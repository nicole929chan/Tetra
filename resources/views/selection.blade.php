@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col">
          <img src="{{ asset('storage/' . $selections->shift()->image_path) }}" class="img-fluid">
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
            <img class="d-block w-100" src="{{ asset('storage/' . $selection->image_path) }}">
          </div>
        @endforeach
        </div>
      </div>
      <div class="carousel-item">
        <div class="row">
          @foreach ($chunk as $selection)
            <div class="col-3">
              <img class="d-block w-100" src="{{ asset('storage/' . $selection->image_path) }}">
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
<script type="text/javascript">
$(document).ready(function(){
  var selection_height = 0;
  //關閉底部
  $("#view_select_off").click(function(event){
    event.preventDefault();
    selection_height = $("#view_select").height();
    $("#view_select-open").css('display', 'block');
    $("#view_select").stop(true).animate({height:0, opacity: '0'}, 'slow', function(){
      $("#view_select").css('display', 'none');
    });
  });

  //展開底部
  $("#view_select_on").click(function(event){
    event.preventDefault();
    $("#view_select-open").css('display', 'none');
    $("#view_select").css('display', 'block');
    $("#view_select").stop(true).animate({height: selection_height, opacity: '1'}, 'slow', function(){
    });
  });

});
</script>
@endsection
