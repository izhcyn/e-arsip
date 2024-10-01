<!-- resources/views/pdf/surat.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat PDF</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { width: 80px; }
        .header h2 { margin: 0; }
        .content { margin: 20px 0; }
        .content .tanggal { text-align: right; }
        .content .kepada { margin-top: 20px; }
        .content .isi { margin-top: 20px; }
        .signature { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <img src="/path/to/logo.png" alt="Logo">
    </div>

    <div class="content">
        <p class="tanggal">Bogor, {{ $tanggal }}</p>

        <p>Nomor: {{ $no_surat }}</p>
        <p>Perihal: {{ $perihal }}</p>

        <p class="kepada">Kepada Yth: <br>{{ $kepada }}<br>{{ $alamat }}</p>

        <p class="isi">{{ $isi_surat }}</p>

        <div class="signature">
            <p>{{ $penulis }}</p>
            <p>{{ $jabatan }}</p>
        </div>
    </div>
</body>
</html>
