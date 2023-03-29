<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Office;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'long' => 'required',
            'lat' => 'required',
            'address' => 'required',
            'type' => 'required',
            'photo' => 'required|image|mimes:jpeg,jpg,png',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
         //upload image
        $image = $request->file('photo');
        $image->storeAs('public/absensi', $image->hashName());

        $attendanceType = $request->type;
        $userAttendanceToday = $request->user()
            ->attendances()
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($attendanceType == 'in') {
            if (! $userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(
                        [
                            'status' => false,
                            'office_id' => $request->office_id
                        ]
                    );

                $current_date = Carbon::now("Asia/Jakarta");
                $date = $current_date->format('Y-m-d');
                $time = $current_date->format('H:i');
                $start = '06:02';
                $pulawal = '13:00';
                $end = '15:00';

                $office_id = auth()->user()->office_id;
                $office = Office::where('id', $office_id)->first();
                // dd($office->name);
                if ($office->name == "Kantor Pusat BMT NU Ngasem"){
                    $end = '15:30';
                }

                if($time > $start){
                    $attendance->detail()->create(
                        [
                            'type' => 'in',
                            'point' => 0,
                            'tanggal' => $date,
                            'pukul' => $time,
                            'keterangan' => "Telat",
                            'long' => $request->long,
                            'lat' => $request->lat,
                            'distance' => $request->distance,
                            'photo' => $image->hashName(),
                            'address' => $request->address
                        ]
                    );
                    return response()->json(
                        [
                            'message' => 'Absensi Check in berhasil di kirim!',
                            'data' => $attendance,
                        ],
                        Response::HTTP_CREATED
                    );
                } else {
                    $attendance->detail()->create(
                        [
                            'type' => 'in',
                            'point' => 1,
                            'tanggal' => $date,
                            'pukul' => $time,
                            'keterangan' => "Datang",
                            'long' => $request->long,
                            'lat' => $request->lat,
                            'distance' => $request->distance,
                            'photo' => $image->hashName(),
                            'address' => $request->address
                        ]
                    );
                    return response()->json(
                        [
                            'message' => 'Absensi Check in berhasil di kirim!',
                            'data' => $attendance,
                        ],
                        Response::HTTP_CREATED
                    );
                }
            }

            // else show user has been checked in
            return response()->json(
                [
                    'message' => 'Santri sudah Check in hari ini!',
                ],
                Response::HTTP_OK
            );
        }

        if ($attendanceType == 'out') {
            if ($userAttendanceToday) {
                if ($userAttendanceToday->status) {
                    return response()->json(
                        [
                            'message' => 'User has been checked out',
                        ],
                        Response::HTTP_OK
                    );
                }

                $userAttendanceToday->update(
                    [
                        'status' => true
                    ]
                );

                $current_date = Carbon::now("Asia/Jakarta");
                $date = $current_date->format('Y-m-d');
                $time = $current_date->format('H:i');
                $start = '13:02';
                $pulawal = '13:00';
                $end = '15:00';

                $office_id = auth()->user()->office_id;
                $office = Office::where('id', $office_id)->first();
                if ($office->name == "Kantor Pusat BMT NU Ngasem"){
                    $end = '15:30';
                }

                if($time > $pulawal && $time < $end){
                    $userAttendanceToday->detail()->create(
                        [
                            'type' => 'out',
                            'point' => 0,
                            'tanggal' => $date,
                            'pukul' => $time,
                            'keterangan' => "Pulang Awal",
                            'long' => $request->long,
                            'lat' => $request->lat,
                            'distance' => $request->distance,
                            'photo' => $image->hashName(),
                            'address' => $request->address
                        ]
                    );

                    return response()->json(
                        [
                            'message' => 'Absensi Check out berhasil di kirim!',
                            'data' => $userAttendanceToday,
                        ],
                        Response::HTTP_CREATED
                    );
                } else if($time < $pulawal){
                    $userAttendanceToday->detail()->create(
                        [
                            'type' => 'out',
                            'point' => 0,
                            'tanggal' => $date,
                            'pukul' => $time,
                            'keterangan' => "Pulang Awal",
                            'long' => $request->long,
                            'lat' => $request->lat,
                            'distance' => $request->distance,
                            'photo' => $image->hashName(),
                            'address' => $request->address
                        ]
                    );

                    return response()->json(
                        [
                            'message' => 'Absensi Check out berhasil di kirim!',
                            'data' => $userAttendanceToday,
                        ],
                        Response::HTTP_CREATED
                    );
                } else {
                    $userAttendanceToday->detail()->create(
                        [
                            'type' => 'out',
                            'point' => 1,
                            'tanggal' => $date,
                            'pukul' => $time,
                            'keterangan' => "Pulang",
                            'long' => $request->long,
                            'lat' => $request->lat,
                            'distance' => $request->distance,
                            'photo' => $image->hashName(),
                            'address' => $request->address
                        ]
                    );

                    return response()->json(
                        [
                            'message' => 'Absensi Check out berhasil di kirim!',
                            'data' => $userAttendanceToday,
                        ],
                        Response::HTTP_CREATED
                    );
                }
            }

            return response()->json(
                [
                    'message' => 'Santri di mohon untuk Check in terlebih dahulu',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    public function history(Request $request)
    {
        $request->validate(
            [
                'from' => ['required'],
                'to' => ['required'],
            ]
        );

        $history = $request->user()->attendances()->with('detail')
            ->whereBetween(
                DB::raw('DATE(created_at)'),
                [
                    $request->from, $request->to
                ]
            )->get();


        // $history = Attendance::with('user','detail')
        //     ->whereBetween(
        //         DB::raw('DATE(created_at)'),
        //         [
        //             $request->from, $request->to
        //         ]
        //     )->get();

        return response()->json(
            [
                'message' => "list of presences by user",
                'data' => $history,
            ],
            Response::HTTP_OK
        );
    }
}
