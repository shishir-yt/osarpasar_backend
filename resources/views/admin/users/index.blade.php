@extends('admin.templates.index')

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100 table-hover">
            <tr>
                <th> ID </th>
                <th>Name</th>
                <th>Email</th>
                <th>Action</th>
            </tr>

            @foreach ($serviceProviders as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    @include('admin.templates.index_action')
                </tr>
            @endforeach

        </table>
    </div>
@endsection
