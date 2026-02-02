<?php

namespace App\Http\Controllers\Api\V2;

use App\Exceptions\Redirectingexception;
use App\Http\Controllers\Controller;
use App\Models\AffiliateConfig;
use App\Models\AffiliateUser;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AffiliateController extends Controller
{
    public function store(Request $request)
    {

    }

    /**
     * @throws Redirectingexception
     */
    public function store_affiliate_user(Request $request)
    {
       $messages = array(
            'name.required' => translate('Name is required'),
            'email_or_phone.required' => $request->email_or_phone == 'email' ? translate('Email is required') : translate('Phone is required'),
            'email_or_phone.email' => translate('Email must be a valid email address'),
            'email_or_phone.numeric' => translate('Phone must be a number.'),
            'email_or_phone.unique' => $request->email_or_phone == 'email' ? translate('The email has already been taken') : translate('The phone has already been taken'),
            'password.required' => translate('Password is required'),
            'password.confirmed' => translate('Password confirmation does not match'),
            'password.min' => translate('Minimum 6 digits required for password')
        );
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6|confirmed',
            'email_or_phone' => ['required', 'in:email,phone'],

            'email' => [
                Rule::requiredIf($request->email_or_phone === 'email'),
                // 'email',

            ],

            'phone' => [
                Rule::requiredIf($request->email_or_phone === 'phone'),
                // 'numeric',

            ],

            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1, ['required', new Recaptcha()], ['sometimes'])
            ]
        ], $messages);
        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        if (!Auth::check()) {
            if (User::where('email', $request->email)->first() != null) {

                return response()->json([
                    'result' => true,
                    'message' => translate('Email already exists!')
                ]);
            }
            if ($request->password == $request->password_confirmation) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->user_type = "customer";
                $user->password = Hash::make($request->password);
                $user->save();

                auth()->login($user, false);

                if (get_setting('email_verification') != 1) {
                    $user->email_verified_at = date('Y-m-d H:m:s');
                    $user->save();
                } else {
                    event(new Registered($user));
                }
            } else {
                return response()->json([
                    'result' => true,
                    'message' => translate('Sorry! Password did not match.')
                ]);

            }
        }

        $affiliate_user = Auth::user()->affiliate_user;
        if ($affiliate_user == null) {
            $affiliate_user = new AffiliateUser;
            $affiliate_user->user_id = Auth::user()->id;
        }
        $data = array();
        $i = 0;
        foreach (json_decode(AffiliateConfig::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i];
            } elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_' . $i]);
            } elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_' . $i]->store('uploads/affiliate_verification_form');
            }
            $data[] = $item;
            $i++;
        }
        $affiliate_user->informations = json_encode($data);
        if ($affiliate_user->save()) {
            return response()->json([
                'result' => true,
                'message' => translate('Your verification request has been submitted successfully!')
            ]);

        }
        return response()->json([
            'result' => true,
            'message' => translate('Sorry! Something went wrong.')
        ]);


    }
}
