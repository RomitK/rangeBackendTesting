<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.user_management'),
        ['only' => ['index','create', 'edit', 'update', 'destroy']
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::latest()->get();
        return view('dashboard.userManagement.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();

        return view('dashboard.userManagement.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = new Role;
            $role->name = $request->name;
            $role->syncPermissions($request->input('permissions'));
            $role->save();
            
            return redirect()->route('dashboard.roles.index')->with('success', 'Role has been created successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.roles.index')->with('error', $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get();
       
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$role->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('dashboard.userManagement.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        try {
            $role->name = $request->name;
            $role->save();
            $role->syncPermissions($request->input('permissions'));
            return redirect()->route('dashboard.roles.index')->with('success', 'Role has been updated successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.roles.index')->with('error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('dashboard.roles.index')->with('success', 'Role has been deleted successfully.');
        } catch (\Exception $error) {
            return redirect()->route('dashboard.roles.index')->with('error', $error->getMessage());
        }
    }
}
