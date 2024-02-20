<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Dashboard\{ProfileSettingRequest};
use App\Models\User;
use Auth;
use  App\Imports\{
    CommunityImport,
    ArticleImport
};
use Excel;

class ProfileSettingController extends Controller
{
    public function get()
    {
        $user = Auth()->user();
        return view('dashboard.profileSettings.index', compact('user'));
    }
    public function update(ProfileSettingRequest $request)
    {
        $user = Auth()->user();
        $user->name = $request->name;
        $user->password = Hash::make($request->new_password);
        $user->save();
        if ($request->hasFile('image')) {
            $user->clearMediaCollection('images');
            $user->addMediaFromRequest('image')->toMediaCollection('images');
        }
        // if($request->hasFile('communityFile')){
        //     $file = $request->communityFile;
        //     Excel::import(new CommunityImport,$file);
        // }
        // if($request->hasFile('blogFile')){
        //     $file = $request->blogFile;
        //     Excel::import(new ArticleImport,$file);
        // }
        return redirect()->route('dashboard.profileSettings')->with('success','Profile has been updated successfully.');
    }
}
