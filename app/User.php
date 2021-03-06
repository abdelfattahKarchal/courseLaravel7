<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }

    /**
     *  function permet de retourner les meilleurs utilisateurs active qui post beaucoup
     */
    public function scopeMostUsersActive(Builder $query){
        return $query->withCount('posts')->orderBy('posts_count','desc');
    }


    /**
     * fonction permet de retourner les utilisateurs qui ont posté le dernier mois
     */
    public function scopeMostUsersActiveInLastMonth(Builder $query){
        return $query->withCount(['posts'=>function(Builder $query){
            return $query->whereBetween(static::CREATED_AT,[now()->subMonth(1),now()]);
        }])
        ->having('posts_count','>=',3)
        ->orderBy('posts_count','desc');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
}
