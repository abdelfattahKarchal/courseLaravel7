<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path'];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    /**
     * retourn l'url de l image
     */
    public function url(){
        return Storage::url($this->path);
    }
}
