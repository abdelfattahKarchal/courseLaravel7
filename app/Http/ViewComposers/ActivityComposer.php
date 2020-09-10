<?php
namespace App\Http\ViewComposers;

use App\Post;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 *
 *  permet d injecter des variables sur des views on specifiant les view sur le ficher
 *  d'enregistrement (on registre pour qu elle soit connu sur l application) Providers/AppServiceProvider.php sur la méthéode boot
 */
class ActivityComposer{

    public function compose(View $view){
        $mostCommented = Cache::remember('mostCommented', now()->addMinutes(10), function () {
            return Post::mostCommented()->take(5)->get();
        });
        $mostUsersActive = Cache::remember('mostUsersActive', now()->addMinutes(10), function () {
            return User::mostUsersActive()->take(5)->get();
        });
        $mostUsersActiveInLastMonth = Cache::remember('mostUsersActiveInLastMonth', now()->addMinutes(10), function () {
            return User::mostUsersActiveInLastMonth()->take(5)->get();
        });

        $view->with([
            'mostCommented' => $mostCommented,
            'mostUsersActive' => $mostUsersActive,
            'mostUsersActiveInLastMonth' => $mostUsersActiveInLastMonth
        ]);
    }
}
