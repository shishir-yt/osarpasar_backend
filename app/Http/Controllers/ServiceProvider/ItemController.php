<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use Yajra\DataTables\DataTables;

class ItemController extends BaseController
{
    public function __construct()
    {
        $this->title = "Items";
        $this->resources = "service_providers.items.";
        parent::__construct();
        $this->route = "items.";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::where('service_provider_id', auth()->user()->id)->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('category_id', function ($data) {
                    if($data->category) {
                        return '<a target="_blank" href="'. route('categories.show', $data->category->id) .'">'. $data->category->name .'</a>';
                    } else {
                        return 'N/A';
                    }
                })
                ->addColumn('action', function ($data) {
                    return view('admin.templates.index_action2', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->filter(function ($query) {
                    $query->where('category_id', 'like', "%" . request('categoryFilter') . "%");
                }, true)
                ->rawColumns(['action', 'category_id'])
                ->make(true);
        }
        $info = $this->crudInfo();
        $info['categories'] = Category::where('service_provider_id', auth()->user()->id)->get();;

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
        $info['categories'] = Category::where('service_provider_id', auth()->user()->id)->get();
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
            'name' => 'required|string',
            'category_id' => 'required'
        ]);

        $item = new Item();
        $item->service_provider_id = auth()->user()->id;
        $item->name = $request->name;
        $item->category_id = $request->category_id;
        $item->save();

        return redirect()->route($this->indexRoute())->with('success', 'Item Added Successfully.');
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
        $info['item'] = Item::findOrFail($id);
        $info['categories'] = Category::where('service_provider_id', auth()->user()->id)->get();
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
        $info['item'] = Item::findOrFail($id);
        $info['categories'] = Category::where('service_provider_id', auth()->user()->id)->get();
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
            'name' => 'required|string',
            'category_id' => 'required'
        ]);

        $item = Item::findOrFail($id);
        $item->service_provider_id = auth()->user()->id;
        $item->name = $request->name;
        $item->category_id = $request->category_id;
        $item->update();

        return redirect()->route($this->indexRoute())->with('success', 'Item Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route($this->indexRoute())->with('delete', 'Item Deleted Successfully.');
    }
}