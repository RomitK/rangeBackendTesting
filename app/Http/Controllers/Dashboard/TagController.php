<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\TagRequest;
use App\Models\{
    TagCategory
};
use Auth;
class TagController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:'.config('constants.Permissions.tags'),
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
        $tags = TagCategory::with('user')
                        ->orderBy('id','desc')
                        ->paginate(10);
        return view('dashboard.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        try{
            $tag = new TagCategory;
            $tag->name = $request->name;
            $tag->status = $request->status;
            $tag->type = $request->type;
            $tag->user_id = Auth::user()->id;
            $tag->save();
            return redirect()->route('dashboard.tags.index')->with('success','Tag has been created successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.tags.index')->with('error',$error->getMessage());
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
    public function edit(TagCategory $tag)
    {
        return view('dashboard.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, TagCategory $tag)
    {
        try{
            $tag->name = $request->name;
            $tag->status = $request->status;
            $tag->type = $request->type;
            $tag->save();
            return redirect()->route('dashboard.tags.index')->with('success','Tag has been updated successfully.');
        }catch(\Exception $error){
            return redirect()->route('dashboard.tags.index')->with('error',$error->getMessage());
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
            TagCategory::find($id)->delete();
            return redirect()->route('dashboard.tags.index')->with('success','Tag has been deleted successfully');
        }catch(\Exception $error){
            return redirect()->route('dashboard.tags.index')->with('error',$error->getMessage());
        }
    }
}
