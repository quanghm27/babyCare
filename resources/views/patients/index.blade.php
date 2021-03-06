@extends('layouts.admin')

@section('main-content-header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h1 class="m-0 text-dark">{{ __('patient.pages.list') }}</h1>
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
        <div class="card card-default">
            @if ($patients->count() > 0)
                <div class="card-header">
                    <h5 class="card-title"> <i class="fa fa-lg fa-bullhorn"></i> Dữ liệu sẽ được cập nhật sau
                        <span id="counter" style="color:orange;font-weight:bold">--</span>
                    </h5>
                    <button id="btn-toggle-alert" class="pull-right btn btn-info">
                        <i class="nav-icon fa fa-bullhorn"></i>
                        Cảnh báo đang tắt
                    </button>
                </div>
                <div class="card-body">
                    <patient-list :patients="{{ $patients }}"></patient-list>
                </div>
            @else
                <div class="card-header">
                    <h5 class="card-title"> <i class="fa fa-lg fa-bullhorn"></i> Chưa có bệnh nhân...
                    </h5>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        window.onload = function() {
            countDown();
            initalAlert();
        }

        $('#btn-toggle-alert').click(function(){
            let alert = JSON.parse(localStorage.getItem('alertSound'))
            localStorage.setItem('alertSound', JSON.stringify(!alert))
            let element = $(this)
            let icon = '<i class="nav-icon fa fa-bullhorn"></i>'
            if (element.hasClass('btn-info')) {
                element.removeClass('btn-info').addClass('btn-danger')
                element.html(`${icon + ' Cảnh báo đang tắt'}`)
            } else {
                element.removeClass('btn-danger').addClass('btn-info')
                element.html(`${icon + ' Cảnh báo đang bật'}`)
            }
        });

        function initalAlert() {
            let alert = JSON.parse(localStorage.getItem('alertSound'))
            let element = $('#btn-toggle-alert')
            let icon = '<i class="nav-icon fa fa-bullhorn"></i>'
            if (alert) {
                // alert ON
                element.removeClass('btn-danger').addClass('btn-info')
                element.html(`${icon + ' Cảnh báo đang bật'}`)
            } else {
                // alert OFF
                element.removeClass('btn-info').addClass('btn-danger')
                element.html(`${icon + ' Cảnh báo đang tắt'}`)
            }
        }

        function countDown() {
            let timer = 60000;
            let counterText = document.getElementById('counter');

            let interval = setInterval(function(){
                timer -= 1000;
                counterText.textContent = `${timer / 1000} giây`;
                if (timer <= 0) {
                    counterText.textContent = 'đang cập nhật ...'
                    clearInterval(interval);
                    location.reload();
                }
            }, 1000);
        }
    </script>
@endsection