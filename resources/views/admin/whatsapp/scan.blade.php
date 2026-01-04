<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Gateway Manager</title>
    
    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f4f6f9; font-family: sans-serif; }
        .card-wa { border-top: 4px solid #25D366; border-radius: 8px; }
        .status-pill { padding: 8px 20px; border-radius: 50px; font-weight: bold; font-size: 0.9rem; }
        .qr-frame { 
            width: 260px; height: 260px; margin: 0 auto; 
            border: 1px solid #ddd; background: #fff; 
            display: flex; align-items: center; justify-content: center; 
        }
        /* Animasi */
        .pulse-warning { animation: pulse-yellow 2s infinite; }
        @keyframes pulse-yellow {
            0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }
        pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            
            <div class="card card-wa shadow">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fab fa-whatsapp text-success me-2"></i> Device Manager</h5>
                    <small id="last-check" class="text-muted" style="font-size: 0.75rem">Updated: -</small>
                </div>
                
                <div class="card-body text-center p-4">
                    
                    <!-- INDIKATOR STATUS -->
                    <div class="mb-4">
                        <div id="status-badge-container">
                            <span class="badge bg-secondary py-2 px-3">Memuat...</span>
                        </div>
                        <p class="mt-2 text-muted small" id="status-message">Menghubungi server...</p>
                    </div>

                    <!-- VIEW 1: SCAN QR -->
                    <div id="view-scan" class="d-none">
                        <div class="qr-frame mb-3">
                            <img id="qr-img" src="" class="img-fluid d-none">
                            <div id="qr-loading">
                                <div class="spinner-border text-success" role="status"></div>
                                <p class="small mt-2">Mengambil QR...</p>
                            </div>
                        </div>
                        <div class="alert alert-light border small text-start">
                            <strong>Tips:</strong> Buka WA di HP > Perangkat Tertaut > Tautkan Perangkat.
                        </div>
                    </div>

                    <!-- VIEW 2: TERHUBUNG -->
                    <div id="view-connected" class="d-none">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        <h3 class="mt-3">Terhubung!</h3>
                        <p class="text-muted">Siap mengirim pesan.</p>
                        <button onclick="actionLogout()" class="btn btn-outline-danger mt-2">Logout</button>
                    </div>

                    <!-- VIEW 3: ERROR -->
                    <div id="view-error" class="d-none">
                        <div class="alert alert-danger" id="error-text">Gagal terhubung ke Node.js Service.</div>
                        <button onclick="location.reload()" class="btn btn-sm btn-secondary">Refresh</button>
                    </div>

                </div>
                
                <!-- DEBUG / RAW RESPONSE (Penting untuk diagnosa) -->
                <div class="card-footer bg-light text-start">
                    <a class="text-decoration-none text-muted small" data-bs-toggle="collapse" href="#debugCollapse">
                        <i class="fas fa-code me-1"></i> Debug / Raw Response
                    </a>
                    <div class="collapse mt-2" id="debugCollapse">
                        <small class="text-muted d-block mb-1">Respon mentah dari server:</small>
                        <pre class="bg-dark text-warning p-2 small mb-0 rounded" id="debug-json">Waiting...</pre>
                        <div id="fix-hint" class="alert alert-info mt-2 small d-none">
                            <strong><i class="fas fa-lightbulb"></i> Saran Perbaikan:</strong><br>
                            Node.js mengirim objek kosong <code>{}</code>. Pastikan kode Node.js Anda bagian <code>/status</code> mengembalikan JSON yang benar.
                            <br>Contoh: <code>res.json({ status: 'AUTHENTICATED' });</code>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const API = {
        status: "{{ route('wa.status') }}",
        qr: "{{ route('wa.qr') }}",
        logout: "{{ route('wa.logout') }}"
    };
    const CSRF = "{{ csrf_token() }}";

    $(document).ready(function() {
        checkStatus();
        setInterval(checkStatus, 3000);
    });

    function checkStatus() {
        $.ajax({
            url: API.status,
            method: 'GET',
            timeout: 5000,
            success: function(response) {
                updateUI(response);
            },
            error: function(xhr, status, error) {
                $('#status-badge-container').html('<span class="badge bg-danger">OFFLINE</span>');
                $('#status-message').text('Node.js Service mati atau tidak merespon.');
                $('#debug-json').text("Error: " + status + " - " + error);
                $('#last-check').text('Last: ' + new Date().toLocaleTimeString());
            }
        });
    }

    function updateUI(data) {
        $('#last-check').text('Last: ' + new Date().toLocaleTimeString());
        
        // 1. Tampilkan Data Mentah
        const jsonString = JSON.stringify(data, null, 2);
        $('#debug-json').text(jsonString);

        // 2. DETEKSI RESPON KOSONG (Kasus Anda saat ini)
        if ($.isEmptyObject(data)) {
            const badge = $('#status-badge-container');
            const msg = $('#status-message');
            
            badge.html('<span class="status-pill bg-danger text-white">DATA KOSONG</span>');
            msg.html('<span class="text-danger">Server Node.js aktif, tapi mengirim data kosong ({}).</span>');
            
            // Tampilkan hint perbaikan
            $('#debugCollapse').addClass('show');
            $('#fix-hint').removeClass('d-none');
            
            $('#view-scan, #view-connected').addClass('d-none');
            return;
        }

        // 3. Normalisasi Status
        let rawStatus = data.status || data.state || data.info || 'UNKNOWN';
        let status = rawStatus.toString().toUpperCase();
        let userName = data.name || data.pushname || 'User';

        const badge = $('#status-badge-container');
        const msg = $('#status-message');

        $('#view-error').addClass('d-none');
        $('#fix-hint').addClass('d-none'); // Sembunyikan hint jika data tidak kosong

        // --- KONDISI: TERHUBUNG ---
        if (['AUTHENTICATED', 'READY', 'CONNECTED', 'OPEN'].includes(status)) {
            badge.html('<span class="status-pill bg-success text-white"><i class="fas fa-wifi"></i> TERHUBUNG</span>');
            msg.html('Login sebagai: <b>' + userName + '</b>');
            
            $('#view-scan').addClass('d-none');
            $('#view-connected').removeClass('d-none');
            $('#debugCollapse').removeClass('show'); // Tutup debug kalau sukses

        // --- KONDISI: SCAN QR ---
        } else if (['QR_READY', 'DISCONNECTED', 'UNPAIRED', 'SCAN_QR'].includes(status)) {
            badge.html('<span class="status-pill bg-warning text-dark pulse-warning"><i class="fas fa-qrcode"></i> SCAN QR</span>');
            msg.text('Menunggu scan...');
            
            $('#view-connected').addClass('d-none');
            $('#view-scan').removeClass('d-none');
            
            fetchQr();

        // --- KONDISI: STARTING / UNKNOWN ---
        } else {
            badge.html('<span class="status-pill bg-info text-white">' + status + '</span>');
            msg.text('Status server: ' + status);
            
            $('#view-scan, #view-connected').addClass('d-none');
        }
    }

    let isQrFetching = false;
    function fetchQr() {
        if (isQrFetching) return;
        if ($('#qr-img').attr('src') && !$('#qr-img').hasClass('d-none')) return;

        isQrFetching = true;
        $.get(API.qr, function(res) {
            if (res && res.qr) {
                $('#qr-loading').hide();
                $('#qr-img').attr('src', res.qr).removeClass('d-none');
            }
        }).always(() => { isQrFetching = false; });
    }

    function actionLogout() {
        if(!confirm('Logout?')) return;
        $.post(API.logout, { _token: CSRF }).done(() => location.reload());
    }
</script>

</body>
</html>