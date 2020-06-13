@extends('layouts.admin')

@section('main-content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ __('user.pages.edit') }}</h1>
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
                        <user-edit :user="{{$user}}"></user-edit>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection