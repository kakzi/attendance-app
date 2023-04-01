<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index()
    {
        return view('admin.report.index');
    }

    public function filter(Request $request)
    {
        $this->validate($request, [
            'date_from'  => 'required',
            'date_to'    => 'required',
        ]);

        $date_from  = $request->date_from;
        $date_to    = $request->date_to;

        //get data donation by range date
        $attendances = Attendance::with('employment','detail', 'office')
            ->whereDate('created_at', '>=', $request->date_from)
            ->whereDate('created_at', '<=', $request->date_to)
            ->paginate(10);

        // $offices = Office::latest()->when(request()->q, function($offices) {
        //     $offices = $offices->where('name', 'like', '%'. request()->q . '%');
        // })->paginate(10);

        // dd($attendances);

        return view('admin.report.index', compact('attendances'));
    }

    public function download(Request $request)
    {
        $this->validate($request, [
            'date_from'  => 'required',
            'date_to'    => 'required',
        ]);

        $date_from  = $request->date_from;
        $date_to    = $request->date_to;

        return (new AttendanceExport)->forRange($date_from, $date_to)->download('report-attendance.xlsx');
    }
}
