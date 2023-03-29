<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceExport implements FromQuery, WithMapping, WithHeadings
{
    
    use Exportable;

    public function forRange(String $start, String $end)
    {
        $this->start = $start;
        $this->end = $end;

        return $this;
    }

    public function query()
    {
        return Attendance::query()->with('employment', 'detail', 'office')->whereBetween('created_at', [$this->start, $this->end]);
    }

    /**
     * @var Transaction $transaction
     */
    public function map($attendance): array
    {
        $i = 1;
        return [
            $i++,
            $attendance->employment->name,
            $attendance->employment->jabatan,
            $attendance->office->name,
            Carbon::parse($attendance->created_at)->format('d-m-Y'),
            "Absensi Harian",
            "06:02",
            "15:00",
            $attendance->detail[0]->pukul,
            $attendance->detail[0]->keterangan,
            $attendance->detail[0]->lat.",".$attendance->detail[0]->long,
            $attendance->detail[0]->address,
            $attendance->detail[0]->distance,
            $attendance->detail[1]->pukul,
            $attendance->detail[1]->keterangan,
            $attendance->detail[1]->lat.",".$attendance->detail[1]->long,
            $attendance->detail[1]->address,
            $attendance->detail[1]->distance,
            $attendance->detail[0]->point,
            $attendance->detail[1]->point,
            $attendance->detail[0]->point + $attendance->detail[1]->point,

        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Santri',
            'Jabatan',
            'Kantor',
            'Tanggal',
            'Shift Name',
            'Shift In',
            'Shift Out',
            'Actual In',
            'Note In',
            'GeoLocation In',
            'Address In',
            'Distance In',
            'Actual Out',
            'Note Out',
            'GeoLocation Out',
            'Address Out',
            'Distance Out',
            'Point In',
            'Point Out',
            'Total Point',
        ];
    }
}
