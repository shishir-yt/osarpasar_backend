@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
    <h1>Notifications</h1>
@stop

@section('css')

@stop

@section('content')
    @foreach($notifications as $notification)
    <div class="row">
    <a target="_blank" style="color: black;cursor: pointer;" href="{{route('order.details', $notification->data['order_id'])}}">
        Order Id : {{$notification->data['order_id']}}<br>
        Read At: {{$notification->read_at ?: "Yet to be read"}}
    </a>
    </div>
    <hr>
    @endforeach
@stop

@section('js')
@stop
