<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Support\Facades\Hash;

class EmploymentController extends Controller
{
    public function index()
    {
        $employments = Employment::with('office')->latest()->when(request()->q, function($employments) {
            $employments = $employments->where('name', 'like', '%'. request()->q . '%');
        })->paginate(10);

        return view('admin.employment.index', compact('employments'));
    }

    public function create()
    {
        $offices = Office::latest()->get();
        return view('admin.employment.create', compact('offices'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username'  => 'required|unique:employments',
            'jabatan'  => 'required',
            'office_id'  => 'required',
            'name'  => 'required',
            'password'  => 'required'
        ]); 

        // dd($request->all());

        //save to DB
        $employment = Employment::create([
            'office_id' => $request->office_id,
            'name' => $request->name,
            'jabatan' => $request->jabatan,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        if($employment){
            //redirect dengan pesan sukses
            return redirect()->route('admin.employment.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.employment.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }
    

    public function edit(Employment $employment)
    {

        $offices = Office::latest()->get();
        return view('admin.employment.edit', compact('employment', 'offices'));
    }

    public function update(Request $request, Employment $employment)
    {
        $this->validate($request, [
            'name'  => 'required|unique:employments,name,'.$employment->id 
        ]); 

       //update data tanpa image
        $employment = Employment::findOrFail($employment->id);
        $employment->update([
            'office_id' => $request->office_id,
            'name' => $request->name,
            'jabatan' => $request->jabatan,
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        if($employment){
            //redirect dengan pesan sukses
            return redirect()->route('admin.employment.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.employment.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }
    
  
    public function destroy($id)
    {
        $employment = Employment::findOrFail($id);
        $employment->delete();

        if($employment){
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
