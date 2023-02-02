@extends('admin.templates.show')

@section('form_content')
    <div class="row">
        <div class="col-md-6">
            <label>Name: </label> {{ $item->name }}<br>
        </div>
        <div class="col-md-6">
            <label>Email: </label> {{ $item->email }}<br>
        </div>
    </div>
@endsection
