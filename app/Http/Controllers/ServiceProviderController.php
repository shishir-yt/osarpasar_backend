<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
class ServiceProviderController extends BaseController
{
    public function __construct()
    {
        $this->title = "Service Providers";
        $this->resources = "admin.users.";
        parent::__construct();
        $this->route = "service-providers.";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('is_admin', "0")->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return view('admin.templates.index_action2', [
                        'id' => $data->id, 'route' => $this->route
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $info = $this->crudInfo();
        $info['serviceProviders'] = User::where("is_admin","0")->get();
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
        $info['routeType'] = "Create";

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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $data = $request->all();
        $data['is_admin'] = "0";
        $data['password'] = Crypt::encryptString($data['password']);
        $serviceProvider = new User($data);
        $serviceProvider->save();

        return redirect()->route($this->indexroute())->with('success', 'Service Provider added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = $this->crudInfo();
        $info['item'] = User::findOrFail($id);

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
        $info = $this->crudInfo();
        $info['item'] = User::findOrFail($id);
        $info['routeType'] = "Edit";

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
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $serviceProvider = User::findOrFail($id);
        $data = $request->all();
        $data['password'] = $data['password'] ? Crypt::encryptString($data['password']) : $serviceProvider->password;
        $serviceProvider->update($data);

        return redirect()->route($this->indexroute())->with('success', 'Service Provider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serviceProvider = User::findOrFail($id);
        $serviceProvider->delete();

        return redirect()->route($this->indexRoute())->with('delete', 'Service Provider Deleted Successfully.');
    }
}