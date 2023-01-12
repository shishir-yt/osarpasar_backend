@if (!isset($hideShow))
    <div class="d-flex">
        <a href="{{ route($route . 'show', $id ?? $item->id) }}"
            class="btn btn-sm btn-clean btn-icon btn-hover-primary"><i class="fa fa-eye"
                style="margin: 0 0.1rem!important;"></i></a>
@endif
@if (!isset($hideEdit))
    <a href="{{ route($route . 'edit', $id ?? $item->id) }}" class="btn btn-sm btn-clean btn-icon btn-hover-info"><i
            class="fa fa-pencil-alt" style="margin: 0 0.1rem!important;"></i></a>
@endif


<form class="d-inline" action="{{ route($route . 'destroy', $id ?? $item->id) }}" method="POST"
    onclick="return confirm('Are you sure?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-clean btn-icon btn-hover-danger"><i class="fa fa-trash"
            style="margin: 0 0.1rem!important;"></i></button>
</form>

@foreach ($actions ?? [] as $action)
    {!! $action !!}
@endforeach
