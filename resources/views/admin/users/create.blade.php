@extends('admin.templates.create')

@section('form_content')
    <div class="form-group row">
        <div class="col-md-6">
            <label>Provider Name</label>
            <input type="text" class="form-control" placeholder="Enter Provider Name" name="provider_name">
        </div>
    </div>
@endsection
