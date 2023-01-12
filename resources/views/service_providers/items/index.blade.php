@extends('admin.templates.index')

@section('index_content')
    <div class="d-flex  justify-content-between align-items-center mb-2">
        <div class="d-flex  align-items-center">
            <div class="mr-3">
                <label for="" id="actionsName">Filter: </label>
            </div>

            <select id="categoryFilter" class="form-control form-control-sm " style="width: 210px" aria-label="Select Category">
                <option value="">Select Category</option>
                @foreach ($categories ?? [] as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
                <tr class="text-left text-capitalize">
                    <th> ID </th>
                    <th> Category </th>
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
                ajax: {
                    url: "{{ route('items.index') }}",
                    type: 'GET',
                    data: function(d) {
                        return $.extend(true, {}, d, {
                            'categoryFilter': $('#categoryFilter').val()
                        })
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'category_id',
                        name: 'category_id'
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

            $('#categoryFilter').bind("keyup change", function() {
                $('#data-table').DataTable().draw(false);
            });
        });
    </script>
@endpush
