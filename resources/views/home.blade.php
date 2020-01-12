@extends('layouts.app')

@section('content')
<v-app>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <form-component></form-component>
                </div>
            </div>
        </div>
    </div>
</div>
</v-app>

{{-- <script src=”{{ asset('js/app.js') }}” defer></script> --}}
@endsection
