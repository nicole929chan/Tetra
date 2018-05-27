@extends('layouts.app')

@section('after_style')
<link href="{{ asset('css/room.css') }}" rel="stylesheet">
@endsection

@section('profile')
<div class="container-fluid p-0">
  <div class="row">
    <div id="sidebar" class="col-3 position-fixed">
      <div id="profile" class="row align-item-center">
        <div class="col-10 media">
          <img src="https://dummyimage.com/80/828187/ffffff.jpg&text=MNS" class="rounded-circle align-self-center mr-3 ml-3" alt="">
          <div class="media-body align-self-center">
            {{ $project->address }},<br>
            {{ $project->city }} <br>
            {{ $project->state }}
          </div>
        </div>
        <div class="col-2">
          <a href="#" id="sidebar-arrow"><img src="/images/tools_off.png" alt="" class="mt-5" ></a>
        </div>
      </div>
      <div class="row">
        <div class="col pr-0">
          <form action="#">
            @include('rooms.rooms', ['project' => $project, 'room' => $room])
          </form>
        </div>
      </div>
      <div id="command" class="row">
        <div class="col pr-0 pl-0">
          <a id="download" href="{{ asset('storage/' . $selection->image_path) }}" class="btn btn-primary btn-block" Download>Download Image</a>
        </div>
      </div>
    </div>
    <div class="col p-0">
      <nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="menu">
          <div class="container">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
                  <!-- Main Of Navbar -->
                  <ul class="navbar-nav">
                      <!-- Authentication Links -->
                          <li><a class="nav-link" href="#">PROJECTS</a></li>
                          <li><a class="nav-link" href="#">USERS</a></li>
                          <li><a class="nav-link" href="#">HELP</a></li>
                          <li><a  class="nav-link" href="#" id="menu_off"><img src="/images/menu_off.png" alt=""></a></li>
                  </ul>
              </div>
          </div>
      </nav>
    </div>
  </div>
</div>

@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col p-0">
          <a href="#" class="selections" rel="{{$selection->id}}">
            <img class="d-block w-100" src="{{ asset('storage/' . $selection->image_path) }}">
          </a>
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