<div class="form-group row">
    <div class="col-md-6">
        <label>Name <span class="text-danger">*</label>
        <input type="text" placeholder="Enter Name" class="form-control" name="name" required
            value="{{ old('name', $item->name) }}">
    </div>
    <div class="col-md-6">
        <label>Category <span class="text-danger">*</label>
        <select name="category_id" class="form-control" required>
            <option>Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
            @endforeach
        </select>
    </div>
</div>
