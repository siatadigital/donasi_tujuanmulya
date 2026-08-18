<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $delivery->code }}_Kitir_Pengiriman.pdf</title>
    <style>
        @page {
            margin: 10px;
        }

        h1, p {
            font-family: Helvetica;
            font-size: 12px;
            margin: 0px;
        }

        header {
            width: 50%;
            margin: auto;
        }

        h1 {
            text-align: center;
        }

        .section-title {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .section-title p {
            text-align: center;
            margin: 2px 0px;
        }

        .section-content {
            padding: 4px;
            padding-bottom: 12px;
        }

        .watermark {
            font-size: 74px;
            font-weight: bold;
            opacity: 0.4;
            color: #aaa;
            position: absolute;
            top: 240px;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        footer {
            margin-top: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <h1>EKSPEDISI</h1>
        <h1>{{ strtoupper($delivery->courier_info) }} {{ strtoupper($delivery->courier_service_info) }}</h1>
    </header>
    <br>
    <p style="margin-bottom: 5px;">Kode Pemesanan: {{ $delivery->sales->code }}</p>
    <div class="section-title">
        <p>PENGIRIM</p>
    </div>
    @if ($delivery->origin_fullname == $delivery->destination_fullname)
    <div class="section-content">
        <p>Rumah Tas Lucu</p>
    </div>
    @else
    <div class="section-content">
        <p>{{ $delivery->origin_fullname }}</p>
        <p>{{ $delivery->origin_phone }}</p>
    </div>
    @endif
    <div class="section-title">
        <p>PENERIMA</p>
    </div>
    <div class="section-content">
        <p>{{ $delivery->destination_fullname }}</p>
        <p>{{ $delivery->destination_address }}</p>
        <p>{{ $delivery->destinationCity->province->name }}, {{ $delivery->destinationCity->name }}</p>
        <p>{{ $delivery->destination_postcode }}</p>
        <br>
        <p>{{ $delivery->destination_phone }}</p>
    </div>
    <div class="section-title">
        <p>DETAIL BARANG</p>
    </div>
    <div class="section-content">
        <p>
            @foreach($items as $index => $item)
                @foreach($item->colors as $color)
                    {{ $item->product_name }} - {{ $color->name }} ( x{{ $color->quantity }} )
                    @if ($index < $items->count() - 1)
                    ,
                    @endif
                @endforeach
            @endforeach
        </p>
    </div>
    <div class="section-title">
        <p style="text-align: left;">TOTAL HARGA: Rp. {{ number_format($delivery->sales->amount_transfer) }}</p>
        <p style="text-align: left;">TOTAL BERAT: {{ $delivery->total_weight / 1000 }} KG</p>
        <p style="text-align: left;">TOTAL ITEM: {{ number_format($totalQuantity) }}</p>
    </div>
    @if ($isCopy)
    <p class="watermark">SALINAN</p>
    @endif
    <footer>
        <p>&lt;&lt; TERIMA KASIH &gt;&gt;</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
    </footer>
</body>
</html>
