<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use Yajra\DataTables\DataTables;

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->title = "Categories";
        $this->resources = "service_providers.categories.";
        parent::__construct();
        $this->route = "categories.";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::where('service_provider_id', auth()->user()->id)->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('admin.templates.index_action2', [
                        'id' => $data->id, 'route' => $this->route, 'isCategory' => true
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $info = $this->crudInfo();

        return view($this->indexResource(), $info);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info = $this->crudInfo();

        return view($this->createResource(), $info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = new Category();
        $category->service_provider_id = auth()->user()->id;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route($this->indexRoute())->with('success', 'Category Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info =  $this->crudInfo();
        $info['item'] = Category::findOrFail($id);

        return view($this->showResource(), $info);
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info =  $this->crudInfo();
        $info['item'] = Category::findOrFail($id);

        return view($this->editResource(), $info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::findOrFail($id);
        $category->service_provider_id = auth()->user()->id;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->update();

        return redirect()->route($this->indexRoute())->with('success', 'Category Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        Item::where('category_id', $category->id)->delete();
        $category->delete();

        return redirect()->route($this->indexRoute())->with('delete', 'Category Deleted Successfully.');
    }
}
