 {{-- card 1 Most post Commented --}}
        {{-- <div class="card">
            <div class="card-body">
                <h4 class="card-title">Most Commented</h4>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostCommented as $post)
                     <li class="list-group-item">
                     <a href="{{ route('posts.show',['post'=>$post->id]) }}">{{$post->title}}</a>
                        <p>
                            {{-- <span class="badge badge-success">
                                {{$post->comments_count}}
                            </span> --}}
                            {{-- @component('partials.badge',['type'=>'success'])
                                {{$post->comments_count}}
                            @endcomponent --}}
                            {{-- <x-badge>
                                {{$post->comments_count}}
                            </x-badge>
                        </p>
                    </li>
                @endforeach ($posts as $post)
            </ul>
        </div>  --}}
 {{-- utilisation du component card on remplacant le code en haut par ce lui ci en bas
    j'ai ajouté le slot pour la 1ere card car possède de contenu different (pas de text et possède d'un <a href>)  --}}
        <x-card title="Most Commented">
            @foreach ($mostCommented as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show',['post'=>$post->id]) }}">{{$post->title}}</a>
                    <p>
                        {{-- <span class="badge badge-success">
                            {{$post->comments_count}}
                        </span> --}}
                        {{-- @component('partials.badge',['type'=>'success'])
                            {{$post->comments_count}}
                        @endcomponent --}}
                        <x-badge>
                            {{$post->comments_count}}
                        </x-badge>
                    </p>
                </li>
            @endforeach ($posts as $post)
        </x-card>
        {{-- end card 1 Most post Commented --}}
        {{-- ------------------------------------------------------------------------------------------------------ --}}

        {{-- card 2 Most users post written --}}
        {{-- <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Most users</h4>
                <p class="text-muted"> Most Users post written</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostUsersActive as $user)
                    <li class="list-group-item">
                        {{-- <span class="badge badge-info"> {{$user->posts_count}} </span> --}}
                        {{-- @component('partials.badge',['type'=>'info'])
                            {{$user->posts_count}}
                        @endcomponent --}}
                        {{-- <x-badge type="info">
                            {{$user->posts_count}}
                        </x-badge>
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div> --}}

        {{-- utilisation du component card on remplacant le code en haut par ce lui ci en bas  --}}
        <x-card
            title="Most users"
            text="Most Users post written"
            {{-- :items="collect($mostUsersActive)->pluck('name')" --}}
            :items="collect($mostUsersActive)"
        ></x-card>
        {{-- end card 2 Most users post written --}}
{{-- ------------------------------------------------------------------------------------------------------ --}}
        {{-- card 3  Most Users active in last month --}}
        {{-- <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Most users</h4>
                <p class="text-muted"> Most Users active in last month</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostUsersActiveInLastMonth as $user)
                    <li class="list-group-item">
                        {{-- <span class="badge badge-info"> {{$user->posts_count}} </span> --}}
                        {{-- @component('partials.badge',['type'=>'info'])
                            {{$user->posts_count}}
                        @endcomponent --}}
                        {{-- <x-badge type="info">
                            {{$user->posts_count}}
                        </x-badge>
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div> --}}


        <x-card
            title="Most users"
            text="Most Users active in last month"
            {{-- items="collect($mostUsersActiveInLastMonth)->pluck('name')" --}}
            :items="collect($mostUsersActiveInLastMonth)"
    ></x-card>
        {{-- end card 3  Most Users active in last month --}}
