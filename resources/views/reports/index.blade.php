@extends('layouts.app')

@section('title', 'Reports')

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
@endpush

@section('breadcrumb-items')
    <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
    <span>Reports</span>
@endsection

@section('content')
    <p>Report content goes here!</p>
@endsection

@push('js')
    <script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
@endpush
