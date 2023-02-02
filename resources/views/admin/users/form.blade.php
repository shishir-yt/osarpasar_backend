<div class="form-group row">
    <div class="col-md-6">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" required class="form-control" placeholder="Enter Provider Name" name="name"
            value="{{ old('name', $item->name) }}">
    </div>

    <div class="col-md-6">
        <label>Email <span class="text-danger">*</span></label>
        <input type="email" required class="form-control" placeholder="Enter Email" name="email"
            value="{{ old('email', $item->email) }}">
    </div>

    <div class="col-6 my-2">
        <label for="">Password <span class="text-danger">*</span></label>
        <div style="position: relative">
            <input type="password" name="password" class="form-control pr-5" placeholder="Enter Password"
                autocomplete="current-password" @if ($routeType == 'Create') required @endif id="password">
            <span class="far fa-eye-slash" id="togglePassword"
                style="position: absolute; top: 13px; right: 13px; cursor: pointer;"></span>
        </div>
        @if ($routeType == 'Edit')
            <span class="text-muted">Leave blank if you dont't want to update password.</span>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        jQuery(document).ready(function() {
            $('#togglePassword').click(function(e) {
                const type = $('#password').attr('type') === 'password' ? 'text' : 'password';
                $('#password').attr('type', type);
                if ($(this).hasClass('fa-eye-slash')) {
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                }
            })
        });
    </script>
@endpush
