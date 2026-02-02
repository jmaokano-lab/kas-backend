@extends('backend.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header  text-black">
            <h4>{{ translate('Create Testimonial') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">{{ translate('Name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}" placeholder="Enter Name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Designation -->
                <div class="mb-3">
                    <label for="designation" class="form-label">{{ translate('Designation') }}</label>
                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                           id="designation" name="designation" value="{{ old('designation') }}" placeholder="Enter Designation" required>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">{{ translate('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4" placeholder="Enter Testimonial" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Image -->
                <div class="mb-3">

                    <label>Image</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <input type="hidden" name="image" class="selected-files">
                        <button type="button" class="btn btn-primary">Choose File</button>
                    </div>
                    <div class="file-preview"></div>

                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">{{ translate('Create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('script')
<script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
@endsection

@endsection
