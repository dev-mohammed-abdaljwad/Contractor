@extends('layouts.dashboard')

@section('content')
    @include('pages.dashboard.index')
    @include('pages.dashboard.distribution')
    @include('pages.dashboard.workers')
    @include('pages.dashboard.collection')
@endsection
