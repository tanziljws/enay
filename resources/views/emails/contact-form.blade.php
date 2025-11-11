<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan dari Form Kontak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3d4f5d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
        }
        .info-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #3d4f5d;
            margin-bottom: 5px;
        }
        .value {
            color: #495057;
        }
        .message-box {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #3d4f5d;
            margin-top: 10px;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0;">📧 Pesan Baru dari Form Kontak</h2>
    </div>
    
    <div class="content">
        <p>Anda menerima pesan baru dari form kontak website sekolah:</p>
        
        <div class="info-row">
            <div class="label">👤 Nama Pengirim:</div>
            <div class="value">{{ $data['name'] }}</div>
        </div>
        
        <div class="info-row">
            <div class="label">📧 Email:</div>
            <div class="value">
                <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
            </div>
        </div>
        
        @if(!empty($data['phone']))
        <div class="info-row">
            <div class="label">📱 Nomor Telepon:</div>
            <div class="value">{{ $data['phone'] }}</div>
        </div>
        @endif
        
        <div class="info-row">
            <div class="label">📋 Subjek:</div>
            <div class="value">
                @switch($data['subject'])
                    @case('informasi')
                        Informasi Pendaftaran
                        @break
                    @case('akademik')
                        Pertanyaan Akademik
                        @break
                    @case('fasilitas')
                        Informasi Fasilitas
                        @break
                    @default
                        Lainnya
                @endswitch
            </div>
        </div>
        
        <div class="info-row">
            <div class="label">💬 Pesan:</div>
            <div class="message-box">
                {!! nl2br(e($data['message'])) !!}
            </div>
        </div>
        
        <div class="info-row">
            <div class="label">🕒 Waktu Pengiriman:</div>
            <div class="value">{{ now()->format('d F Y, H:i:s') }} WIB</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Email ini dikirim otomatis dari form kontak website sekolah.</p>
        <p>Untuk membalas, klik email pengirim di atas atau hubungi langsung melalui nomor telepon yang tertera.</p>
    </div>
</body>
</html>
