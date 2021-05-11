@extends('layouts.app')

@section('title', 'Movement Statistics')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@endpush

@section('breadcrumb-items')
    <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
    <span>Movement Statistics</span>
@endsection

@section('content')
    <p>Movement statistical content goes here!</p>
@endsection

@push('js')
    <script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
@endpush
