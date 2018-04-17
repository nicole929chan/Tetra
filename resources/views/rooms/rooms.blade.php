<select name="rooms" id="rooms" class="custom-select">
  @foreach ($project->rooms as $value)
    <option value="{{ $value->id }}" @if ($value->id == $room->id) selected @endif>
      {{ $value->name }}
    </option>
  @endforeach	
</select>