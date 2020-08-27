<div class="text-muted">
  {{ !empty(trim($slot)) ? $slot : 'Added'}}   {{$date}} {{ isset($name) ? ', by '.$name : null }}
</div>
