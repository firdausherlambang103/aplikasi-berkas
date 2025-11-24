<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Gateway Manager</title>
    
    <!-- Menggunakan CDN Bootstrap 5 dan Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-wa {
            border: none;
            border-top: 4px solid #25D366; /* Warna Hijau WA */
            border-radius: 8px;
        }
        .qr-frame {
            width: 260px;
            height: 260px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .status-pill {
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }
        /* Animasi berkedip untuk status 'Menunggu Scan' */
        .pulse-anim {
            animation: pulse-yellow 2s infinite;
        }
        @keyframes pulse-yellow {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            
            <!-- KARTU UTAMA -->
            <div class="card card-wa shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="fab fa-whatsapp text-success me-2"></i> Device Manager
                    </h5>
                    <small id="last-updated" class="text-muted">Update: --:--:--</small>
                </div>
                
                <div class="card-body text-center p-4">
                    
                    <!-- BAGIAN INDIKATOR STATUS -->
                    <div class="mb-4">
                        <p class="text-muted mb-2 small fw-bold text-uppercase">Status Koneksi</p>
                        <div id="status-badge-container">
                            <span class="badge bg-secondary py-2 px-3">
                                <i class="fas fa-spinner fa-spin me-1"></i> Memuat...
                            </span>
                        </div>
                        <p class="mt-2 text-muted small" id="status-message">Menghubungi server...</p>
                    </div>

                    <!-- BAGIAN 1: AREA SCAN QR (Muncul jika belum login) -->
                    <div id="view-scan" class="d-none">
                        <div class="qr-frame mb-3">
                            <img id="qr-img" src="" alt="QR Code" class="img-fluid d-none" style="max-width: 100%; height: auto;">
                            <div id="qr-loading">
                                <div class="spinner-border text-success" role="status"></div>
                                <p class="mt-2 small text-muted">Mengambil QR Code...</p>
                            </div>
                        </div>
                        <div class="alert alert-light border small text-start">
                            <strong>Panduan:</strong>
                            <ol class="mb-0 ps-3">
                                <li>Buka WhatsApp di HP Anda.</li>
                                <li>Ketuk menu titik tiga (Android) atau Pengaturan (iOS).</li>
                                <li>Pilih <strong>Perangkat Tertaut</strong> > <strong>Tautkan Perangkat</strong>.</li>
                                <li>Arahkan kamera ke QR Code di atas.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- BAGIAN 2: AREA TERHUBUNG (Muncul jika sudah login) -->
                    <div id="view-connected" class="d-none">
                        <div class="py-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem; margin-bottom: 15px;"></i>
                            <h3 class="text-dark fw-bold">WhatsApp Terhubung!</h3>
                            <p class="text-muted mb-4">Sistem siap mengirim pesan notifikasi.</p>
                            
                            <div class="d-grid gap-2 col-8 mx-auto">
                                <button onclick="actionLogout()" class="btn btn-outline-danger">
                                    <i class="fas fa-power-off me-2"></i> Putuskan Koneksi (Logout)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 3: AREA ERROR (Muncul jika server mati) -->
                    <div id="view-error" class="d-none">
                        <div class="alert alert-danger text-start">
                            <div class="d-flex">
                                <div class="me-3"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                                <div>
                                    <h6 class="alert-heading fw-bold">Gagal Terhubung ke Node.js</h6>
                                    <p class="mb-0 small">Service WhatsApp Gateway tidak merespon. Pastikan aplikasi Node.js sudah dijalankan.</p>
                                </div>
                            </div>
                        </div>
                        <button onclick="checkStatus()" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync me-1"></i> Coba Lagi
                        </button>
                    </div>

                </div>
                
                <!-- DEBUG FOOTER (Untuk melihat data mentah) -->
                <div class="card-footer bg-light text-start">
                    <a class="text-decoration-none text-muted small" data-bs-toggle="collapse" href="#debugCollapse" role="button">
                        <i class="fas fa-code me-1"></i> Debug / Raw Response
                    </a>
                    <div class="collapse mt-2" id="debugCollapse">
                        <pre class="card card-body bg-dark text-success p-2 small mb-0" id="debug-json">Waiting...</pre>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- SCRIPT JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // --- KONFIGURASI URL ---
    const API = {
        status: "{{ route('wa.status') }}",
        qr: "{{ route('wa.qr') }}",
        logout: "{{ route('wa.logout') }}"
    };
    const CSRF = "{{ csrf_token() }}";

    $(document).ready(function() {
        // Jalankan cek status pertama kali
        checkStatus();
        
        // Loop cek status setiap 3 detik (Real-time feel)
        setInterval(checkStatus, 3000);
    });

    // Fungsi Mengambil Status dari Controller Laravel -> Node.js
    function checkStatus() {
        $.ajax({
            url: API.status,
            method: 'GET',
            timeout: 5000, // Batas waktu tunggu 5 detik
            success: function(response) {
                updateUI(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching status:", error);
                showErrorState();
                $('#debug-json').text("Error: " + status + " - " + error);
            }
        });
    }

    // Fungsi Mengupdate Tampilan Berdasarkan Data JSON
    function updateUI(data) {
        // Update Jam Terakhir
        const now = new Date();
        $('#last-updated').text('Update: ' + now.toLocaleTimeString());

        // Update Debug Box
        $('#debug-json').text(JSON.stringify(data, null, 2));

        // Normalisasi Status (antisipasi huruf kecil/besar)
        // Default ke 'UNKNOWN' jika data.status kosong
        let status = (data.status || 'UNKNOWN').toUpperCase(); 
        let userName = data.name || 'User';

        const badgeContainer = $('#status-badge-container');
        const msgContainer = $('#status-message');

        // Reset Tampilan Area
        $('#view-error').addClass('d-none');

        // --- LOGIKA PERCABANGAN STATUS ---

        // 1. JIKA TERHUBUNG (AUTHENTICATED / READY)
        if (status === 'AUTHENTICATED' || status === 'READY' || status === 'CONNECTED') {
            
            // Tampilkan Badge Hijau
            badgeContainer.html(`
                <span class="status-pill bg-success text-white">
                    <i class="fas fa-wifi me-1"></i> TERHUBUNG
                </span>
            `);
            msgContainer.html(`Login sebagai: <strong>${userName}</strong>`);

            // Tampilkan Area Terhubung
            $('#view-scan').addClass('d-none');
            $('#view-connected').removeClass('d-none');

        // 2. JIKA MINTA SCAN (QR_READY / DISCONNECTED)
        } else if (status === 'QR_READY' || status === 'DISCONNECTED' || status === 'UNPAIRED') {
            
            // Tampilkan Badge Kuning Berkedip
            badgeContainer.html(`
                <span class="status-pill bg-warning text-dark pulse-anim">
                    <i class="fas fa-qrcode me-1"></i> BUTUH SCAN
                </span>
            `);
            msgContainer.text('Sesi belum aktif. Silakan scan QR Code.');

            // Tampilkan Area Scan
            $('#view-connected').addClass('d-none');
            $('#view-scan').removeClass('d-none');

            // Panggil fungsi ambil gambar QR
            fetchQrCode();

        // 3. STATUS LAINNYA (STARTING, dll)
        } else {
            badgeContainer.html(`
                <span class="status-pill bg-secondary text-white">
                    <i class="fas fa-sync fa-spin me-1"></i> ${status}
                </span>
            `);
            msgContainer.text('Sedang memproses status...');
            
            $('#view-connected').addClass('d-none');
            $('#view-scan').addClass('d-none');
        }
    }

    // Fungsi Mengambil Gambar QR Code
    let isQrFetching = false;
    function fetchQrCode() {
        // Mencegah request numpuk
        if (isQrFetching) return;
        
        // Cek jika gambar sudah tampil dan status masih butuh scan, 
        // kita tidak perlu refresh gambar terus menerus kecuali src kosong
        if ($('#qr-img').attr('src') !== "" && !$('#qr-img').hasClass('d-none')) {
             // Opsional: Bisa return di sini jika QR statis
        }

        isQrFetching = true;
        $.get(API.qr, function(res) {
            if (res && res.qr) {
                $('#qr-loading').hide();
                $('#qr-img').attr('src', res.qr).removeClass('d-none');
            }
        }).always(function() {
            isQrFetching = false;
        });
    }

    // Fungsi Logout
    function actionLogout() {
        if(!confirm('Yakin ingin memutuskan koneksi WhatsApp?')) return;

        $('#status-badge-container').html('<span class="badge bg-danger">Memproses Logout...</span>');

        $.post(API.logout, { _token: CSRF })
            .done(function() {
                alert('Berhasil Logout. Halaman akan dimuat ulang.');
                location.reload();
            })
            .fail(function() {
                alert('Gagal melakukan logout. Cek koneksi server.');
            });
    }

    // Tampilan Jika Gagal Koneksi ke Laravel/Node
    function showErrorState() {
        $('#status-badge-container').html('<span class="badge bg-danger">OFFLINE</span>');
        $('#status-message').text('Koneksi terputus.');
        
        $('#view-scan').addClass('d-none');
        $('#view-connected').addClass('d-none');
        $('#view-error').removeClass('d-none');
    }
</script>

</body>
</html>