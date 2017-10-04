<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query
                ->where('name', 'like', "%$term%")
                ->orWhere('description', 'like', "%$term%");
        }
        return $query;
    }
}
