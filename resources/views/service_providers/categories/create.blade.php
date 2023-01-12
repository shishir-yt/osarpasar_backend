@extends('admin.templates.create')

@push('styles')
@endpush


@section('form_content')
    @include('service_providers.categories.form')
@endsection

@push('scripts')
@endpush
