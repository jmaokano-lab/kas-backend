<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $testimonials = Testimonial::with('testimonialImage')->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $testimonial = Testimonial::with('testimonialImage')->find($id);

        if (!$testimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $testimonial
        ], 200);
    }

    /**
     * Show instructions for creating a testimonial (for admins).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json([
            'success' => true,
            'message' => 'Send name, designation, description, and image to /api/v2/testimonials POST endpoint.',
            'fields' => [
                'name' => 'string, required',
                'designation' => 'string, required',
                'description' => 'string, required',
                'image' => 'AIZ uploader file ID (integer), required'
            ]
        ], 200);
    }

    /**
     * Store a newly created testimonial in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimonial = Testimonial::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'description' => $request->description,
            'image' => $request->image,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Testimonial created successfully!',
            'data' => $testimonial
        ], 201);
    }

    /**
     * Update the specified testimonial in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::find($id);

        if (!$testimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'designation' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $testimonial->update($request->only(['name', 'designation', 'description', 'image']));

        return response()->json([
            'success' => true,
            'message' => 'Testimonial updated successfully!',
            'data' => $testimonial
        ], 200);
    }

    /**
     * Remove the specified testimonial from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $testimonial = Testimonial::find($id);

        if (!$testimonial) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        $testimonial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Testimonial deleted successfully!'
        ], 200);
    }
}
