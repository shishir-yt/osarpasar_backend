@extends('admin.templates.show')
@push('styles')
@endpush
@section('form_content')
    <div class="row">
        <div class="col-md-6">
            <label for=""><span class="show-text">Name:</span></label> {{ $item->name ?: 'N/A' }}<br>
        </div>

        <div class="col-md-6">
            <label for=""><span class="show-text">Category:</span></label>
            {{ $item->category ? $item->category->name : 'N/A' }}<br>
        </div>
    </div>
@endsection
