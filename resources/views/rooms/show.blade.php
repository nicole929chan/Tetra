@extends('layouts.app')

@section('after_style')
<link href="{{ asset('css/room.css') }}" rel="stylesheet">
@endsection

@section('content')

  <room :room="{{ $room}}" :version="{{ $version}}" :project="{{ $project }}"></room>

@endsection
