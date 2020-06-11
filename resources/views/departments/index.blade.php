@extends('layouts.admin')

@section('main-content-header')
    <h1 class="m-0 text-dark">{{ __('department.pages.index') }}</h1>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('departments.store') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-primary">{{__('department.btnSubmit')}}</button>
                                </div>
                                <input type="text" class="form-control" name="name" placeholder="Nhập tên khoa">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('department.list') }}</h3>
                    </div>
                    <div class="card-body">
                        <department-table :departments="{{ $departments }}"></department-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection