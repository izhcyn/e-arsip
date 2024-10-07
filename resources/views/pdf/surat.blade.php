<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            position: relative;
            margin: 20px 20px 20px 30px;
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
            width: 100px;
            text-align: left;
        }
        .heading{
            border: transparent;
            width: 100%;
            text-align: left;
            padding: 2px 5px;
            line-height: 1.2;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            padding: 5px;
            text-align: left;
        }
        footer {
            text-align: center;
            bottom: 0;
            position: fixed;
            font-size: 13px;
            color: #000080;
            width: 100%;
        }

        footer p{
            margin: 0;
            padding-right: 60px;
        }

        .page-break {
            page-break-before: always;
        }
        .attachment {
            page-break-before: always;
            text-align: center
        }
        .attachment img {
            max-width: 100%;
            max-height: 800px;
            object-fit: contain;
            display: block;
            margin: 20px 20px 20px 30px;
        }
    </style>
</head>
<body>
    <!-- Header Section with Logo -->
    <div class="header">
        <img src="{{ public_path('assets/heading_surat.png') }}" alt="Radar Bogor Logo" style="width: 50%;">
        <br />
    </div>

    <!-- Date Section -->
    <div class="tgl">
        <?php
        // Set the locale to Indonesian
        \Carbon\Carbon::setLocale('id');
        ?>
        <p>Bogor, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <!-- Content Section -->
    <div class="content">
        <?php
        // Array to convert month number to Roman numeral
        $roman_months = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $month = \Carbon\Carbon::parse($tanggal)->format('n'); // Get the month number without leading zeros
        $roman_month = $roman_months[$month]; // Convert to Roman numeral
        ?>
        <table class="heading" style="">
            <tr padding: 2px 5px;>
                <td style="width: 20%">Nomor</td>
                <td style="width: 5%">:</td>
                <td style="width: 75%">{{ $no_surat }}/{{ $kode_surat }}{{ $roman_month }}/{{ \Carbon\Carbon::parse($tanggal)->format('Y') }}</td>
            </tr>
            <tr>
                <td style="width: 20%">Perihal</td>
                <td style="width: 5%">:</td>
                <td style="width: 75%">{{ $perihal }}</td>
            </tr>
            <tr>
                <td style="width: 20%">Lampiran</td>
                <td style="width: 5%">:</td>
                <td style="width: 75%">{{ $lampiran }}</td>
            </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr>
                <td>Kepada Yth:</td>

            </tr>
            <tr>
                <td>{{ $kepada }}</td>
            </tr>
            <tr>
                <td style="width: 50%">{{ $alamat }}</td>
            </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
        </table>
    </div>

        <div>
            {!! $isi_surat !!}
        </div>
    </div>

    <!-- Signature Section -->
<!-- Signature Section -->
    <div class="signature">
        <p style="text-align: center;">Hormat Kami</p>
            @if ($signature)
                <img src="{{ public_path('storage/' . $signature) }}" alt="Signature" style="width: 100px;">
            @endif
        <p style="font-weight: bold; text-align: center;">{{ $penulis }}</p>
        <p style="text-align: center;">{{ $jabatan }}</p>
    </div>

<!-- Notes Section -->
    <div class="notes" style="margin-top: 20px;">
        @if ($notes)
            {!! $notes !!}
        @endif
    </div>

    <!-- Footer Section -->
    <footer>
            <hr style="width: 90%; margin-left: 0; background-color: #000080;">
            <p>GRAHA PENA: Jl. KH. R. Abdullah Bin Muhammad Nuh No. 30, Taman Yasmin, Bogor</p>
            <p>Tel. 0251-7544001 (Hunting), Fax. 0251-7544009</p>
            <p>e-Mail: redaksi@radar-bogor.com, iklan@radar-bogor.com, promosi@radar-bogor.com</p>
    </footer>

    @if ($file_lampiran)
        @if (in_array(pathinfo($file_lampiran, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
            <div class="attachment">
                <img src="{{ public_path('storage/' . $file_lampiran) }}" alt="Lampiran">
            </div>
        @endif
    @endif



</body>
</html>
