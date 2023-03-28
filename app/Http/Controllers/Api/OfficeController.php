<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Support\Facades\Validator;

class OfficeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'name' => 'required',
            'address' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $office = Office::create([
            'lat' => $request->lat,
            'long' => $request->long,
            'name' => $request->name,
            'address' => $request->address
        ]);

        return response()->json([
            'message' => 'Success Added!',
            'data' => $office
        ], 201);
        
    }
}
