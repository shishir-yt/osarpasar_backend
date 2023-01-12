<div class="form-group row">
    <div class="col-md-6">
        <label>Name <span class="text-danger">*</label>
        <input type="text" placeholder="Enter Name" class="form-control" name="name" required
            value="{{ old('name', $item->name) }}">
    </div>
</div>

<div class="row my-3">
    <div class="col-md-12">
        <label>Description <span class="text-primary"></label>
        <textarea id="summernote" class="form-control" name=description>{!! old('description', $item->description) !!}</textarea>
    </div>
</div>
