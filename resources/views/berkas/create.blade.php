@extends('adminlte::page')

@section('title', 'Registrasi Berkas Baru')

@section('content_header')
    <h1>Registrasi Berkas Baru</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('berkas.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nomer Berkas (Internal)</label>
                        <input type="text" name="nomer_berkas" class="form-control" placeholder="Contoh: 2025/001" value="{{ old('nomer_berkas') }}">
                    </div>

                    <div class="form-group">
                        <label>Kode Klien (Opsional - Auto-fill WA)</label>
                        {{-- Dropdown Klien --}}
                        <select id="kode_klien_select" name="klien_id" class="form-control">
                            <option value="">-- Pilih Kode (untuk auto-fill) --</option>
                            
                            {{-- PERBAIKAN 1: Menambahkan 'data-nomer-wa' --}}
                            @foreach($klienTersedia as $klien)
                                <option value="{{ $klien->id }}" 
                                        data-nomer-wa="{{ $klien->nomer_wa }}" 
                                        {{ old('klien_id') == $klien->id ? 'selected' : '' }}>
                                    {{ $klien->kode_klien }} ({{ $klien->nama_klien }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label>Nama Pemohon (Wajib)</label>
                        <input type="text" name="nama_pemohon" class="form-control" placeholder="Masukkan nama pemohon" value="{{ old('nama_pemohon') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nomer WA Pemohon</label>
                        {{-- Input ini akan diisi otomatis oleh JS di bawah --}}
                        <input type="text" name="nomer_wa" id="nomer_wa" class="form-control" placeholder="Cth: 628123456789 (Pilih Klien atau isi manual)" value="{{ old('nomer_wa') }}">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Jenis Hak</label>
                        <select name="jenis_hak" class="form-control" required>
                            <option value="">-- Pilih Jenis Hak --</option>
                            <option value="SHM" {{ old('jenis_hak') == 'SHM' ? 'selected' : '' }}>SHM</option>
                            <option value="SHGB" {{ old('jenis_hak') == 'SHGB' ? 'selected' : '' }}>SHGB</option>
                            <option value="SHW" {{ old('jenis_hak') == 'SHW' ? 'selected' : '' }}>SHW</option>
                            <option value="SHP" {{ old('jenis_hak') == 'SHP' ? 'selected' : '' }}>SHP</option>
                            <option value="Leter C" {{ old('jenis_hak') == 'Leter C' ? 'selected' : '' }}>Leter C</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Nomer Hak</label>
                        <input type="text" name="nomer_hak" class="form-control" value="{{ old('nomer_hak') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control" value="{{ old('desa') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan</label>
                        <input type="text" name="jenis_permohonan" class="form-control" value="{{ old('jenis_permohonan') }}" required>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>SPA</label>
                        <textarea name="spa" class="form-control" rows="3">{{ old('spa') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        <textarea name="alih_media" class="form-control" rows="3">{{ old('alih_media') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="8">{{ old('keterangan') }}</textarea>
                    </div>

                    <hr>
                    {{-- FIELD BARU UNTUK PEMBAYARAN --}}
                    <div class="form-group">
                        <label>Kode Billing</label>
                        <input type="text" name="kode_biling" class="form-control" value="{{ old('kode_biling') }}">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Bayar (Hanya Angka)</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar') }}" placeholder="Contoh: 500000">
                    </div>

                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary">Simpan Registrasi</button>
        </form>
    </div>
</div>
@stop

@section('js')
{{-- PERBAIKAN 2: Mengganti logika JS menjadi lebih stabil (tanpa fetch API) --}}
<script>
    $(document).ready(function() {
        // Fungsi ini akan berjalan setiap kali dropdown Klien (#kode_klien_select) berubah
        $('#kode_klien_select').on('change', function() {
            
            // 1. Ambil <option> yang sedang dipilih
            var selectedOption = $(this).find('option:selected');
            
            // 2. Ambil nilai dari atribut 'data-nomer-wa' yang tadi kita tambahkan di HTML
            //    Gunakan || '' untuk memastikan nilainya string kosong jika atribut tidak ada/kosong
            var nomerWa = selectedOption.data('nomer-wa') || '';

            // 3. Masukkan nilai nomerWa ke input Nomer WA (#nomer_wa)
            $('#nomer_wa').val(nomerWa);
        });

        // (Tambahan Opsional)
        // Jika halaman dimuat ulang (misal karena error validasi) dan ada Klien yang
        // sudah terpilih (dari 'old()'), kita picu 'change' agar Nomer WA-nya
        // langsung terisi saat halaman dimuat.
        if ($('#kode_klien_select').val() != "") {
            $('#kode_klien_select').trigger('change');
        }
    });
</script>
@stop