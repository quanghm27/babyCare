@extends('layouts.admin')

@section('main-content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ __('patient.pages.edit') }}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <patient-edit :patient="{{ $patient }}" :edit="{{ json_encode($edit) }}"></patient-edit>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')

@endsection