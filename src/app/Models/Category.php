<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $appends = ['path'];

    // One level child
    public function child()
    {
        return $this->hasMany(Category::class, 'ancestor_id');
    }

    // Recursive children
    public function children()
    {
        return $this->hasMany(Category::class, 'ancestor_id')
            ->with('children');
    }

    // One level parent
    public function parent()
    {
        return $this->belongsTo(Category::class, 'ancestor_id');
    }

    // Recursive parents
    public function parents() {
        return $this->belongsTo(Category::class, 'ancestor_id')
            ->with('parent');
    }

    public function getPathAttribute()
    {
        $path = [];
        if ($this->ancestor_id) {
            $parent = $this->parent;
            $parent_path = $parent->path;
            $path = array_merge($path, $parent_path);
        }
        $path[] = $this->name;
        return $path;
    }
}
