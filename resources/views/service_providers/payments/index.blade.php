@extends('admin.templates.index')

@section('index_content')
    <div class="table-responsive">
        <table class="table w-100" id="data-table">
            <thead>
                <tr class="text-left text-capitalize">
                    <th> ID </th>
                    <th> Order Id </th>
                    <th> Order By </th>
                    <th> Price </th>
                    <th>Payment Method</th>
                    <th>Paid At</th>
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
                    url: "{{ route('payments.index') }}",
                    type: 'GET',
                    data: function(d) {
                        return $.extend(true, {}, d, {
                            'orderFilter': $('#orderFilter').val()
                        })
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'order_id',
                        name: 'order_id'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ],
            });

            $('#orderFilter').bind("keyup change", function() {
                $('#data-table').DataTable().draw(false);
            });
        });
    </script>
@endpush
