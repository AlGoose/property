@extends('layouts.app')

@section('content')
    <script>
        var managers = @json($managers)
    </script>
@endsection
