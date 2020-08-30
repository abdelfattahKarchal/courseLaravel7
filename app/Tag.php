<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * Many To Many relation with Post Model
     */
    public function posts(){
        return $this->belongsToMany('App\Post');
    }
}
