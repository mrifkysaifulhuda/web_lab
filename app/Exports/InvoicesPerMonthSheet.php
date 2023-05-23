<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class InvoicesPerMonthSheet implements FromCollection, WithTitle, WithHeadings
{
    private $keb;
    private $year;

    public function __construct(int $year, string $keb)
    {
        $this->keb = $keb;
        $this->year  = $year;
    }

    /**
     * @return Builder
     */
    public function collection()
    {
        if($this->keb == "Alat"){
            $data = DB::select("SELECT ROW_NUMBER() OVER() AS num_row, nm_alat, merek, profil, jumlah_ajuan FROM `keb_alat_lab` where tahun = ? and id_laboratorium = ? and deleted_at is null",[$this->year, Session::get('selected-lab')]);
        }else{
            $data = DB::select("SELECT ROW_NUMBER() OVER() AS num_row, nm_bahan, satuan, deskripsi, jumlah_ajuan FROM `keb_bahan_lab` where tahun = ? and id_laboratorium = ? and deleted_at is null",[$this->year, Session::get('selected-lab')]);
        }
       
        return collect($data);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->keb;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings() :array
    {
        if($this->keb == "Alat"){
            return [
                'Nomor',
                'Nama Alat',
                'Merek',
                'Profil',
                'Kebutuhan'
            ];
        }else{
            return [
                'Nomor',
                'Nama Bahan',
                'Satuan',
                'Deskripsi',
                'Kebutuhan'
            ];
        }
    }
}