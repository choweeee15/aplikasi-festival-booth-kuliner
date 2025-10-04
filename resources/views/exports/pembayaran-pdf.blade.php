<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pembayaran</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h2>Riwayat Pembayaran</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal Upload</th>
                <th>Layanan</th>
                <th>Nama Kendaraan</th>
                <th>Model Kendaraan</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->tanggal_upload)->format('d M Y H:i') }}</td>
                    <td>{{ $payment->registrasi->layanan->nama_layanan }}</td>
                    <td>{{ $payment->registrasi->nama_kendaraan }}</td>
                    <td>{{ $payment->registrasi->model_kendaraan }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
