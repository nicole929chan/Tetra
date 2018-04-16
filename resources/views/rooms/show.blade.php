@extends('layouts.app')

@section('after_style')
<link href="{{ asset('css/room.css') }}" rel="stylesheet">
@endsection

@section('profile')
<div id="profile" class="row align-item-center">
  <div class="col-10 media">
    <img src="https://dummyimage.com/80/828187/ffffff.jpg&text=MNS" class="rounded-circle align-self-center mr-3 ml-3" alt="">
    <div class="media-body align-self-center">
      {{ $room->project->address }}<br>
      {{ $room->project->city }}<br>
      {{ $room->project->country }}
    </div>
  </div>
  <div class="col-2">
    <a href="#" id="sidebar-arrow"><img src="{{ asset('/images/tools_off.png')}}" alt="" class="mt-5" ></a>
  </div>
</div>
<div class="row">
  <div class="col pr-0">
    <form action="#">
      <select name="rooms" id="rooms" class="custom-select">
        <option value="">{{ $room->name }}</option>
      </select>
    </form>
  </div>
</div>
@endsection

@section('activity')
<div id="activity" class="row">
  <div class="col pr-0">
    <div id="accordion">
      <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              Activity
            </button>
          </h5>
        </div>
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
          <div class="card-body">
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header" id="headingTwo">
          <h5 class="mb-0">
            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              Files
            </button>
          </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
          <div class="card-body">
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('command')
<div id="command" class="row">
  <div class="col pr-0 pl-0">
    <button class="btn btn-primary btn-block">Download Image</button>
  </div>
  <div class="col pr-0 pl-0">
    <button class="btn btn-danger btn-block">Submit Feedback</button>
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
      @foreach ($room->versions as $key => $value)
        <li class="m-0 @if($value->id == $version->id) active @endif ">
          <div style="padding: 5px 10px;">
            <div class="float-left mr-3">
              <div style="font-size:10px;">{{ $value->created_at->format('Y-m-d') }}</div>
              <div>VERSION</div>
            </div>
            <div class="version">{{ $key+1 }}</div>
          </div>
      </li>
      @endforeach
      <li class="m-0">
        <div style="padding: 5px 10px;">
          <div class="float-left mr-3">
            <div style="font-size:10px;">12 Dec 2017</div>
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
