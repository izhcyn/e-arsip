<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
        }
        .header {
            text-align: left;
            font-size: 18px;
            font-weight: bold;
        }
        .tgl {
            text-align: right;
            line-height: 1; /* Adjust line spacing for the date */
        }
        .content {
            margin-top: 20px;
            line-height: 1.5; /* Line spacing for the content */
        }
        .content p {
            margin: 0;
        }
        .signature {
            margin-top: 50px;
            text-align: left;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        footer {
            margin-bottom: 0px;
            text-align: center;
            font-size: 12px;
            color: #000080;
            border-top: 2px solid #000080; /* Blue line */
            padding-top: 10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Header Section with Logo -->
    <div class="header">
        <img src="{{ public_path('assets/heading_surat.png') }}" alt="Radar Bogor Logo" style="width: 70%;">
        <br />
    </div>

    <!-- Date Section -->
    <div class="tgl">
        <p>Bogor, {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
    </div>

    <!-- Content Section -->
    <div class="content">
        <?php
        // Array to convert month number to Roman numeral
            $roman_months = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
            $month = \Carbon\Carbon::parse($tanggal)->format('n'); // Get the month number without leading zeros
            $roman_month = $roman_months[$month]; // Convert to Roman numeral
        ?>
        <p>Nomor        : {{ $no_surat }}/{{ $kode_indeks }}/{{ $kode_surat }}{{ $roman_month }}/{{ \Carbon\Carbon::parse($tanggal)->format('Y') }}</p>
        <p>Perihal      : {{ $perihal }}</p>
        <p>Lampiran : {{ $lampiran }}</p>
        <br />
        <p>Kepada Yth:</p>
        <p>{{ $kepada }}</p>
        <p>{{ $alamat }}</p>
        <br />

        <p>Dengan hormat,</p>
        <br />
        <!-- Isi Surat Section with Table Rendering -->
        <div style="line-height: 1.5;">
            {!! $isi_surat !!}
        </div>
    </div>

    <!-- Signature Section -->
    <div class="signature">
        <p>     Hormat Kami     </p>
        @if ($signature)
            <img src="{{ public_path('storage/' . $signature) }}" alt="Signature" style="width: 100px;">
        @else
            <br /><br />
        @endif
        <p style="font-weight: bold">{{ $penulis }}</p>
        <p>{{ $jabatan }}</p>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>GRAHA PENA: Jl. KH. R. Abdullah Bin Muhammad Nuh No. 30, Taman Yasmin, Bogor</p>
        <p>Tel. 0251-7544001 (Hunting), Fax. 0251-7544009</p>
        <p>e-Mail: redaksi@radar-bogor.com, iklan@radar-bogor.com, promosi@radar-bogor.com</p>
    </footer>
</body>
</html>
