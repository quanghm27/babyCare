@extends('layouts.admin')

@section('main-content-header')
    <h1 class="m-0 text-dark">{{ __('patient.pages.list') }}</h1>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <patient-list :patients="{{ $patients }}"></patient-list>
        </div>
    </div>
@endsection