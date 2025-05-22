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
            action="{{ isset($dosen) ? route('admin-dosen-update', $dosen->id) : route('admin-dosen-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($dosen))
            @method('PUT')
        @endif

        <h6>1. Account Details</h6>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label" for="email">Email</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="text" id="email" name="email" value="{{ $dosen->email ?? old('email') }}" class="form-control" placeholder="adrian@it.student.pens.ac.id" aria-label="john.doe" aria-describedby="email2" />
                {{-- <span class="input-group-text" id="multicol-email2">@example.com</span> --}}
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="password">Password</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" {{ isset($dosen) ? '' : 'required' }} />
                <span class="input-group-text cursor-pointer" id="password2"><i class="ti ti-eye-off"></i></span>
              </div>
              @if(isset($dosen))
                <small class="text-muted">Leave blank to keep current password</small>
              @endif
            </div>
          </div>
        </div>
        <hr class="my-6 mx-n4" />
        <h6>2. Personal Information</h6>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="nip">NIP</label>
            <div class="col-sm-12">
              <input name="nip" value="{{ $dosen->nip ?? old('nip') }}" type="text" class="form-control" id="nip" placeholder="19800515200501001" />
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="nama">Nama</label>
            <div class="col-sm-12">
              <input name="nama" value="{{ $dosen->nama ?? old('nama') }}" type="text" class="form-control" id="nama" placeholder="Denis Beban" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="prodi">Program Studi</label>
            <div class="col-sm-12">
              <select name="prodi_id" id="prodi" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($program_studi as $prodi)
                  <option value="{{ $prodi->id }}"
                          {{ (isset($dosen) && $dosen->prodi_id == $prodi->id) ? 'selected' : '' }}>
                    {{ $prodi->nama }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="jenis-kelamin">Jenis Kelamin</label>
            <div class="col-sm-12">
              <select name="jenis_kelamin" id="jenis-kelamin" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                <option value="L" {{ (isset($dosen) && $dosen->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ (isset($dosen) && $dosen->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="telepon">Telepon</label>
            <div class="col-sm-12">
              <input name="telepon" value="{{ $dosen->telepon ?? old('telepon') }}" type="text" id="telepon" class="form-control phone-mask" placeholder="0895301391873" aria-label="0895301391873" aria-describedby="basic-default-phone" />
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="jabatan">Jabatan</label>
            <div class="col-sm-12">
              <select name="jabatan" id="jabatan" class="select2 form-select" data-allow-clear="true">
                <option value="">Pilih Jabatan</option>
                <option value="Dosen" {{ (isset($dosen) && $dosen->jabatan == 'Dosen') ? 'selected' : '' }}>Dosen</option>
                <option value="Asisten Ahli" {{ (isset($dosen) && $dosen->jabatan == 'Asisten Ahli') ? 'selected' : '' }}>Asisten Ahli</option>
                <option value="Lektor" {{ (isset($dosen) && $dosen->jabatan == 'Lektor') ? 'selected' : '' }}>Lektor</option>
                <option value="Lektor Kepala" {{ (isset($dosen) && $dosen->jabatan == 'Lektor Kepala') ? 'selected' : '' }}>Lektor Kepala</option>
                <option value="Profesor" {{ (isset($dosen) && $dosen->jabatan == 'Profesor') ? 'selected' : '' }}>Profesor</option>
                <option value="Ketua Program Studi" {{ (isset($dosen) && $dosen->jabatan == 'Ketua Program Studi') ? 'selected' : '' }}>Ketua Program Studi</option>
                <option value="Sekretaris Program Studi" {{ (isset($dosen) && $dosen->jabatan == 'Sekretaris Program Studi') ? 'selected' : '' }}>Sekretaris Program Studi</option>
                <option value="Kepala Laboratorium" {{ (isset($dosen) && $dosen->jabatan == 'Kepala Laboratorium') ? 'selected' : '' }}>Kepala Laboratorium</option>
                <option value="Wakil Dekan" {{ (isset($dosen) && $dosen->jabatan == 'Wakil Dekan') ? 'selected' : '' }}>Wakil Dekan</option>
                <option value="Dekan" {{ (isset($dosen) && $dosen->jabatan == 'Dekan') ? 'selected' : '' }}>Dekan</option>
                <option value="Dosen Tamu" {{ (isset($dosen) && $dosen->jabatan == 'Dosen Tamu') ? 'selected' : '' }}>Dosen Tamu</option>
                <option value="Dosen Luar Biasa" {{ (isset($dosen) && $dosen->jabatan == 'Dosen Luar Biasa') ? 'selected' : '' }}>Dosen Luar Biasa</option>
                <option value="Dosen Tetap" {{ (isset($dosen) && $dosen->jabatan == 'Dosen Tetap') ? 'selected' : '' }}>Dosen Tetap</option>
                <option value="Dosen Tidak Tetap" {{ (isset($dosen) && $dosen->jabatan == 'Dosen Tidak Tetap') ? 'selected' : '' }}>Dosen Tidak Tetap</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-6">
            <label class="form-label" for="tanggal-lahir">Tanggal Lahir</label>
            <div class="col-sm-12">
              <input name="tanggal_lahir" value="{{ $dosen->tanggal_lahir ?? old('tanggal_lahir') }}" type="text" id="tanggal-lahir" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
            </div>
          </div>
          <div class="col-md-4 mb-6">
            <label class="form-label" for="golongan_akhir">Golongan Akhir</label>
            <div class="col-sm-12">
              <select name="golongan_akhir" id="golongan_akhir" class="select2 form-select" data-allow-clear="true">
                <option value="">Pilih Golongan Akhir</option>
                <option value="II/a" {{ (isset($dosen) && $dosen->golongan_akhir == 'II/a') ? 'selected' : '' }}>II/a</option>
                <option value="II/b" {{ (isset($dosen) && $dosen->golongan_akhir == 'II/b') ? 'selected' : '' }}>II/b</option>
                <option value="II/c" {{ (isset($dosen) && $dosen->golongan_akhir == 'II/c') ? 'selected' : '' }}>II/c</option>
                <option value="II/d" {{ (isset($dosen) && $dosen->golongan_akhir == 'II/d') ? 'selected' : '' }}>II/d</option>
                <option value="III/a" {{ (isset($dosen) && $dosen->golongan_akhir == 'III/a') ? 'selected' : '' }}>III/a</option>
                <option value="III/b" {{ (isset($dosen) && $dosen->golongan_akhir == 'III/b') ? 'selected' : '' }}>III/b</option>
                <option value="III/c" {{ (isset($dosen) && $dosen->golongan_akhir == 'III/c') ? 'selected' : '' }}>III/c</option>
                <option value="III/d" {{ (isset($dosen) && $dosen->golongan_akhir == 'III/d') ? 'selected' : '' }}>III/d</option>
                <option value="IV/a" {{ (isset($dosen) && $dosen->golongan_akhir == 'IV/a') ? 'selected' : '' }}>IV/a</option>
                <option value="IV/b" {{ (isset($dosen) && $dosen->golongan_akhir == 'IV/b') ? 'selected' : '' }}>IV/b</option>
                <option value="IV/c" {{ (isset($dosen) && $dosen->golongan_akhir == 'IV/c') ? 'selected' : '' }}>IV/c</option>
                <option value="IV/d" {{ (isset($dosen) && $dosen->golongan_akhir == 'IV/d') ? 'selected' : '' }}>IV/d</option>
                <option value="IV/e" {{ (isset($dosen) && $dosen->golongan_akhir == 'IV/e') ? 'selected' : '' }}>IV/e</option>
                <option value="Non-PNS" {{ (isset($dosen) && $dosen->golongan_akhir == 'Non-PNS') ? 'selected' : '' }}>Non-PNS</option>
              </select>
            </div>
          </div>
          <div class="col-md-4 mb-6">
            <label class="form-label" for="is_wali">Status Wali</label>
            <div class="col-sm-12">
              <select name="is_wali" id="is_wali" class="select2 form-select" data-allow-clear="true">
                <option value="">Wali atau Tidak</option>
                <option value="1" {{ (isset($dosen) && $dosen->is_wali == 1) ? 'selected' : '' }}>Wali</option>
                <option value="0" {{ (isset($dosen) && $dosen->is_wali == 0) ? 'selected' : '' }}>Tidak Wali</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-6 d-grid">
            <button type="submit" class="btn btn-primary">Kirim</button>
          </div>
          <div class="col-md-6 d-grid">
            <a href="{{ route('admin-dosen-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
