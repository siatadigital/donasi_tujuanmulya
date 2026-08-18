<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $return->code }}_Nota_Pengembalian.pdf</title>
    <style>
        @page {
            margin: 10px;
        }

        h1, p {
            font-family: Helvetica;
            font-size: 12px;
            margin-top: 0px;
            margin-bottom: 4px;
        }

        header {
            margin-bottom: -10px;
        }

        h1 {
            font-weight: normal;
            text-align: center;
        }

        .item {
            width: 100%;
            display: flex;
            margin-bottom: -20px;
        }

        .item__content {
            margin-left: 16px;
        }

        .item__name {
            width: 65%;
        }

        .item__amount {
            display: flex;
            margin-bottom: -18px;
        }

        .item__quantity {
            /* line-height: 1.1em; */
        }

        .item__total {
            /* line-height: 1.1em; */
            text-align: right;
        }

        .total {
            width: 160px;
            text-align: right;
            margin-left: auto;
            margin-bottom: 10px;
        }

        .watermark {
            font-size: 96px;
            font-weight: bold;
            opacity: 0.4;
            color: #aaa;
            position: absolute;
            top: 240px;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        footer {
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <h1>Rumah Tas Lucu</h1>
        <hr>
        <p>Kode Pemesanan: {{ $return->code }}</p>
        <p>Tanggal Pemesanan: {{ Carbon\Carbon::parse($date)->format('d-m-Y') }}</p>
        <p>Customer: {{ $return->customer ? strtoupper($return->customer->user->fullname) : 'GUEST' }}</p>
        <p>Kasir: {{ strtoupper(auth()->user()->fullname) }}</p>
    </header>
    <br>
    @foreach($items as $index => $item)
    <div class="item">
        <p class="item__index">{{ $index + 1 }}.</p>
        <div class="item__content">
            <p class="item__name">{{ strtoupper($item->product_name) }} ({{ $item->type }})</p>
            @foreach($item->colors as $color)
            <div class="item__amount">
                <p class="item__quantity">({{ $color->name }}) {{ $color->quantity }} &times; {{ number_format($item->price_used) }}</p>
                <p class="item__total">{{ number_format($color->subtotal) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    <div class="total">
        <hr>
        <p>TOTAL: {{ number_format($totalPrice) }}</p>
        <p>TOTAL ITEM: {{ number_format($totalQuantity) }}</p>
    </div>
    <hr>
    @if ($isCopy)
    <p class="watermark">SALINAN</p>
    @endif
    <footer>
        <p>&lt;&lt; TERIMA KASIH &gt;&gt;</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
    </footer>
</body>
</html>
