<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .main-table td {
            padding: 8px;
            vertical-align: top;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2, .header h4 {
            margin: 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .disposition-card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .disposition-instruction {
            font-style: italic;
            font-size: 14px;
        }
        .meta-info {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .recipients-table {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="header">
    <h2>LEMBAR DISPOSISI</h2>
    <h4>BAPPEDA PROVINSI SULAWESI UTARA</h4>
</div>

<div class="section-title">A. DETAIL SURAT MASUK</div>
<table class="main-table details-table" border="1">
    <tr>
        <td width="30%"><strong>Nomor Surat</strong></td>
        <td width="70%">{{ $letter->letter_number }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal Surat</strong></td>
        <td>{{ \Carbon\Carbon::parse($letter->letter_date)->isoFormat('D MMMM YYYY') }}</td>
    </tr>
    <tr>
        <td><strong>Perihal</strong></td>
        <td>{{ $letter->subject }}</td>
    </tr>
    <tr>
        <td><strong>Pengirim</strong></td>
        <td>{{ $letter->sender }}</td>
    </tr>
    <tr>
        <td><strong>Diinput Oleh</strong></td>
        <td>{{ $letter->user->name }}</td>
    </tr>
</table>

<div class="section-title">B. DAFTAR DISPOSISI</div>

@if($letter->dispositions->count() > 0)
    @foreach($letter->dispositions as $disposition)
        <div class="disposition-card">
            <table class="main-table">
                <tr>
                    <td>
                        <div class="disposition-instruction">"{{ $disposition->instruction }}"</div>
                        <div class="meta-info">
                            Dibuat oleh <strong>{{ $disposition->user->name }}</strong> pada {{ \Carbon\Carbon::parse($disposition->created_at)->isoFormat('D MMMM YYYY, HH:mm') }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="recipients-table">
                            <tr>
                                <td colspan="2"><strong>Diteruskan kepada:</strong></td>
                            </tr>
                            @foreach($disposition->recipients as $recipient)
                                <tr>
                                    <td>{{ $recipient->name }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
@else
    <p>Belum ada disposisi untuk surat ini.</p>
@endif
</body>
</html>
