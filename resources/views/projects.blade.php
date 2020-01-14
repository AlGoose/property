@extends('layouts.app')

@section('content')
<v-app>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Projektlista</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    //TODO: КАК БЫТЬ С АДРЕСАЦИЕЙ ВЦЕЛОМ? КАК ОТКРЫТЬ "ТАБЛИЦУ"?
                    <projects-component projects='@json($projects)'></projects-component> 
                    <div>
                        {{$projects->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</v-app>
@endsection
