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
@vite(['resources/assets/js/form-layouts.js'])
@endsection

@section('content')
<div class="col-xxl">
  <div class="card mb-6">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Basic Layout</h5> <small class="text-muted float-end">Default label</small>
    </div>
    <div class="card-body">
      <form id="formAccountSettings" method="POST"
            action="{{ isset($jadwal) ? route('admin-jadwal-kuliah-update', $jadwal->id) : route('admin-jadwal-kuliah-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($jadwal))
            @method('PUT')
        @endif

        <div class="row">
          <div class="col-md-6">
            <label class="form-label" for="kelas">Kelas</label>
            <div class="col-sm-12">
              <select name="kelas_id" id="kelas" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($kelas as $k)
                  <option value="{{ $k->id }}"{{ (isset($jadwal) && $jadwal->kelas_id == $k->id) ? 'selected' : '' }}>
                    {{ $k->pararel }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="dosen-id">Dosen</label>
            <div class="col-sm-12">
              <select name="dosen_id" id="dosen-id" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($dosen as $d)
                  <option value="{{ $d->id }}"{{ (isset($jadwal) && $jadwal->dosen_id == $d->id) ? 'selected' : '' }}>
                    {{ $d->nama }} - {{ $d->nip }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label" for="matakuliah">Mata Kuliah</label>
            <div class="col-sm-12">
              <select name="mk_id" id="matakuliah" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($matakuliah as $mk)
                  <option value="{{ $mk->id }}"{{ (isset($jadwal) && $jadwal->mk_id == $mk->id) ? 'selected' : '' }}>
                    {{ $mk->nama }} - {{ $mk->kode }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="ruangan">Ruangan</label>
            <div class="col-sm-12">
              <select name="ruangan_id" id="ruangan" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($ruangan as $r)
                  <option value="{{ $r->id }}"{{ (isset($jadwal) && $jadwal->ruangan_id == $r->id) ? 'selected' : '' }}>
                    {{ $r->nama }} - {{ $r->kode }} - {{ $r->gedung }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <label class="form-label" for="hari">Hari</label>
            <div class="col-sm-12">
              <select name="hari" id="hari" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                <option value="senin"{{ (isset($jadwal) && $jadwal->hari == 'senin') ? 'selected' : '' }}>Senin</option>
                <option value="selasa"{{ (isset($jadwal) && $jadwal->hari == 'selasa') ? 'selected' : '' }}>Selasa</option>
                <option value="rabu"{{ (isset($jadwal) && $jadwal->hari == 'rabu') ? 'selected' : '' }}>Rabu</option>
                <option value="kamis"{{ (isset($jadwal) && $jadwal->hari == 'kamis') ? 'selected' : '' }}>Kamis</option>
                <option value="jumat"{{ (isset($jadwal) && $jadwal->hari == 'jumat') ? 'selected' : '' }}>Jumat</option>
                <option value="sabtu"{{ (isset($jadwal) && $jadwal->hari == 'sabtu') ? 'selected' : '' }}>Sabtu</option>
                <option value="minggu"{{ (isset($jadwal) && $jadwal->hari == 'minggu') ? 'selected' : '' }}>Minggu</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="jam-mulai">Jam Mulai</label>
            <div class="col-sm-12">
              <input type="time" id="jam-mulai" name="jam_mulai" value="{{ $jadwal->jam_mulai ?? old('jam_mulai') }}" class="form-control flatpickr-basic" placeholder="Jam Mulai" />
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="jam-selesai">Jam Selesai</label>
            <div class="col-sm-12">
              <input type="time" id="jam-selesai" name="jam_selesai" value="{{ $jadwal->jam_selesai ?? old('jam_selesai') }}" class="form-control flatpickr-basic" placeholder="Jam Mulai" />
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
