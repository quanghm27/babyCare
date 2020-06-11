@extends('layouts.admin')

@section('main-content-header')
    <h1 class="m-0 text-dark">{{ __('sharekey.pages.index') }}</h1>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <sharekey-table :sharekeys="{{ $sharekeys }}"></sharekey-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection