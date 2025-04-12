<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function blogPostsCategory()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_category');
    }
}
