@extends('layouts.app')

@section('title', 'Data Siswa - Galeri Sekolah Enay')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Data Siswa</h1>
            
            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="class-filter" class="form-label">Filter Kelas:</label>
                            <select class="form-select" id="class-filter">
                                <option value="">Semua Kelas</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="status-filter" class="form-label">Status:</label>
                            <select class="form-select" id="status-filter">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                                <option value="graduated">Lulus</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search-input" class="form-label">Cari Siswa:</label>
                            <input type="text" class="form-control" id="search-input" placeholder="Nama atau NIS...">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Students List -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Siswa</h5>
                    <span class="badge bg-primary" id="total-students">0 siswa</span>
                </div>
                <div class="card-body">
                    <div id="students-container">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentPage = 1;
    let currentClass = '';
    let currentStatus = '';
    let currentSearch = '';
    
    // Load initial data
    loadClasses();
    loadStudents();
    
    // Event listeners
    $('#class-filter').on('change', function() {
        currentClass = $(this).val();
        currentPage = 1;
        loadStudents();
    });
    
    $('#status-filter').on('change', function() {
        currentStatus = $(this).val();
        currentPage = 1;
        loadStudents();
    });
    
    $('#search-input').on('keyup', debounce(function() {
        currentSearch = $(this).val();
        currentPage = 1;
        loadStudents();
    }, 500));
});

function loadClasses() {
    $.get(API_BASE_URL + '/class-rooms')
        .done(function(response) {
            if (response.success) {
                const classSelect = $('#class-filter');
                response.data.data.forEach(function(classRoom) {
                    classSelect.append(`<option value="${classRoom.id}">${classRoom.name}</option>`);
                });
            }
        })
        .fail(function() {
            console.log('Failed to load classes');
        });
}

function loadStudents() {
    const params = new URLSearchParams({
        page: currentPage
    });
    
    if (currentClass) {
        params.append('class_room_id', currentClass);
    }
    
    if (currentStatus) {
        params.append('status', currentStatus);
    }
    
    if (currentSearch) {
        params.append('search', currentSearch);
    }
    
    $.get(API_BASE_URL + '/students?' + params.toString())
        .done(function(response) {
            if (response.success) {
                displayStudents(response.data);
                updateTotalCount(response.data.total);
            }
        })
        .fail(function() {
            $('#students-container').html('<div class="alert alert-danger">Gagal memuat data siswa.</div>');
        });
}

function displayStudents(data) {
    let html = '';
    
    if (data.data.length === 0) {
        html = '<div class="alert alert-info">Tidak ada data siswa yang ditemukan.</div>';
    } else {
        html = '<div class="row">';
        
        data.data.forEach(function(student) {
            const statusClass = getStatusClass(student.status);
            const statusText = getStatusText(student.status);
            const genderIcon = student.gender === 'male' ? 'fas fa-male' : 'fas fa-female';
            const genderColor = student.gender === 'male' ? 'text-primary' : 'text-pink';
            
            html += `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                ${student.photo ? 
                                    `<img src="${student.photo}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;" alt="${student.name}">` :
                                    `<div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                        <i class="${genderIcon} fa-2x ${genderColor}"></i>
                                    </div>`
                                }
                            </div>
                            <h5 class="card-title">${student.name}</h5>
                            <p class="card-text text-muted">NIS: ${student.student_number}</p>
                            <p class="card-text">
                                <i class="fas fa-graduation-cap me-1"></i>
                                ${student.class_room ? student.class_room.name : 'Belum ada kelas'}
                            </p>
                            <span class="badge ${statusClass}">${statusText}</span>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Email</small><br>
                                    <small>${student.email}</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Telepon</small><br>
                                    <small>${student.phone || '-'}</small>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Jenis Kelamin</small><br>
                                    <small><i class="${genderIcon} ${genderColor}"></i></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
    }
    
    $('#students-container').html(html);
}

function updateTotalCount(total) {
    $('#total-students').text(`${total} siswa`);
}

function getStatusClass(status) {
    const classes = {
        'active': 'bg-success',
        'inactive': 'bg-warning',
        'graduated': 'bg-info'
    };
    return classes[status] || 'bg-secondary';
}

function getStatusText(status) {
    const texts = {
        'active': 'Aktif',
        'inactive': 'Tidak Aktif',
        'graduated': 'Lulus'
    };
    return texts[status] || 'Unknown';
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>
@endsection
