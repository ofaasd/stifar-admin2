<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembimbingan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .nota-container {
            width: 210mm;
            height: 297mm;
            padding: 20mm;
            border: 1px solid #000;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .header p {
            font-size: 14px;
            margin: 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 45%;
        }
        .signature p {
            margin: 80px 0 0 0;
            border-top: 1px solid #000;
            display: inline-block;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <div class="header">
            <h1>Nota Pembimbingan Skripsi</h1>
            <p>Universitas XYZ</p>
        </div>

        <div class="details">
            <table>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>John Doe</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>123456789</td>
                </tr>
                <tr>
                    <th>Judul Skripsi</th>
                    <td>Pengembangan Sistem Informasi Berbasis Web</td>
                </tr>
                <tr>
                    <th>Tanggal Pembimbingan</th>
                    <td>19 Desember 2024</td>
                </tr>
            </table>
        </div>

        <div class="signatures">
            <div class="signature">
                <p>Pembimbing 1</p>
                <p>(Dr. Jane Smith)</p>
            </div>
            <div class="signature">
                <p>Pembimbing 2</p>
                <p>(Dr. Richard Roe)</p>
            </div>
        </div>

        <div class="footer">
            <p>Catatan: Nota ini digunakan sebagai bukti bimbingan skripsi.</p>
        </div>
    </div>
</body>
</html>
