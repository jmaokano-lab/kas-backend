<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
       public function index()
    {
        $testimonials = Testimonial::all();
        return view ('backend.testimonial.index',compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('backend.testimonial.create');
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|string', // aizuploader স্ট্রিং পাঠায়
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Testimonial::create([
            'name'        => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'image'       => $request->image, // যেমন: "uploads/testimonials/abc.jpg" বা "166"
        ]);

        return redirect()->route('testimonials.index')
                         ->with('success', 'Testimonial created successfully!');
    }

public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('backend.testimonial.edit', compact('testimonial'));
    }

    // Update testimonial
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|string', // ❗ এটি ফাইল নয়, স্ট্রিং পাথ
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $testimonial = Testimonial::findOrFail($id);

        $testimonial->update([
            'name'        => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'image'       => $request->image, // সরাসরি পাথ আপডেট করুন
        ]);

        return redirect()->route('testimonials.index')
                         ->with('success', 'Testimonial updated successfully!');
    }
    // Delete testimonial
    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Delete image
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }

        $testimonial->delete();

        return redirect()->route('testimonials.index')->with('success', 'Testimonial deleted successfully!');
    }


}
