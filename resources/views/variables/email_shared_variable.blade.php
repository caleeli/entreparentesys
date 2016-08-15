@extends('layouts.email')

@section('content')
    <p>
        {{ trans('variable.variable_shared_text') }}

        {{ $variable }}
    </p>
@endsection