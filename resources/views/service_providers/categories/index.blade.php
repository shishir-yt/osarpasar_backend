@extends('admin.templates.index')

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
                <tr class="text-left text-capitalize">
                    <th> ID </th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
            });
        });
    </script>
@endpush
