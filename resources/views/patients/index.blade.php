@extends('layouts.admin')

@section('main-content-header')
    <h1 class="m-0 text-dark">{{ __('patient.pages.list') }}</h1>
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h5 class="card-title"> <i class="fa fa-lg fa-bullhorn"></i> Dữ liệu sẽ được cập nhật sau
                    <span id="counter" style="color:orange;font-weight:bold">--</span>
                </h5>
            </div>
            <div class="card-body">
                <patient-list :patients="{{ $patients }}"></patient-list>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        window.onload = countDown;

        function countDown() {
            let timer = 60000;
            let counterText = document.getElementById('counter');

            let interval = setInterval(function(){
                console.log(interval);
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