<?php

namespace App\Http\Controllers;

use App\Models\Permession;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request){
        $permissions = Permession::with('user')
        ->when($request->input('name'), function ($query, $name) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        })->orderBy('id', 'desc')->paginate(10);
    return view('pages.permission.index', compact('permissions'));
    }

    public function show($id)
    {
        $permission = Permession::with('user')->find($id);
        return view('pages.permission.show', compact('permission'));
    }

    //edit
    public function edit($id)
    {
        $permission = Permession::find($id);
        return view('pages.permission.edit', compact('permission'));
    }

    //update
    public function update(Request $request, $id)
    {
        $permission = Permession::find($id);
        $permission->is_approved = $request->is_approved;
        $permission->save();
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

}
