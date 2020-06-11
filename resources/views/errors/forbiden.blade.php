@extends('layouts.admin')

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="error-page">
                <h2 class="headline text-warning">403</h2>

                <div class="error-content">
                    <h3><i class="fa fa-exclamation-triangle text-warning"></i> Không được quyền truy cập</h3>

                    <p>
                        We could not find the page you were looking for.
                        Meanwhile, you may <a href="../../index.html">return to dashboard</a> or try using the search form.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection