<?php

namespace App\Support;

class QrSigner
{
    /**
     * CORE payload yang ditandatangani:
     * "TCK|{kode}|UID:{pengunjung_id}|TID:{pembelian_tiket_id}"
     */
    public static function buildCore(string $kode, int $uid, int $tid): string
    {
        return "TCK|{$kode}|UID:{$uid}|TID:{$tid}";
    }

    /**
     * Hasilkan signature HMAC-SHA256 (hex lowercase)
     */
    public static function sign(string $core, ?string $secret = null): string
    {
        $secret = $secret ?? config('app.key');
        return hash_hmac('sha256', $core, $secret);
    }

    /**
     * Gabungkan payload final: "CORE|SIG:{hex}"
     */
    public static function buildPayload(string $core, string $sig): string
    {
        return "{$core}|SIG:{$sig}";
    }

    /**
     * Normalisasi input paste (trim + buang zero-width chars)
     */
    public static function normalize(string $s): string
    {
        $s = preg_replace('/\p{Cf}/u', '', $s ?? '');
        return trim($s);
    }
}
