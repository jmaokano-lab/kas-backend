@extends('backend.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header text-black">
            <h4>{{ translate('Edit Testimonial') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- PUT method for update --}}

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">{{ translate('Name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $testimonial->name) }}" placeholder="Enter Name" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Designation -->
                <div class="mb-3">
                    <label for="designation" class="form-label">{{ translate('Designation') }}</label>
                    <input type="text" class="form-control @error('designation') is-invalid @enderror"
                           id="designation" name="designation" value="{{ old('designation', $testimonial->designation) }}" placeholder="Enter Designation" required>
                    @error('designation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">{{ translate('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" rows="4" placeholder="Enter Testimonial" required>{{ old('description', $testimonial->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label>{{ translate('Image') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <input type="hidden" name="image" class="selected-files" value="{{ $testimonial->image }}">
                        <button type="button" class="btn btn-primary">{{ translate('Choose File') }}</button>
                    </div>
                    <div class="file-preview">
                        @if($testimonial->image)
                            <img src="{{ asset('storage/' . $testimonial->image) }}" alt="Image" class="img-fluid mt-2" style="max-height: 150px;">
                        @endif
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">{{ translate('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
<script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
@endsection

@endsection
