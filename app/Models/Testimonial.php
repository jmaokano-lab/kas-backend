<?php

namespace App\Models;

use App\Models\Upload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    use HasFactory;
    protected $fillable = [
    'name',
    'designation',
    'description',
    'image',
];

 public function testimonialImage()
    {
        return $this->belongsTo(Upload::class,'image'); // অথবা asset($this->file_path)
    }

}
