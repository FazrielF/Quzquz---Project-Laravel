@extends('templates.sidebar')

@section('content')
    <div class="container">
        @if (Session::get('success'))
        <div class="alert alert-success">{{Session::get('success')}} <b>Selamat Datang, {{Auth::user()->name}}</b></div>
        @endif
    </div>
@endsection