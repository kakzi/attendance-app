<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $employment = Employment::count();
        return view('admin.dashboard.index', compact('employment'));
    }
}
