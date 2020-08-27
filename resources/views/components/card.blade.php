<div class="card mt-4">
    <div class="card-body">
        <h4 class="card-title">{{$title}}</h4>
        <p class="text-muted"> {{$text}}</p>
    </div>
    <ul class="list-group list-group-flush">
    @if (!empty(trim($slot)))
        {{$slot}}
    @else
        @foreach ($items as $item)
            <li class="list-group-item">

                <x-badge type="info">
                    {{$item->posts_count}}
                </x-badge>
                {{-- dans le cas où on utilise pluck('name') avec un seule paramettre; --}}
                {{-- {{ $item }} --}}
                {{ $item->name }}
            </li>
        @endforeach ($items as $item)
    @endif



    </ul>
</div>
