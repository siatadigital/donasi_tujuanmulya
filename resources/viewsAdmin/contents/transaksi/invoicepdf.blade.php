<style>
    #inventory-invoice a {
        text-decoration: none ! important;
    }

    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #998306
    }

    .company-details {
        text-align: right;
        /* max-width:400px; */
    }

    .company-details .name {
        margin-top: 0;
        margin-bottom: 0;
        color: #998306
    }

    .invoice .contacts {
        margin-bottom: 20px
    }

    .invoice .invoice-to {
        text-align: left
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #998306
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #998306
    }

    .invoice main .notices .notice {
        font-size: 1em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table.invtable td,
    .invoice table th {
        padding: 15px;
        background: #eee;
    }

    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px;
        border: 1px solid #fff;
    }

    .invoice table td {
        border: 1px solid #fff;
    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #998306;
        font-size: 1em
    }

    .invoice table .tax,
    .invoice table .total,
    .invoice table .unit {
        text-align: right;
        font-size: 1em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #9c8816
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #9c8816;
        color: #fff;
        font-size: 1.6em;
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.4em;
        border-top: 1px solid #aaa
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }
</style>
<div id="inventory-invoice">
    <div style="border-bottom: #9c8816 2px solid; margin-bottom:15px">
        <table>
            <tr class="row">
                <td>
                    <img src="https://yukdonasi.org/public/images/logo_n1.png" alt="peduli" width="200px" class="pull-left mx-auto">
                </td>
                <td>
                    <div class="col company-details">
											<h2 class="name">yukdonas.org </h2>
											<div>Al Barokah Block. C No. 11 RT. 006 Rw. 009 Lebakwangi Sepatin Timur Kab. Tangerang Banten</div>
											<div>WhatsApp : +6285711122646</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <main>
                <table>
                    <tr>
                        <td>
                            <div class="col invoice-to">
                                <div class="text-gray-light">INVOICE TO:</div>
                                <h2 class="to">{{ $transactions->fullname }}</h2>
                                <table style="border:0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>Kota</td>
                                        <td>:</td>
                                        <td>
                                            <div class="address">{{ $transactions->city }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>
                                            <div class="email"><a href="mailto:{{ $transactions->email }}">{{ $transactions->email }}</a></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Telepon</td>
                                        <td>:</td>
                                        <td>
                                            <div class="email">{{ $transactions->phone }}</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td>
                            <div class="col invoice-details">
                                <h1 class="invoice-id">INVOICE #MH{{ $transactions->id }}</h1>
                                <div class="date">Tanggal Invoice: {{ formatTime($transactions->created_at, 'd F Y, H:i') }} </div>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="invtable" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th style="text-align:left">NAMA DONASI</th>
                            <th style="text-align:right">NOMINAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left">
                                <h3>{{ $transactions->akad }}</h3>{{ $transactions->project_title }}
                            </td>
                            <td class="total"> {{ number_format($transactions->amount, 0, ",", ".") }}</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tr>
                        <td style="text-align: left">
                            <div>Metode Pembayaran</div>
                            <h3>{{ $transactions->data_payment_method }}</h3>
                        </td>
                        <td style="text-align: right">
                            <div>Status Pembayaran</div>
                            <h3>{{ strtoupper($transactions->status="ACCEPT"?"SUCCESS":$transactions->status) }}</h3>
                        </td>
                    </tr>
                </table>
                <div class="notices">
                    <div>NOTICE:</div>
                    <div class="notice">Semoga Allah memberi pahala atas apa yang telah Anda berikan, menjadikannya sebagai penyuci untuk Anda, dan memberkahi untuk Anda apa yang masih tersisa.</div>
                </div>
            </main>
            <div style=" font-size: 1em;text-align:center;margin-bottom:10px">Terimakasih atas kepercayaannya. Untuk informasi program infak/zakat lainnya, silahkan kunjungi <span class="email"><a href="{{ url() }}">yukdonasi.org</a></span>
            </div>
            <footer>
                Invoice was generated on a computer and is valid without the signature and seal.
            </footer>
        </div>
    </div>
</div>