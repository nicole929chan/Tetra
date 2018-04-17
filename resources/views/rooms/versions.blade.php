@foreach ($room->versions as $key => $value)
  <li class="m-0 @if($value->id == $version->id) active @endif ">
    <div style="padding: 5px 10px;">
      <a href="{{ route('rooms.show', [$room->id, $value->id]) }}">
        <div class="float-left mr-3">
          <div style="font-size:10px;">{{ $value->created_at->format('Y-m-d') }}</div>
          <div>VERSION</div>
        </div>
        <div class="version">{{ $key+1 }}</div>
      </a>
    </div>
</li>
@endforeach