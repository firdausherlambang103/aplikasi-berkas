@extends('adminlte::page')

@section('title', 'Registrasi Berkas Baru')

@section('content_header')
    <h1>Registrasi Berkas Baru</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('berkas.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kode Klien (Opsional)</label>
                        <select id="kode_klien_select" class="form-control">
                            <option value="">-- Pilih Kode --</option>
                            @foreach($klienTersedia as $klien)
                                <option value="{{ $klien->kode_klien }}" data-id="{{ $klien->id }}">
                                    {{ $klien->kode_klien }} ({{ $klien->nama_klien }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="klien_id" id="klien_id">
                    </div>

                    <div class="form-group">
                        <label>Nomer WA (Otomatis)</label>
                        <input type="text" id="nomer_wa" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jenis Hak</label>
                        <select name="jenis_hak" class="form-control" required>
                            <option value="SHM">SHM</option>
                            <option value="SHGB">SHGB</option>
                            <option value="SHW">SHW</option>
                            <option value="SHP">SHP</option>
                            <option value="Leter C">Leter C</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nomer Hak</label>
                        <input type="text" name="nomer_hak" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Desa</label>
                        <input type="text" name="desa" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Permohonan</label>
                        <input type="text" name="jenis_permohonan" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>SPA</label>
                        <textarea name="spa" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Alih Media</label>
                        <textarea name="alih_media" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="8"></textarea>
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
<script>
    $(document).ready(function() {
        $('#kode_klien_select').on('change', function() {
            var kode = $(this).val();

            // Dapatkan klien_id dari atribut data
            var klienId = $(this).find('option:selected').data('id');

            // Set input tersembunyi
            $('#klien_id').val(klienId);

            if (kode) {
                // Panggil API kita
                fetch(`/api/klien/get-by-kode/${kode}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            $('#nomer_wa').val(data.nomer_wa);
                        } else {
                            $('#nomer_wa').val('Kode tidak ditemukan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        $('#nomer_wa').val('Gagal memuat');
                    });
            } else {
                $('#nomer_wa').val(''); // Kosongkan jika tidak ada kode
                $('#klien_id').val(''); // Kosongkan ID
            }
        });
    });
</script>
@stop