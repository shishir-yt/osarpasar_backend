@if (!isset($hideShow))
    <td><a href="{{ route($route . 'show', $item->id) }}" class="btn btn-sm btn-clean btn-icon btn-hover-primary"><i
                class="fa fa-eye"></i></a>
@endif
@if (!isset($hideEdit))
    <a href="{{ route($route . 'edit', $item->id) }}" class="btn btn-sm btn-clean btn-icon btn-hover-info btn-edit"><i
            class="fa fa-pencil-alt"></i></a>
@endif

@if (!isset($hideDelete))
    <form class="d-inline" action="{{ route($route . 'destroy', $item->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn-delete btn btn-sm btn-clean btn-icon btn-hover-danger"
            onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></button>
    </form>
    </td>
@endif
