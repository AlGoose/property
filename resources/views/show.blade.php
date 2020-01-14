@extends('layouts.app')

@section('content')
<v-app>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Välkommen {{ Auth::user()->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <h1>{{ $error }}</h1>
                            @endforeach
                    </div>
                    @else
                        @if(!Auth::guest())
                            <div>
                                <p>Имя: {{$project->name}}</p>
                                <p>Адрес: {{$project->address}}</p>
                                <p>Заказчик: {{$project->customer}}</p>
                                <p>Конкуренты: {{$project->opponents}}</p>
                                <p>Контакты: {{$project->contacts}}</p>
                                <p>Дата: {{$project->date}}</p>
                                <p>Менеджер: {{$project->manager}}</p>
                            </div>
                            <a href="/project" class="btn btn-primary" style="color: white;">Назад</a>
                            @if(Auth::user()->id == $project->id)
                                <a href="" class="btn btn-primary" style="color: white;">Редактировать</a>
                                <button type="button" class="btn btn-danger">Удалить</button>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</v-app>
@endsection
