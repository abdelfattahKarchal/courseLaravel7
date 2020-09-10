<?php

namespace App;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use SoftDeletes;

 protected $fillable = ['content','user_id'];

    public function post(){
        return $this->belongsTo('App\Post');
    }

    // local scope doit être prefixer par le mot scope
    public function scopeDernier(Builder $query){
        $query->orderBy(static::UPDATED_AT,'desc');
    }

    public static function boot(){
        parent::boot();

        static::creating(function(Comment $comment){
            Cache::forget("post-show-{$comment->post->id}");
        });

        //apply scope for all posts queries
      //  static::addGlobalScope(new LatestScope);
    }

    public function user(){
        return $this->BelongsTo('App\User');
    }
}
