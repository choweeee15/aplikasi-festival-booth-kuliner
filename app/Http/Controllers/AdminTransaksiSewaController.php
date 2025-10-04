<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminTransaksiSewaController extends Controller
{
    public function index(Request $r)
    {
        $email  = trim((string) $r->query('email', ''));
        $booth  = trim((string) $r->query('booth', ''));
        $status = trim((string) $r->query('status', ''));

        // Deteksi relasi penyewa: pengunjung_id→pengunjungs ATAU penyewa_id→penyewas
        [$fkCol, $relTable] = $this->detectRenterRelation();

        // Base query
        $q = DB::table('transaksi_sewa as ts')
              ->leftJoin('booths as b', 'b.id', '=', 'ts.booth_id')
              ->select('ts.*', 'b.nama_booth');

        // Join ke tabel penyewa/pengunjung bila ada
        if ($fkCol && $relTable) {
            $q->leftJoin($relTable . ' as p', 'p.id', '=', 'ts.' . $fkCol);

            // Siapkan COALESCE utk nama & email (agar tidak strip)
            [$nameExpr, $emailExpr, $emailColForFilter] = $this->nameEmailExpressions($relTable);
            $q->addSelect(DB::raw($nameExpr . ' as penyewa_nama'));
            $q->addSelect(DB::raw($emailExpr . ' as penyewa_email'));

            // Filter email (pakai kolom email yang benar di tabel relasi)
            if ($email !== '' && $emailColForFilter) {
                $q->where("p.$emailColForFilter", $email);
            }
        } else {
            // Fallback jika tidak ada relasi yang dikenali
            $q->addSelect(DB::raw('NULL as penyewa_nama'), DB::raw('NULL as penyewa_email'));
        }

        // Filter booth & status
        if ($booth !== '') {
            $q->where('b.nama_booth', 'like', "%{$booth}%");
        }
        if ($status !== '') {
            $q->where('ts.status', $status);
        }

        $rows = $q->orderByDesc('ts.id')->paginate(10)->withQueryString();

        // Rekap status dinamis
        $valid = $this->getValidStatuses();
        $rekap = ['total' => (int) DB::table('transaksi_sewa')->count()];
        foreach ($valid as $st) {
            $rekap[$st] = (int) DB::table('transaksi_sewa')->where('status', $st)->count();
        }
        $filterStatuses = $this->sortedStatusesForFilter($valid);

        return view('admin_transaksi_sewa_index', compact('rows','rekap','email','booth','status','filterStatuses'));
    }

    public function approve($id)
    {
        $target = $this->pickAvailable(['lunas','disetujui','approved']);
        if (!$target) return back()->with('error','Tidak ada status APPROVE yang valid di DB.');
        $ok = DB::table('transaksi_sewa')->where('id',$id)->update(['status'=>$target]);
        return $ok ? back()->with('success',"Sewa disetujui ($target).") : back()->with('error','Data tidak ditemukan.');
    }

    public function reject($id)
    {
        $target = $this->pickAvailable(['pending','menunggu']);
        if (!$target) return back()->with('error','Tidak ada status PENDING yang valid di DB.');
        $ok = DB::table('transaksi_sewa')->where('id',$id)->update(['status'=>$target]);
        return $ok ? back()->with('success',"Status dikembalikan ke $target.") : back()->with('error','Data tidak ditemukan.');
    }

    /** Deteksi relasi penyewa: pengunjung_id→pengunjungs atau penyewa_id→penyewas */
    private function detectRenterRelation(): array
    {
        if (Schema::hasColumn('transaksi_sewa','pengunjung_id') && Schema::hasTable('pengunjungs')) {
            return ['pengunjung_id','pengunjungs'];
        }
        if (Schema::hasColumn('transaksi_sewa','penyewa_id') && Schema::hasTable('penyewas')) {
            return ['penyewa_id','penyewas'];
        }
        return [null,null];
    }

    /**
     * Bangun ekspresi COALESCE utk nama & email yang aman lintas skema.
     * Mengembalikan: [namaExpr, emailExpr, emailColForFilter]
     */
    private function nameEmailExpressions(string $table): array
    {
        // Kandidat kolom nama & email yang sering dipakai
        $nameCandidates  = ['nama','nama_lengkap','full_name','name','username','display_name'];
        $emailCandidates = ['email','alamat_email','mail'];

        $nameCols  = array_values(array_filter($nameCandidates, fn($c)=>Schema::hasColumn($table,$c)));
        $emailCols = array_values(array_filter($emailCandidates, fn($c)=>Schema::hasColumn($table,$c)));

        // Kalau tak ada sama sekali, pakai NULL
        $nameExpr  = $nameCols ? 'COALESCE('.implode(',', array_map(fn($c)=>"p.$c", $nameCols)).')' : 'NULL';
        $emailExpr = $emailCols ? 'COALESCE('.implode(',', array_map(fn($c)=>"p.$c", $emailCols)).')' : 'NULL';

        // Kolom email utama untuk filter (ambil kandidat pertama yang ada)
        $emailColForFilter = $emailCols[0] ?? null;

        return [$nameExpr, $emailExpr, $emailColForFilter];
    }

    /** Ambil status valid dari ENUM atau distinct values */
    private function getValidStatuses(): array
    {
        try {
            $db = DB::getDatabaseName();
            $row = DB::selectOne("
                SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'transaksi_sewa' AND COLUMN_NAME = 'status'
            ", [$db]);

            if ($row && isset($row->COLUMN_TYPE) && stripos($row->COLUMN_TYPE, 'enum(') === 0) {
                $list = trim($row->COLUMN_TYPE, "enum()'");
                $parts = array_map(fn($s)=>trim($s," '\""), explode(',', $list));
                $parts = array_values(array_filter($parts, fn($s)=>$s!==''));
                if ($parts) return $parts;
            }
        } catch (\Throwable $e) { /* ignore */ }

        $vals = DB::table('transaksi_sewa')->select('status')->distinct()->pluck('status')->toArray();
        $vals = array_values(array_filter(array_map('strval',$vals)));
        return $vals ?: ['pending'];
    }

    /** Pilih kandidat status pertama yang tersedia di DB */
    private function pickAvailable(array $candidates): ?string
    {
        $valid = $this->getValidStatuses();
        foreach ($candidates as $c) if (in_array($c,$valid,true)) return $c;
        return null;
    }

    /** Urutan enak untuk dropdown filter */
    private function sortedStatusesForFilter(array $valid): array
    {
        $prio = ['pending','menunggu','lunas','disetujui','approved','ditolak','rejected'];
        $inP  = array_values(array_intersect($prio,$valid));
        $rest = array_values(array_diff($valid,$inP));
        return array_merge($inP,$rest);
    }
}
