<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Disposisi</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
        }
        .container {
            padding: 0;
            margin: 0;
        }
        .header {
            text-align: center;
            line-height: 1.2;
        }
        .header h3, .header h2 {
            margin: 0;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .double-hr {
            border: 0;
            border-top: 3px double #000;
            margin: 5px 0 15px 0;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        .main-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        .no-border-table {
            width: 100%;
            border-collapse: collapse;
        }
        .no-border-table td {
            border: none;
            padding: 2px 0;
        }
        .checkbox-label {
            display: block;
            align-items: center;
            padding-bottom: 5px;
        }
        .signature-box {
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h3>LEMBAR DISPOSISI</h3>
        <h2>PEMERINTAH PROVINSI SULAWESI UTARA</h2>
        <h2>BADAN PERENCANAAN PEMBANGUNAN DAERAH</h2>
        <p>J. 17 Agustus No. 69, Telp. (0431) 865204, Fax (0431) 841147</p>
        <p>bappeda.sulutprov.go.id, email: bappeda.sulut@gmail.com</p>
        <p>MANADO 95117</p>
    </div>
    <hr class="double-hr">

    <table class="main-table">
        <tr>
            <td width="50%">
                <table class="no-border-table">
                    <tr>
                        <td width="25%"><strong>Surat Dari</strong></td>
                        <td width="5%">:</td>
                        <td>{{ $letter->sender }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Surat</strong></td>
                        <td>:</td>
                        <td>{{ $letter->letter_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tgl. Surat</strong></td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($letter->letter_date)->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Hal</strong></td>
                        <td>:</td>
                        <td>{{ $letter->subject }}</td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table class="no-border-table">
                    <tr>
                        <td width="30%"><strong>Diterima Tgl</strong></td>
                        <td width="5%">:</td>
                        <td>&nbsp;</td> </tr>
                    <tr>
                        <td><strong>No. Agenda</strong></td>
                        <td>:</td>
                        <td>&nbsp;</td> </tr>
                    <tr>
                        <td><strong>Sifat</strong></td>
                        <td>:</td>
                        <td>
                            <label class="checkbox-label"><input type="checkbox"> Segera</label>
                            <label class="checkbox-label"><input type="checkbox"> Sangat Segera</label>
                            <label class="checkbox-label"><input type="checkbox"> Rahasia</label>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" style="padding: 0;">
                <table class="no-border-table" style="width: 100%;">
                    <tr>
                        <td style="padding: 8px; border-right: 1px solid #000; width: 50%;">
                            <label class="checkbox-label"><input type="checkbox"> Sekretaris</label>
                            <label class="checkbox-label"><input type="checkbox"> Kepala Bidang Monev</label>
                            <label class="checkbox-label"><input type="checkbox"> Kepala Bidang Perekonomian dan Perdagangan</label>
                            <label class="checkbox-label"><input type="checkbox"> Kepala Bidang IPW</label>
                            <label class="checkbox-label"><input type="checkbox"> Kepala Bidang Pemsosbud</label>
                        </td>
                        <td style="padding: 8px; width: 50%;">
                            <strong>Dengan hormat harap:</strong><br><br>
                            <label class="checkbox-label"><input type="checkbox"> Tanggapan dan saran</label>
                            <label class="checkbox-label"><input type="checkbox"> Proses lebih lanjut</label>
                            <label class="checkbox-label"><input type="checkbox"> Koordinasi/Konfirmasi</label>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="height: 150px;">
                <strong>Catatan:</strong><br>
                @if($letter->dispositions->count() > 0)
                    @foreach($letter->dispositions as $disposition)
                        <p><em>"{{ $disposition->instruction }}" - (Oleh: {{ $disposition->user->name }})</em></p>
                    @endforeach
                @endif
            </td>
            <td class="signature-box">
                <strong>KEPALA BAPPEDA</strong><br>
                <strong>PROVINSI SULAWESI UTARA</strong>
                <br><br><br><br><br>
                <strong><u>ELVIRA M. KATUUK, ST., ME.</u></strong><br>
                NIP. 19700520 200604 2 003
            </td>
        </tr>
    </table>
</div>
</body>
</html>
