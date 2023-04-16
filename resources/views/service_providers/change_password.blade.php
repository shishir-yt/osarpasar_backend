@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Change Password</h1>
@stop

@section('content')
    <section class="content ">
        <div class="container-fluid ">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12 col-offset-6 centered">
                    <!-- general form elements -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form class="form repeater" id="form" action="{{ route('password-change.store') }}" method="post"
                            enctype="multipart/form-data">
                            <div class="card-body">
                                @csrf
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $error }}</strong>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @endif

                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="">Old Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" required name="old_password"
                                            placeholder="Enter current password" value="{{ old('old_password') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">New Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" required name="new_password"
                                            placeholder="Enter new password" value="{{ old('new_password') }}">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" required name="confirm_password"
                                            placeholder="Confirm password" value="{{ old('confirm_password') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-default float-right">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@stop

@section('css')
    {{--    <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
