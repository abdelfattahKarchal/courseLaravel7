<?php

namespace App;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    public function post(){
        return $this->belongsTo('App\Post');
    }

    // local scope doit être prefixer par le mot scope
    public function scopeDernier(Builder $query){
        $query->orderBy(static::UPDATED_AT,'desc');
    }

    public static function boot(){
        parent::boot();

        //apply scope for all posts queries
      //  static::addGlobalScope(new LatestScope);
    }

    public function user(){
        return $this->BelongsTo('App\User');
    }
}
