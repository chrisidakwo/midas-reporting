@extends('layouts.app')

@section('breadcrumb-items')
    <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
    <span>Home</span>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="px-4">
                @include('partials.date-selector')
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <x-dashboard>

            </x-dashboard>
        </div>
    </div>
@endsection
