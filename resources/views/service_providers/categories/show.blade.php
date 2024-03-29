@extends('admin.templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row">
        <div class="col-md-6">
            <label for=""><span class="show-text">Name:</span></label> {{ $item->name ?: 'N/A' }}<br>
        </div>
    </div>
    <div class="row my-4">
        <div class="col-md-12 mt-4">
            <label for=""><span class="show-text">Description</span></label>
            <hr>
            <p>
                {!! $item->description !!}
            </p>
        </div>
    </div>
@endsection
