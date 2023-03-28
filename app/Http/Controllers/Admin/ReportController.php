<?php

namespace App\Http\Controllers\Admin;

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
            ->get();

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

        //get data donation by range date
        $donations = Donation::with('donatur', 'category', 'program')->where('status', 'success')->whereDate('created_at', '>=', $request->date_from)->whereDate('created_at', '<=', $request->date_to)->get();

        //get total donation by range date    
        $total = Donation::where('status', 'success')->whereDate('created_at', '>=', $request->date_from)->whereDate('created_at', '<=', $request->date_to)->sum('amount');

        return (new DonationExport)->forRange($date_from, $date_to)->download('invoices.xlsx');
    }
}
