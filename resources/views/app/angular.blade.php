@extends('layouts.app')

@section('content')
    <div id="app"></div>
    <app-root></app-root>
    <script type="text/javascript" src="angular/inline.bundle.js"></script>
    <script type="text/javascript" src="angular/polyfills.bundle.js"></script>
    <script type="text/javascript" src="angular/styles.bundle.js"></script>
    <script type="text/javascript" src="angular/vendor.bundle.js"></script>
    <script type="text/javascript" src="angular/main.bundle.js"></script>
@endsection
