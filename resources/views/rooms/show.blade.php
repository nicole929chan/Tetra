@extends('layouts.app')

@section('after_style')
<link href="{{ asset('css/room.css') }}" rel="stylesheet">
@endsection

@section('content')

  <room :current="{{ $room}}" :version="{{ $version}}" :project="{{ $project }}" :rooms="{{ $project->rooms }}"></room>

@endsection
