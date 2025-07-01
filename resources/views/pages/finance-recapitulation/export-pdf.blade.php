<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Rekapitulasi Keuangan</title>
    <link rel="stylesheet" href="{{ public_path("assets/css/finance-recapitulation-pdf.min.css") }}">
</head>
<body>
    <div class="container">
        <div class="header no-page-break">
            <div class="logo">
                <img src="{{ public_path("assets/img/baitana-logo-white.png") }}" alt="Logo Baitana">
            </div>
            <h1 class="organization-name">Bagian Perbendaharaan Masjid</h1>
            <div class="report-title">LAPORAN REKAPITULASI KEUANGAN</div>
            <div class="report-period">Periode: {{ $datePeriod->startDate. " - ". $datePeriod->endDate }}</div>
        </div>
        <div class="summary-section no-page-break">
            <div class="summary-title">RINGKASAN KEUANGAN</div>
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Total Pemasukan</td>
                    <td class="summary-value amount amount-debit">{{ "Rp. ". number_format($financeAccumulation->total_income, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Total Pengeluaran</td>
                    <td class="summary-value amount amount-credit">{{ "Rp. ". number_format($financeAccumulation->total_expense, 0, ',', '.') }}</td>
                </tr>
                <tr class="summary-total">
                    <td class="summary-label">Total Saldo</td>
                    <td class="summary-value amount">{{ "Rp. ". number_format(($financeAccumulation->total_income - $financeAccumulation->total_expense), 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="section-divider"></div>

        <div class="transaction-section">
            <div class="transaction-title">DETAIL TRANSAKSI KEUANGAN</div>
            <table class="transaction-table">
                <thead>
                    <tr>
                        <th class="col-no">No.</th>
                        <th class="col-date">Tanggal</th>
                        <th class="col-code">Kode Transaksi</th>
                        <th class="col-category">Kategori</th>
                        <th class="col-description">Deskripsi</th>
                        <th class="col-debit">Pemasukan</th>
                        <th class="col-credit">Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($financeRecapitulations as $financeRecapitulation)
                        <tr>
                            <td class="col-no-cell">{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($financeRecapitulation->date)->format("d - m - Y") }}</td>
                            <td class="col-code-cell">{{ $financeRecapitulation->transaction_code }}</td>
                            @switch($financeRecapitulation->financeCategory->type)
                                @case("income")
                                    <td>
                                        <span class="category-tag category-income-a">{{ $financeRecapitulation->financeCategory->name }}</span>
                                    </td>
                                    @break
                                @case("expense")
                                    <td>
                                        <span class="category-tag category-expense-a">{{ $financeRecapitulation->financeCategory->name }}</span>
                                    </td>
                                    @break
                            @endswitch
                            <td class="col-description">{{ $financeRecapitulation->description }}</td>
                            <td class="amount amount-debit">{{ $financeRecapitulation->income ? "Rp. ". number_format($financeRecapitulation->income, 0, ',', '.') : "-" }}</td>
                            <td class="amount amount-credit">{{ $financeRecapitulation->expense ? "Rp. ". number_format($financeRecapitulation->expense, 0, ',', '.') : "-" }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="col-description" colspan="7">Tidak ada transaksi!</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total Akumulasi</th>
                        <td class="amount amount-debit">{{ "Rp. ". number_format($financeAccumulation->total_income, 0, ',', '.') }}</td>
                        <td class="amount amount-credit">{{ "Rp. ". number_format($financeAccumulation->total_expense, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>