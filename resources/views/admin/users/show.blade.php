@extends('admin.templates.show')

@section('form_content')
    <div class="row">
        <div class="col-md-6">
            <label>Name: </label> {{ $item->name }}<br>
        </div>
        <div class="col-md-6">
            <label>Email: </label> {{ $item->email }}<br>
        </div>

        <div class="col-md-6 my-3">
            <label>Type: </label> {{ $item->type }}<br>
        </div>
    </div>
@endsection
