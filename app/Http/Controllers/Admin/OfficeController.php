<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{

    public function index()
    {
        $offices = Office::latest()->when(request()->q, function($offices) {
            $offices = $offices->where('name', 'like', '%'. request()->q . '%');
        })->paginate(10);

        return view('admin.office.index', compact('offices'));
    }

    public function create()
    {
        return view('admin.office.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|unique:offices',
            'address'  => 'required',
            'lat'  => 'required',
            'long'  => 'required'
        ]); 

        //save to DB
        $office = office::create([
            'name'   => $request->name,
            'address'   => $request->address,
            'lat'   => $request->lat,
            'long'   => $request->long
        ]);

        if($office){
            //redirect dengan pesan sukses
            return redirect()->route('admin.office.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.office.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }
    

    public function edit(Office $office)
    {
        return view('admin.office.edit', compact('office'));
    }



    public function update(Request $request, office $office)
    {
        $this->validate($request, [
            'name'  => 'required|unique:offices,name,'.$office->id 
        ]); 

       //update data tanpa image
        $office = office::findOrFail($office->id);
        $office->update([
            'name'   => $request->name,
            'address'   => $request->address,
            'lat'   => $request->lat,
            'long'   => $request->long
        ]);

        if($office){
            //redirect dengan pesan sukses
            return redirect()->route('admin.office.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.office.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }
    
  
    public function destroy($id)
    {
        $office = Office::findOrFail($id);
        $office->delete();

        if($office){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
