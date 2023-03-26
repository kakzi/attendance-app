<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

        // is presence type equal with 'in' ?
        if ($attendanceType == 'in') {
            // is $userPresenceToday not found?
            if (! $userAttendanceToday) {
                $attendance = $request
                    ->user()
                    ->attendances()
                    ->create(
                        [
                            'status' => false
                        ]
                    );

                $attendance->detail()->create(
                    [
                        'type' => 'in',
                        'long' => $request->long,
                        'lat' => $request->lat,
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

                $userAttendanceToday->detail()->create(
                    [
                        'type' => 'out',
                        'long' => $request->long,
                        'lat' => $request->lat,
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

        return response()->json(
            [
                'message' => "list of presences by user",
                'data' => $history,
            ],
            Response::HTTP_OK
        );
    }
}
