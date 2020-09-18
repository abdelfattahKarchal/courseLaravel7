<?php

namespace App;

use App\Scopes\AdminShowDeleteScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['title','content','slug','active','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        // relationShip and apply scope named(scopeDernier), it s a local scop into comment model
        return $this->hasMany('App\Comment')->dernier();
    }
    /**
     * pour faire la suppresion des objets associés soit on utilise les events, soit  onDelete cascade (migration)
     * pour supprimer les commentaires d'un post il nous faut les supprimer dans la phase de deleting(event)
     * overrid la methode boot du Class Model pour qu on utiliser des events
     * events[ retrieved, creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored]
     *
     * la suppression est logic car on a declaré softdelete dans le model Comment
     * @return void
     */
    public static function boot(){
        //apply Global scope for showing the trashet posts for admin
        static::addGlobalScope(new AdminShowDeleteScope);
        parent::boot();


        //apply scope for all posts queries (Global Scope)
        static::addGlobalScope(new LatestScope);

        static::deleting(function(Post $post){
            $post->comments()->delete();
        });
        static::updating(function(Post $post){
            Cache::forget("post-show-{$post->id}");
        });
        static::restoring(function(Post $post){
            $post->comments()->restore();
        });

    }

    /**
     * function pour récupérer les meilleurs posts commentés (les plus commentés)
     *  LocalScope
     */
    public function scopeMostCommented(Builder $query){
        // le champ comments_count se génère automatiquement lors d'execution de la méthode withCount()
        // contient le nombre de posts retournés
        return $query->withCount('comments')->orderBy('comments_count','desc');
    }
    public function scopePostWithCommentsTags(Builder $query){
        $query->withCount('comments')->with(['user','tags']);
    }

    /**
     * Many To Many relation with Tag Model
     */
    public function tags(){
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    /**
     * one to one relation one post have one image
     */
    public function image(){
        return $this->hasOne(Image::class);
    }

}
