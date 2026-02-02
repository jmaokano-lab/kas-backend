<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use PreventDemoModeChanges;

    use SoftDeletes;

    public function category() {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
    // public function comments()
    // {
    //     return $this->hasMany(BlogComment::class);
    // }


    public function comments()
{
    return $this->hasMany(\App\Models\BlogComment::class, 'blog_id')
                ->with('user') // কমেন্টের সাথে ইউজার ডেটা চাইলে
                ->latest();    // সর্বশেষ কমেন্ট প্রথমে
}

}
