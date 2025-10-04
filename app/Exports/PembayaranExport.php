<?php

namespace App\Exports;  // Pastikan namespace ini sesuai dengan lokasi file di app/Exports/

use Maatwebsite\Excel\Concerns\FromCollection;

class PembayaranExport implements FromCollection
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        // Mengembalikan koleksi data pembayaran yang diterima
        return $this->payments;
    }
}
