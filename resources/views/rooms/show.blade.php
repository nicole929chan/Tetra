@extends('layouts.app')

@section('content')

  <room :room="{{ $room}}" :version="{{ $version}}" :project="{{ $project }}"></room>

@endsection
