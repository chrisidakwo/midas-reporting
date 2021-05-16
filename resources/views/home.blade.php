@extends('layouts.app')

@section('breadcrumb-items')
    <i class="material-icons breadcrumb-icon me-1">chevron_right</i>
    <span>Home</span>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="px-4">
{{--                @include('partials.date-selector')--}}

                @php
                    [$startDate, $endDate] = getDefaultStartEndDates();
                @endphp

                <livewire:date-selector :startDate="$startDate"  :endDate="$endDate" />
            </div>
        </div>
    </div>

    <x-dashboard>
        <div class="row">
            <div class="col-12">
                <div class="px-4">
                    <livewire:movement-statistics :endDate="$endDate"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="px-4">
                    <div class="d-flex flex-column w-100 mb-3 mt-10">
                        <h3 class="m-0">Travellers Demographic</h3>
                        <div class="text-secondary tracking-tight">
                            Demographic properties and general statistical characteristics of travellers and movements
                        </div>
                    </div>

                    <div class="d-flex flex-wrap w-100">
{{--                        <livewire:chart-tile chartClass="{{ \App\Charts\TestChart::class }}" refreshIntervalInSeconds="5" />--}}
                    </div>
                </div>
            </div>
        </div>
    </x-dashboard>
@endsection
