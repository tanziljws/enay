<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Statistik Galeri - {{ date('d F Y') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        
        .header h2 {
            font-size: 20px;
            margin: 5px 0;
        }
        
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .summary-card {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
        }
        
        .summary-card h3 {
            font-size: 14px;
            margin: 0 0 10px 0;
        }
        
        .summary-card .number {
            font-size: 24px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-size: 12px;
        }
        
        td {
            font-size: 11px;
        }
        
        .photo-thumb {
            max-height: 50px;
            width: auto;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
        
        @media print {
            body {
                font-size: 10px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .header h2 {
                font-size: 16px;
            }
            
            .summary-card .number {
                font-size: 20px;
            }
            
            th, td {
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STATISTIK GALERI</h1>
        <h2>SMK NEGERI 4 KOTA BOGOR</h2>
        <p>Periode: {{ date('d F Y') }}</p>
    </div>
    
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Foto</h3>
            <div class="number">{{ $totalItems }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Dilihat</h3>
            <div class="number">{{ $totalViews }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Disukai</h3>
            <div class="number">{{ $totalLikes }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Komentar</h3>
            <div class="number">{{ $totalComments }}</div>
        </div>
    </div>
    
    <div class="section-title">Foto Paling Banyak Dilihat</div>
    @if($topViewed->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Judul</th>
                    <th>Dilihat</th>
                    <th>Disukai</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topViewed as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ public_path('storage/' . $item->image) }}" alt="{{ $item->title }}" class="photo-thumb" onerror="this.style.display='none'">
                        @else
                            <div style="height: 50px; width: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 10px;">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->views_count }}</td>
                    <td>{{ \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count() }}</td>
                    <td>{{ $item->approved_comments_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data.</p>
    @endif
    
    <div class="section-title">Foto Paling Banyak Disukai</div>
    @if($topLiked->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Judul</th>
                    <th>Dilihat</th>
                    <th>Disukai</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topLiked as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ public_path('storage/' . $item->image) }}" alt="{{ $item->title }}" class="photo-thumb" onerror="this.style.display='none'">
                        @else
                            <div style="height: 50px; width: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 10px;">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->views_count }}</td>
                    <td>{{ \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count() }}</td>
                    <td>{{ $item->approved_comments_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada data.</p>
    @endif
    
    <div class="section-title">Semua Foto Galeri</div>
    @if($galleryItems->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Judul</th>
                    <th>Tanggal Diambil</th>
                    <th>Status</th>
                    <th>Dilihat</th>
                    <th>Disukai</th>
                    <th>Komentar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($galleryItems as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ public_path('storage/' . $item->image) }}" alt="{{ $item->title }}" class="photo-thumb" onerror="this.style.display='none'">
                        @else
                            <div style="height: 50px; width: 50px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 10px;">No Image</span>
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->taken_at ? $item->taken_at->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->status === 'published' ? 'Dipublikasikan' : 'Draft' }}</td>
                    <td>{{ $item->views_count }}</td>
                    <td>{{ \App\Models\GalleryReaction::where('gallery_item_id', $item->id)->where('type', 'like')->count() }}</td>
                    <td>{{ $item->approved_comments_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>Tidak ada foto.</p>
    @endif
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>Halaman ini dicetak secara otomatis oleh sistem</p>
    </div>
    
    <script>
        // Remove any remaining script tags for PDF generation
        window.print();
    </script>
</body>
</html>