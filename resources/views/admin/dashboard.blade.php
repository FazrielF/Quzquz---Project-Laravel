@extends('templates.sidebar')

@section('content')
    <div class="container">
        @if (Session::get('success'))
            <div class="alert alert-success">{{Session::get('success')}} <b>Selamat Datang, {{Auth::user()->name}}</b></div>
        @endif
        <div class="row mt-5">
            <div class="col-6">
                <canvas id="chartBar"></canvas>
            </div>
        </div>
    </div>
@endsection