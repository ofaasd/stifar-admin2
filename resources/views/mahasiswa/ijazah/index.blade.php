<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>Ijazah-{{ $data->nim }}</title> --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman:wght@400;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f5f5f5;
        }
        
        .certificate {
            padding: 20px;
            width: 100%;
            height: 210mm;
            padding: 22px;
            position: relative;
        }

        .mark-duplikat {
            position: absolute;
            right: 18px;
            bottom: 12px;
            font-size: 6px;
            color: #888;
            opacity: 0.25;
            pointer-events: none;
            white-space: nowrap;
            z-index: 1000;
        }
        
        .header {
            text-align: right;
            margin-bottom: 25px;
        }
        
        .serial-number {
            font-size: 14px;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .accreditation {
            font-size: 14px;
        }
        
        .statement {
            margin-top: 46px;
            font-size: 14px;
            text-align: center;
        }
        
        .student-name {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }
        
        .student-info {
            font-size: 14px;
            text-align: center;
        }
        
        .program-info {
            font-size: 14px;
            text-align: center;
        }
        
        .program-accreditation {
            font-size: 14px;
            text-align: center;
        }
        
        .decree-info {
            font-size: 14px;
            text-align: center;
        }
        
        .graduation-info {
            font-size: 14px;
            text-align: center;
        }
        
        .certificate-title {
            font-size: 16px;
            text-align: center;
        }
        
        .degree {
            font-size: 14px;
            text-align: center;
        }
        
        .rights {
            font-size: 14px;
            text-align: center;
        }

        .award-info {
            font-size: 14px;
            text-align: center;
        }
        
        .signatures {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .signature-block {
            text-align: center;
            width: 100%;
        }
        
        .signature-title {
            font-size: 12px;
        }
        
        .signature-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .signature-niy {
            font-size: 12px;
        }
        
        .bilingual {
            margin-bottom: 4px;
        }
        
        .english {
            font-family: 'Times New Roman', Times, serif;
            font-style: italic;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="serial-number">
                <div>Nomor Seri Ijazah : {{ $nomorSeri ?? '-' }}</div>
                <div class="english">Certificate Serial Number : {{ $nomorSeri ?? '-' }}</div>
            </div>
        </div>
        
        <div class="statement bilingual">
            <div class="accreditation">
                {{ $akreditasiBadanPtKes ?? '-' }}
            </div>
            <div>dengan ini menyatakan bahwa :</div>
            <div class="english">states that :</div>
        </div>
        
        <div class="student-name">
            {{ $data->nama ?? '-' }}
        </div>
        
        <div class="student-info">
            <div class="bilingual">
                <div>Nomor Induk Mahasiswa : {{ $data->nim ?? '-' }}</div>
                <div class="english">Student Registration Number : {{ $data->nim ?? '-' }}</div>
            </div>
            <div class="bilingual">
                <div>Lahir di : {{ $data->kotaKelahiran ?? '-' }} Pada : {{ \Carbon\Carbon::parse($data->tanggalLahir)->translatedFormat('d F Y') ?? '-' }} Dengan Nomor Induk Kependudukan : {{ $data->nik ?? '-' }}</div>
                <div class="english">Place of Birth : {{ $data->kotaKelahiran ?? '-' }} On : {{ \Carbon\Carbon::parse($data->tanggalLahir)->format('F j') }}<sup>{{ date('S', strtotime($data->tanggalLahir)) }}</sup>, {{ \Carbon\Carbon::parse($data->tanggalLahir)->format('Y') ?? '-' }} With Identity Number : {{ $data->nik ?? '-' }}</div>
            </div>
        </div>
        
        <div class="program-info bilingual">
            <div>Telah menyelesaikan dan memenuhi segala syarat pendidikan, pada Program Studi {{ $data->prodiIndo ?? '-' }},</div>
            <div class="english">Has Accomplished and completed all requirements of {{ $data->prodiInggris ?? '-' }} Study Program</div>
        </div>
        
        <div class="program-accreditation bilingual">
            <div>{{ $akreditasiLamPtKes ?? '-' }}</div>
            <div class="english">{{ $akreditasiLamPtKesInggris ?? '-' }}</div>
        </div>
        
        <div class="decree-info bilingual">
            <div>Dengan ijin penyelenggaraan berdasarkan : Surat Keputusan Menteri Pendidikan Nasional Republik Indonesia Nomor : 153/D/O/2000 tanggal 10 Agustus 2000</div>
            <div class="english">based on the decree off : The Minister of National Education of the Republic of Indonesia Number : 153/D/O/2000 on August 10<sup>th</sup>, 2000</div>
        </div>
        
        <div class="graduation-info bilingual">
            <div>Yang Bersangkutan Dinyatakan Lulus Pada Tanggal : {{ \Carbon\Carbon::parse($data->lulusPada)->translatedFormat('d F Y') ?? '-' }}, Sehingga kepadanya diberikan :</div>
            <div class="english">Has Passed Graduate on {{ \Carbon\Carbon::parse($data->lulusPada)->format('F j') }}<sup>{{ date('S', strtotime($data->lulusPada)) }}</sup>, {{ \Carbon\Carbon::parse($data->lulusPada)->format('Y') ?? '-' }} and there by has been declared a :</div>
        </div>
        
        <div class="certificate-title bilingual">
            <div style="font-weight: bold;">IJAZAH</div>
            <div class="english">Certificate</div>
        </div>
        
        <div class="certificate-title bilingual">
            <div>dengan gelar</div>
            <div class="english">degree</div>
        </div>
        
        <div class="degree bilingual">
            <div style="font-weight: bold;">{{ $data->namaIjazahIndo ?? '-' }}</div>
            <div class="english">{{ $data->namaIjazahInggris ?? '-' }}</div>
        </div>
        
        <div class="rights bilingual">
            <div>Beserta Segala Hak dan Kewajiban yang melekat pada gelar tersebut.</div>
            <div class="english">with all the rights and responsibilities thereunto appertaining</div>
        </div>
        
        <div class="award-info bilingual">
            <div>Diberikan di Semarang pada tanggal : {{ \Carbon\Carbon::parse(now())->translatedFormat('d F Y') ?? '-' }}</div>
            <div class="english">Awarded in Semarang on {{ \Carbon\Carbon::parse(now())->format('F j') }}<sup>{{ date('S', strtotime(now())) }}</sup>, {{ \Carbon\Carbon::parse(now())->format('Y') ?? '-' }}</div>
        </div>
        
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="text-align: center;">
                    <div class="signature-title bilingual">
                        <div>Ketua Program Studi</div>
                        <div class="english">Head of Study Program</div>
                    </div>
                </td>
                <td></td>
                <td style="text-align: center;">
                    <div class="signature-title bilingual">
                        <div>Ketua</div>
                        <div class="english">Director</div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="text-align:center; padding-top: 58px;">
                    <div class="signature-block" style="border:none;">  
                        <div class="signature-name">{{ $namaKaprodi ?? '-' }}</div>
                        <div class="signature-niy">NIY : {{ $niyKaprodi ?? '-' }}</div>
                    </div>
                </td>
                <td style="text-align:right;">
                    <div style="position: absolute; top: 32px; right: 32px; margin-bottom: 0; z-index: 10;">
                        <img src="{{ asset('assets/images/mahasiswa/ijazah/frame-pas-foto-3x4.png') }}" alt="Foto frame pas foto" style="position: relative; height:80px; width:auto; max-width:90px; object-fit:cover; border-radius:6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    </div>
                </td>
                <td style="text-align: center; padding-top: 58px;">
                    <div class="signature-block" style="border:none; text-align: center; width: 100%;">
                        <div class="signature-name">Dr. apt. Sri Haryanti, M.Si.</div>
                        <div class="signature-niy">NIY : YP.030795003</div>
                    </div>
                </td>
            </tr>
        </table>
        @if (isset($duplikatKe) && $duplikatKe)
            <div class="mark-duplikat">
                Duplikat {{ $duplikatKe }}
            </div>
        @endif
    </div>
</body>
</html>