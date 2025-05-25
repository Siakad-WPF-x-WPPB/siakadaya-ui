@extends('layouts/layoutMaster')

@section('title', ' Horizontal Layouts - Forms')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/select2/select2.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/cleavejs/cleave.js',
  'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/select2/select2.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/form-layouts.js', 'resources/assets/js/admin/form-jadwal.js'])
  @if(isset($jadwal) && $jadwal->kelas && $jadwal->kelas->prodi_id)
    <script>
        var jadwalProdiId = '{{ $jadwal->kelas->prodi_id }}';
        var jadwalKelasId = '{{ $jadwal->kelas->id }}';
        var jadwalMatakuliahId = '{{ $jadwal->mk_id }}';
    </script>
  @endif
@endsection

@section('content')
<div class="col-xxl">
  <div class="card mb-6">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Form Jadwal Kuliah</h5> 
      {{-- <small class="text-muted float-end">Default label</small> --}}
    </div>
    <div class="card-body">
      <form id="formAccountSettings" method="POST"
            action="{{ isset($jadwal) ? route('admin-jadwal-kuliah-update', $jadwal->id) : route('admin-jadwal-kuliah-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($jadwal))
            @method('PUT')
        @endif

        <div class="row mb-6">
          <div class="col-md-12">
            <label class="form-label" for="tahun-ajar">Tahun Ajar <span class="text-danger">*</span></label>
            <div class="col-sm-12">
              <select name="tahun_ajar_id" id="tahun-ajar" class="select2 form-select @error('tahun_ajar_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Pilih Tahun Ajar</option>
                @foreach($tahunAjar->where('status', 'Aktif') as $ta)
                  <option value="{{ $ta->id }}"
                          {{ (isset($jadwal) && $jadwal->tahun_ajar_id == $ta->id) || old('tahun_ajar_id') == $ta->id ? 'selected' : '' }}>
                    {{ $ta->semester }} {{ $ta->tahun_mulai }}/{{ $ta->tahun_akhir }}
                    @if($ta->status == 'Aktif')
                      <span class="badge bg-success ms-2">Aktif</span>
                    @endif
                  </option>
                @endforeach
              </select>
              @error('tahun_ajar_id')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
              <small class="form-text text-muted">Hanya tahun ajar dengan status aktif yang dapat dipilih</small>
            </div>
          </div>
        </div>

        <div class="row mb-6">
          <div class="col-md-6">
            <label class="form-label" for="prodi">Program Studi</label>
            <div class="col-sm-12">
              <select name="prodi_id" id="prodi" class="select2 form-select @error('prodi_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Pilih Program Studi</option>
                @foreach($prodi as $ps)
                  <option value="{{ $ps->id }}"
                          {{ (isset($jadwal) && $jadwal->kelas && $jadwal->kelas->prodi_id == $ps->id) || old('prodi_id') == $ps->id ? 'selected' : '' }}>
                    {{ $ps->nama }}
                  </option>
                @endforeach
              </select>
              @error('prodi_id')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="kelas">Kelas</label>
            <div class="col-sm-12">
              <select name="kelas_id" id="kelas" class="select2 form-select @error('kelas_id') is-invalid @enderror" data-allow-clear="true" disabled>
                <option value="">Select value</option>
                @if(isset($jadwal) && $jadwal->kelas)
                  <option value="{{ $jadwal->kelas->id }}" selected>
                    {{ $jadwal->kelas->pararel }}
                  </option>
                @endif
              </select>
              @error('kelas_id')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row mb-6">
          <div class="col-md-4">
            <label class="form-label" for="dosen-id">Dosen</label>
            <div class="col-sm-12">
              <select name="dosen_id" id="dosen-id" class="select2 form-select @error('dosen_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($dosen as $d)
                  <option value="{{ $d->id }}"
                          {{ (isset($jadwal) && $jadwal->dosen_id == $d->id) || old('dosen_id') == $d->id ? 'selected' : '' }}>
                    {{ $d->nama }} - {{ $d->nip }}
                  </option>
                @endforeach
              </select>
              @error('dosen_id')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="matakuliah">Mata Kuliah</label>
            <div class="col-sm-12">
              <select name="mk_id"
                id="matakuliah"
                class="select2 form-select @error('mk_id') is-invalid @enderror"
                data-allow-clear="true"
                disabled>
                  <option value="">Select value</option>
                  @if(isset($jadwal) && $jadwal->matakuliah)
                      <option value="{{ $jadwal->matakuliah->id }}" selected>
                          {{ $jadwal->matakuliah->nama }} - {{ $jadwal->matakuliah->kode }}
                      </option>
                  @endif
              </select>
              @error('mk_id')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="ruangan">Ruangan</label>
            <div class="col-sm-12">
              <select name="ruangan_id" id="ruangan" class="select2 form-select @error('ruangan_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($ruangan as $r)
                  <option value="{{ $r->id }}"
                          {{ (isset($jadwal) && $jadwal->ruangan_id == $r->id) || old('ruangan_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->nama }} - {{ $r->kode }} - {{ $r->gedung }}
                  </option>
                @endforeach
              </select>
              @error('ruangan_id')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row mb-6">
          <div class="col-md-4">
            <label class="form-label" for="hari">Hari</label>
            <div class="col-sm-12">
              <select name="hari" id="hari" class="select2 form-select @error('hari') is-invalid @enderror" data-allow-clear="true">
                  <option value="">Pilih Hari</option>
                  <option value="Senin" {{ (isset($jadwal) && $jadwal->hari == 'Senin') || old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                  <option value="Selasa" {{ (isset($jadwal) && $jadwal->hari == 'Selasa') || old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                  <option value="Rabu" {{ (isset($jadwal) && $jadwal->hari == 'Rabu') || old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                  <option value="Kamis" {{ (isset($jadwal) && $jadwal->hari == 'Kamis') || old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                  <option value="Jumat" {{ (isset($jadwal) && $jadwal->hari == 'Jumat') || old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                  <option value="Sabtu" {{ (isset($jadwal) && $jadwal->hari == 'Sabtu') || old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                  <option value="Minggu" {{ (isset($jadwal) && $jadwal->hari == 'Minggu') || old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
              </select>
              @error('hari')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="jam-mulai">Jam Mulai</label>
            <div class="col-sm-12">
              <input type="time" id="jam-mulai" name="jam_mulai" value="{{ $jadwal->jam_mulai ?? old('jam_mulai') }}" class="form-control flatpickr-basic @error('jam_mulai') is-invalid @enderror" placeholder="Jam Mulai" />
              @error('jam_mulai')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="jam-selesai">Jam Selesai</label>
            <div class="col-sm-12">
              <input type="time" id="jam-selesai" name="jam_selesai" value="{{ $jadwal->jam_selesai ?? old('jam_selesai') }}" class="form-control flatpickr-basic @error('jam_selesai') is-invalid @enderror" placeholder="Jam Mulai" />
              @error('jam_selesai')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-6 d-grid">
            <button type="submit" class="btn btn-primary">Kirim</button>
          </div>
          <div class="col-md-6 d-grid">
            <a href="{{ route('admin-jadwal-kuliah-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
