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

                    @if(!Auth::guest())
                        <show-component project='@json($project)' auth_id={{Auth::user()->id}} user_id={{$project->user_id}} project_id={{$project->id}}></show-component>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</v-app>
@endsection
