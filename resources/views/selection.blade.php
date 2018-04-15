@extends('layouts.app')

@section('profile')
<div id="profile" class="row align-item-center">
  <div class="col-10 media">
    <img src="https://dummyimage.com/80/828187/ffffff.jpg&text=MNS" class="rounded-circle align-self-center mr-3 ml-3" alt="">
    <div class="media-body align-self-center">
      75 Greene Avenue,<br>
      Brooklyn<br>
      NY 11201
    </div>
  </div>
  <div class="col-2">
    <a href="#" id="sidebar-arrow"><img src="{{ asset('/images/tools_off.png')}}" alt="" class="mt-5" ></a>
  </div>
</div>
<div class="row">
  <div class="col p-0">
    <form action="#">
      <select name="rooms" id="rooms" class="custom-select">
        <option value="">MASTER BATH 5F</option>
      </select>
    </form>
  </div>
</div>
@endsection

@section('command')
<div id="command" class="row">
  <div class="col pr-0 pl-0">
    <a id="download" href="{{ asset('storage/' . $selections->first()->image_path) }}" class="btn btn-primary btn-block" Download>Download Image</a>
  </div>
  <div class="col pr-0 pl-0">
    <button id="feedback" class="btn btn-danger btn-block">Submit Feedback</button>
  </div>
</div>
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col">
          <img id="main_img" src="{{ asset('storage/' . $selections->first()->image_path) }}" class="img-fluid" rel="{{$selections->first()->id}}">
        </div>
    </div>
</div>
@endsection

@section('selection')
<div id="view_select" class="fixed-bottom">
  <div class="row">
    <div class="col text-right">
      <a href="#" id="view_select_off"><img src="{{ asset('images/view_select_off.png')}}" alt=""></a>
    </div>
  </div>
  <div id="carouselExampleControls" class="carousel slide p-3" data-ride="carousel">
    <div class="carousel-inner col-10 offset-1">
      @php
        $chunk = $selections->splice(4);
      @endphp
      <div class="carousel-item active">
        <div class="row">
        @foreach ($selections->all() as $selection)
          <div class="col-3">
            <a href="#" class="selections" rel="{{$selection->id}}">
              <img class=" d-block w-100" src="{{ asset('storage/' . $selection->image_path) }}">
            </a>
          </div>
        @endforeach
        </div>
      </div>
      <div class="carousel-item">
        <div class="row">
          @foreach ($chunk as $selection)
            <div class="col-3">
              <a href="#" class="selections" rel="{{$selection->id}}">
                <img class=" d-block w-100" src="{{ asset('storage/' . $selection->image_path) }}">
              </a>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>

<div id="view_select-open">
  <a href="#" id="view_select_on"><img src="{{ asset('images/view_select_on.png') }}" alt=""></a>
</div>
@endsection

@section('after_script')
<script src="{{ asset('js/selection.js') }}" defer></script>
@endsection
