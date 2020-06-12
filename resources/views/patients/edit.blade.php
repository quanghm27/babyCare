@extends('layouts.admin')

@section('main-content-header')
    <h1 class="m-0 text-dark">{{ __('patient.pages.edit') }}</h1>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <patient-edit :patient="{{ $patient }}"></patient-edit>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')

@endsection