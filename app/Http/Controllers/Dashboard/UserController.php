<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\UserRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Auth;
use DB;
class UserController extends Controller
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
    public function index(Request $request)
    {
        $users = User::whereNot('id', auth()->user()->id)
                ->applyFilters($request->only(['status']))
                ->orderBy('id','desc')
                ->get();

        return view('dashboard.userManagement.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();

        return view('dashboard.userManagement.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try{
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            $user->status = $request->status;

            $user->password = Hash::make($request->password);
            if ($request->hasFile('image')) {
                $user->addMediaFromRequest('image')->toMediaCollection('images');
            }
            $user->assignRole($request->input('roles'));
            $user->save();

            return response()->json([
                'success' => true,
                'message'=> 'User has been created successfully..',
                'redirect' => route('dashboard.users.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.users.index'),
            ]);
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
    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        return view('dashboard.userManagement.users.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        try{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            $user->status = $request->status;
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            if ($request->hasFile('image')) {
                $user->clearMediaCollection('images');
                $user->addMediaFromRequest('image')->toMediaCollection('images');
            }
            DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            $user->assignRole($request->input('roles'));
            $user->save();
            return response()->json([
                'success' => true,
                'message'=> 'User has been updated successfully..',
                'redirect' => route('dashboard.users.index'),
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'success' => false,
                'message'=> $error->getMessage(),
                'redirect' => route('dashboard.users.index'),
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            User::find($id)->delete();

            return redirect()->route('dashboard.users.index')->with('success','User has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.users.index')->with('error',$error->getMessage());
        }

    }
}
