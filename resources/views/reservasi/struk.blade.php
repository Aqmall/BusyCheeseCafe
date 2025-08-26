<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Reservasi {{ $reservasi->kodeReservasi }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; color: #000; }
        .container { width: 300px; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 2px 0; font-size: 12px; }
        .item { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 12px; }
        .total { display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #000; font-weight: bold; font-size: 14px; }
        hr { border: none; border-top: 1px dashed #000; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
        .print-button { display: block; width: 100%; padding: 10px; background: #333; color: #fff; text-align: center; text-decoration: none; margin-bottom: 20px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <button onclick="window.print()" class="print-button no-print">Cetak Struk</button>

        <div class="header">
            <h1>Busy Cheese Cafe</h1>
            <p>Jl. Iskandarsyah II No.Raya Lantai LG #W18</p>
            <p>Jakarta Selatan</p>
        </div>

        <div>Kode Reservasi: {{ $reservasi->kodeReservasi }}</div>
        <div>Tanggal: {{ \Carbon\Carbon::parse($reservasi->created_at)->format('d/m/Y H:i') }}</div>
        <div>Pelanggan: {{ $reservasi->namaPelanggan }}</div>
        
        <hr>

        @foreach($reservasi->pesanan->detailPesanan as $detail)
            <div class="item">
                <span>{{ $detail->menu->namaMenu }} x{{ $detail->jumlah }}</span>
                <span>Rp{{ number_format($detail->subTotal) }}</span>
            </div>
        @endforeach

        <hr>

        <div class="item">
            <span>Subtotal</span>
            <span>Rp{{ number_format($reservasi->pesanan->totalTagihan) }}</span>
        </div>
        <div class="total">
            <span>TOTAL</span>
            <span>Rp{{ number_format($reservasi->pesanan->totalTagihan) }}</span>
        </div>
        <div class="item">
            <span>DP Dibayar (50%)</span>
            <span>Rp{{ number_format($reservasi->pesanan->totalDP) }}</span>
        </div>
        <div class="item">
            <span>Sisa Bayar</span>
            <span>Rp{{ number_format($reservasi->pesanan->sisaTagihan) }}</span>
        </div>
        
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>Harap tunjukkan struk ini saat checkout.</p>
        </div>
    </div>
</body>
</html>