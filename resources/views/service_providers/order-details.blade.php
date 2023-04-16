{{-- @extends('adminlte::page')

@section('content')
    <div class="container">
        <h1>Order Details</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Pickup Address</th>
                    <th>Destination Address</th>
                    <th>Pickup Date</th>
                    <th>Pickup Time</th>
                    <th>Items</th>
                    <th>Quantity</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($order->items ?? [] as $item)
                    <tr>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->pickup_address }}</td>
                        <td>{{ $item->destination_address }}</td>
                        <td>{{ $item->pickup_date }}</td>
                        <td>{{ $item->pickup_time }}</td>
                        <td>{{ $item->items }}</td>
                        <td>{{ $item->quantity }}</td>

                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $order->total }}</td>
                </tr>
            </tfoot>
        </table>

     <form method="POST" action="#">
            @csrf
            <div class="form-group">
                <label for="note">Enter Response</label>
                <textarea class="form-control" id="note" name="note" rows="3">{{ $order->note }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Response</button>
        </form>
    </div>
@endsection --}}



@extends('adminlte::page')

@section('content')
    <div class="container">
        <h1>Order Details</h1>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h2 class="bg-gradient-blue px-3 py-2">User Information</h2>
            </div>

            <div class="col-md-6">
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Phone:</strong> {{ $order->user->phone_number ?: '----' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Pickup Address:</strong> {{ $order->orderAddress->pickup_address }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Destination Address:</strong> {{ $order->orderAddress->destination_address }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Pickup Date:</strong> {{ $order->orderAddress->pickup_date }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>Pickup Time:</strong> {{ $order->orderAddress->pickup_time }}</p>
            </div>
        </div>
        <hr>
        <div class="col-md-12">
            <h2 class="bg-gradient-blue px-3 py-2">Items To Shift</h2>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Item</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items ?? [] as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <form method="POST" action="{{ route('response.send') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $order->user_id }}">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <div class="form-group row">
                <div class="col-md-12">
                    <h2 class="bg-gradient-blue px-3 py-2">Send Response</h2>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-block p-3">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ session('success') }}
                </div>
            @endif
            <div class="form-group row">
                <div class="col-md-6 mt-2">
                    <label for="note">Status</label>
                    <select name="status" required class="form-control">
                        <option value="Accepted">Accept</option>
                        <option value="Rejected">Reject</option>
                    </select>
                </div>
                <div class="col-md-6 mt-2">
                    <label for="note">Price</label>
                    <input type="number" class="form-control" name="price" step="0.01" placeholder="Enter Order Cost">
                </div>
                <div class="col-md-12 mt-2">
                    <label for="note">Enter Response</label>
                    <textarea class="form-control" required id="note" name="note" rows="3"></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Send Response</button>
        </form>
    </div>
    </div>
@endsection
