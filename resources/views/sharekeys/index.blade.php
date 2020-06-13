@extends('layouts.admin')

@section('main-content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">{{ __('sharekey.pages.index') }}</h1>
                </div>
                <div class="col-sm-2 text-right">
                    <a href="{{ route('patients.create') }}" class="btn btn-info">
                        <i class="nav-icon fa fa-plus"></i>
                        {{ __('patient.pages.create') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <sharekey-table :sharekeys="{{ $sharekeys }}"></sharekey-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection