@extends('layouts.default1')

@section ('content')

    <h1>Hello</h1>
    <div id="app">
        <app></app>
    </div>
    
    @section ("jquery")
    <script src="{{ mix('mix/js/bootstrap.js') }}"></script>
    <script src="{{ mix('mix/js/app.js') }}"></script>
    @endsection
@endsection