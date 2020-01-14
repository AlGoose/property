@extends('layouts.app')

@section('content')
<v-app>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Projektlista</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Address</th>
                                <th scope="col">Manager</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $item)
                            <tr>
                                <th scope="row">{{$loop->index + 1}}</th>
                                <td>{{$item->name}}</td>
                                <td>{{$item->address}}</td>
                                <td>{{$item->manager}}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div>
                        {{$projects->links()}}
                    </div>
                </div>

                <projects-component projects='@json($projects)'></projects-component>
                
            </div>
        </div>
    </div>
</div>
</v-app>
@endsection
