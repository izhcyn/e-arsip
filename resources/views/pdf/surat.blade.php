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
            text-align: center;
            line-height: 1; /* Adjust line spacing for the date */
        }
        .content {
            margin-top: 20px;
            line-height: 1.2; /* Adjust line spacing for the content */
        }
        .content p {
            margin: 0;
        }
        .signature {
            margin-top: 25px;
            width: 200px;
            text-align: center; /* Perbaiki menjadi center */
        }

        .signature p, .signature table {
            margin: 0;
            padding: 0;
            line-height: 1; /* Mengurangi jarak antar teks */
        }

        .heading {
            width: 100%;
            text-align: left;
            line-height: 1.1; /* Adjust line spacing */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            padding: 2px; /* Reduce padding */
            vertical-align: top; /* Align text at the top */
        }
        td {
            width: 1px; /* Shrink to minimum width */
            white-space: nowrap; /* Keep text on one line */
        }

        .colon {
            width: 1%; /* Narrow width for colon */
            text-align: left;
        }
        .value {
            width: 95%; /* Adjust width for values */
            text-align: left;
        }
        footer {
            text-align: center;
            bottom: 0;
            position: fixed;
            font-size: 11px;
            color: #2A4879;
            width: 100%;
        }

        footer p {
            margin: 0;
            padding-right: 60px;
            font-weight: bold;
        }
    </style>

</head>
<body>
    <!-- Header Section with Logo -->
    <div class="header">
        <img src="{{ public_path('assets/heading_surat2.png') }}" alt="Radar Bogor Logo" style="width: 50%;">
        <br />
    </div>

    <!-- Date Section -->


    <!-- Content Section -->
    <div class="content">
        <?php
        // Array to convert month number to Roman numeral
        $roman_months = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $month = \Carbon\Carbon::parse($tanggal)->format('n'); // Get the month number without leading zeros
        $roman_month = $roman_months[$month]; // Convert to Roman numeral
        ?>
        <table class="heading">
                <tr>
                    <td class="label" style="width: 5%">Nomor</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $no_surat }}/{{ $kode_surat }}/{{ $roman_month }}/{{ \Carbon\Carbon::parse($tanggal)->format('Y') }}</td>
                </tr>
                <tr>
                    <td class="label" style="width: 5%">Perihal</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $perihal }}</td>
                </tr>
                <tr>
                    <td class="label" style="width: 5%">Lampiran</td>
                    <td class="colon">:</td>
                    <td class="value">{{ $lampiran }}</td>
                </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr>
                <td>Kepada Yth. :</td>

            </tr>
            <tr>
                <td>{!! $kepada !!}</td>
            </tr>
            <tr>
                <td>{!! $alamat !!}</td>
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

    <div class="signature">
        <div class="tgl">
            <?php
            // Set the locale to Indonesian
            \Carbon\Carbon::setLocale('id');
            ?>
            <p>Bogor, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
        </div>
        @if ($signature)
            <img src="{{ public_path('storage/' . $signature) }}" alt="Signature" style="width: 100px;">
        @else
            <br />
            <br />
            <br />
            <br />
        @endif
        <p><strong>{!! $penulis !!}</strong></p>
        <p>{{ $jabatan }}</p>
    </div>


<!-- Notes Section -->
    <div class="notes" style="margin-top: 20px;">
        @if ($notes)
            {!! $notes !!}
        @endif
    </div>

    <!-- Footer Section -->
    <footer>
            <p>GRAHA PENA : Jl. KHR. Abdullah Bin M. Nuh No. 30 Taman Yasmin Bogor</p>
            <p>(HUNTING) 0251-7544001-004, Fax. 0251-7544009</p>
            <p>Email : <u>redaksi@radar-bogor.com</u>, <u>iklan@radar-bogor.com</u></p>
    </footer>

    @if ($file_lampiran)
    @if (in_array(pathinfo($file_lampiran, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
        <div class="attachment">
            <img src="{{ public_path('storage/' . $file_lampiran) }}" alt="Lampiran" style="max-width: 100%; height: auto;">
        </div>
    @endif
@endif




</body>
</html>
