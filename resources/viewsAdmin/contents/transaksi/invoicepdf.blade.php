<?php
    $baseAmount = (float) $transactions->amount;
    $uniqueCode = (float) ($transactions->unique_code ?: 0);
    $totalAmount = $baseAmount + $uniqueCode;
    $transactionStatus = strtolower((string) $transactions->status) === 'accept'
        ? 'SUCCESS'
        : strtoupper((string) $transactions->status);
    $recipientName = $transactions->fullname ?: 'Donatur';
    $campaignName = $transactions->project_title ?: 'Donasi umum';
    $logoPath = str_replace('\\', '/', public_path('images/logo-nh.png'));
?>
<style>
    @page { margin: 24px; }
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; background: #fff; color: #17303b; font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; line-height: 1.5; }
    #inventory-invoice { width: 100%; }
    #inventory-invoice a { color: #ea8e26; text-decoration: none; }
    .invoice-shell { overflow: hidden; border: 1px solid #d9e1e5; border-top: 7px solid #ea8e26; background: #fff; }
    .invoice-header { width: 100%; padding: 24px 28px; background: #17303b; color: #fff; }
    .invoice-header table, .invoice-body table { width: 100%; border-collapse: collapse; }
    .invoice-header td { vertical-align: middle; }
    .brand-logo { display: block; width: 198px; height: auto; padding: 7px 10px; border-radius: 6px; background: #fff; }
    .brand-caption { padding-top: 8px; color: #d8e5e9; font-size: 10px; letter-spacing: .4px; }
    .header-meta { text-align: right; color: #d8e5e9; font-size: 10px; line-height: 1.7; }
    .header-meta strong { display: block; color: #fff; font-size: 13px; }
    .invoice-body { padding: 26px 28px 20px; }
    .invoice-title { margin: 0; color: #17303b; font-size: 24px; font-weight: 700; }
    .invoice-number { margin-top: 4px; color: #ea8e26; font-size: 13px; font-weight: 700; }
    .invoice-date { color: #6d7b80; font-size: 10px; text-align: right; }
    .invoice-date strong { color: #17303b; }
    .info-grid { margin-top: 24px; }
    .info-grid td { width: 50%; padding: 16px; vertical-align: top; border: 1px solid #e3eaed; background: #f8fafb; }
    .info-grid td + td { border-left: 0; }
    .eyebrow { margin-bottom: 6px; color: #ea8e26; font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
    .recipient-name { margin: 0 0 7px; color: #17303b; font-size: 15px; font-weight: 700; }
    .muted, .detail-label { color: #6d7b80; }
    .detail-row { padding: 2px 0; }
    .detail-label { display: inline-block; width: 54px; }
    .detail-value { color: #17303b; }
    .invoice-table { width: 100%; margin-top: 24px; border-collapse: collapse; }
    .invoice-table th { padding: 11px 14px; color: #fff; background: #17303b; font-size: 10px; font-weight: 700; letter-spacing: .6px; text-align: left; text-transform: uppercase; }
    .invoice-table th:last-child, .invoice-table td:last-child { text-align: right; }
    .invoice-table td { padding: 15px 14px; border: 1px solid #e3eaed; vertical-align: top; }
    .transaction-type { margin-bottom: 4px; color: #ea8e26; font-size: 11px; font-weight: 700; }
    .campaign-name { color: #17303b; font-size: 12px; }
    .amount-cell { color: #17303b; font-size: 15px; font-weight: 700; white-space: nowrap; }
    .summary-table { width: 100%; margin-top: 18px; border-collapse: collapse; }
    .summary-table td { padding: 7px 0; border-bottom: 1px solid #edf1f2; }
    .summary-table td:last-child { color: #17303b; font-weight: 700; text-align: right; }
    .summary-table .grand-total td { padding-top: 12px; border-bottom: 0; color: #ea8e26; font-size: 16px; }
    .status-row { margin-top: 18px; }
    .status-row td { width: 50%; padding: 14px 16px; border: 1px solid #e3eaed; vertical-align: top; }
    .status-value { color: #2f8f5b; font-size: 13px; font-weight: 700; }
    .notice { margin-top: 22px; padding: 13px 16px; border-left: 4px solid #ea8e26; background: #fff8ef; color: #526269; font-size: 10px; }
    .notice strong { display: block; margin-bottom: 3px; color: #17303b; font-size: 10px; text-transform: uppercase; }
    .invoice-footer { padding: 16px 28px 20px; border-top: 1px solid #e3eaed; color: #6d7b80; font-size: 9px; text-align: center; }
    .invoice-footer strong { color: #17303b; }
</style>

<div id="inventory-invoice">
    <div class="invoice-shell">
        <div class="invoice-header">
            <table>
                <tr>
                    <td>
                        <img class="brand-logo" src="{{ $logoPath }}" alt="Tujuan Mulia">
                        <div class="brand-caption">Platform kebaikan untuk berbagi dan berdampak.</div>
                    </td>
                    <td class="header-meta">
                        <strong>BUKTI TRANSAKSI</strong>
                        Tujuan Mulia<br>
                        Dokumen resmi pembayaran donasi
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-body">
            <table>
                <tr>
                    <td>
                        <h1 class="invoice-title">Invoice Donasi</h1>
                        <div class="invoice-number">#MH{{ $transactions->id }}</div>
                    </td>
                    <td class="invoice-date">Tanggal transaksi<br><strong>{{ formatTime($transactions->created_at, 'd F Y, H:i') }}</strong></td>
                </tr>
            </table>

            <table class="info-grid">
                <tr>
                    <td>
                        <div class="eyebrow">Donatur</div>
                        <h2 class="recipient-name">{{ $recipientName }}</h2>
                        <div class="detail-row"><span class="detail-label">Kota</span><span class="detail-value">{{ $transactions->city ?: '-' }}</span></div>
                        <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">{{ $transactions->email ?: '-' }}</span></div>
                        <div class="detail-row"><span class="detail-label">Telepon</span><span class="detail-value">{{ $transactions->phone ?: '-' }}</span></div>
                    </td>
                    <td>
                        <div class="eyebrow">Penerima manfaat</div>
                        <h2 class="recipient-name">{{ $campaignName }}</h2>
                        <div class="muted">Terima kasih telah ikut mendukung program kebaikan melalui Tujuan Mulia.</div>
                    </td>
                </tr>
            </table>

            <table class="invoice-table">
                <thead><tr><th>Detail donasi</th><th>Nominal</th></tr></thead>
                <tbody>
                    <tr>
                        <td><div class="transaction-type">{{ $transactions->akad }}</div><div class="campaign-name">{{ $campaignName }}</div></td>
                        <td class="amount-cell">Rp {{ number_format($baseAmount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <table class="summary-table">
                <tr><td>Nominal donasi</td><td>Rp {{ number_format($baseAmount, 0, ',', '.') }}</td></tr>
                <tr><td>Kode unik</td><td>Rp {{ number_format($uniqueCode, 0, ',', '.') }}</td></tr>
                <tr class="grand-total"><td>Total dibayarkan</td><td>Rp {{ number_format($totalAmount, 0, ',', '.') }}</td></tr>
            </table>

            <table class="status-row">
                <tr>
                    <td><div class="eyebrow">Metode pembayaran</div><strong>{{ $transactions->data_payment_method ?: '-' }}</strong></td>
                    <td><div class="eyebrow">Status pembayaran</div><div class="status-value">{{ $transactionStatus }}</div></td>
                </tr>
            </table>

            <div class="notice"><strong>Catatan</strong>Semoga Allah menerima kebaikan ini, memberikan keberkahan, dan menjadikan donasi ini bermanfaat bagi penerima.</div>
        </div>

        <div class="invoice-footer">
            Terima kasih atas kepercayaan Anda kepada <strong>Tujuan Mulia</strong>.<br>
            Dokumen ini dibuat otomatis dan sah tanpa tanda tangan atau stempel.
        </div>
    </div>
</div>
