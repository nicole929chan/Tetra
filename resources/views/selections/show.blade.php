@extends('layouts.app')

@section('after_style')
<link href="{{ asset('css/room.css') }}" rel="stylesheet">
@endsection

@section('profile')
<div id="profile" class="row align-item-center">
  <div class="col-10 media">
    <img src="https://dummyimage.com/80/828187/ffffff.jpg&text=MNS" class="rounded-circle align-self-center mr-3 ml-3" alt="">
    <div class="media-body align-self-center">
      {{ $project->address }}<br>
      {{ $project->city }}<br>
      {{ $project->country }}
    </div>
  </div>
  <div class="col-2">
    <a href="#" id="sidebar-arrow"><img src="{{ asset('/images/tools_off.png')}}" alt="" class="mt-5" ></a>
  </div>
</div>
<div class="row">
  <div class="col pr-0">
    <form action="#">
      @include('rooms.rooms', ['project' => $project, 'room' => $room])
    </form>
  </div>
</div>
@endsection

@section('command')
<div id="command" class="row">
  <div class="col pr-0 pl-0">
    <button class="btn btn-primary btn-block">Download Image</button>
  </div>
  <div class="col pr-0 pl-0">
    <button type="submit" class="btn btn-secondary btn-block" disabled>Submit Feedback</button>
  </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col p-0">
          <div id="mapid" style="height:200px; width:100%;"></div>
        </div>
    </div>
</div>
@endsection

@section('bottom')

<div class="row fixed-bottom justify-content-center">
  <div class="col-8 offset-2">
    <ul id="bottom-tool">
      @include('rooms.versions', ['room' => $room, 'version' => null])
      <li class="m-0 active">
        <div style="padding: 5px 10px;">
          <div class="float-left mr-3">
            <div style="font-size:10px;">{{ $selection->updated_at->format('Y-m-d') }}</div>
            <div>View selection</div>
          </div>
        </div>
      </li>
      <li class="m-0">
        <div style="padding: 5px 10px;" class="text-center">
          <a href="#" id="bottom_off"><img src="{{ asset('/images/toggle_on.png') }}" alt="" style="padding:13px;"></a>
        </div>
      </li>
    </ul>
  </div>
</div>

  <div id="view_select-open">
    <a href="#" id="view_select_on"><img src="{{ asset('images/view_select_on.png') }}" alt=""></a>
  </div>
@endsection

@section('after_script')
<script src="{{ asset('js/room.js') }}" defer></script>
@endsection
