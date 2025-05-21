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
      <form id="formAccountSettings" method="POST" action="{{ route('admin-mahasiswa-store') }}" enctype="multipart/form-data">
        @csrf
        <h6>1. Account Details</h6>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label" for="email">Email</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="text" id="email" name="email" class="form-control" placeholder="adrian@it.student.pens.ac.id" aria-label="john.doe" aria-describedby="email2" />
                {{-- <span class="input-group-text" id="multicol-email2">@example.com</span> --}}
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="password">Password</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password2" />
                <span class="input-group-text cursor-pointer" id="password2"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-6 mx-n4" />
        <h6>2. Personal Information</h6>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="nrp">NRP</label>
            <div class="col-sm-12">
              <input name="nrp" type="text" class="form-control" id="nrp" placeholder="3123500038" />
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="nama">Nama</label>
            <div class="col-sm-12">
              <input name="nama" type="text" class="form-control" id="nama" placeholder="Denis Beban" />
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
                  <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kelas">Kelas</label>
            <div class="col-sm-12">
              <select name="kelas_id" id="kelas" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($kelas as $k)
                  <option value="{{ $k->id }}">{{ $k->pararel }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="jenis-kelamin">Jenis Kelamin</label>
            <div class="col-sm-12">
              <select name="jenis_kelamin" id="jenis-kelamin" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="agama">Agama</label>
            <div class="col-sm-12">
              <select name="agama" id="agama" class="select2 form-select" data-allow-clear="true">
                <option value="">Pilih Agama</option>
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Katolik">Katolik</option>
                <option value="Hindu">Hindu</option>
                <option value="Buddha">Buddha</option>
                <option value="Konghuchu">Konghuchu</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="telepon">Telepon</label>
            <div class="col-sm-12">
              <input name="telepon" type="text" id="telepon" class="form-control phone-mask" placeholder="0895301391873" aria-label="0895301391873" aria-describedby="basic-default-phone" />
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="semester">Semester</label>
            <div class="col-sm-12">
              <input name="semester" type="number" class="form-control" id="semester" placeholder="1" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="tanggal-lahir">Tanggal Lahir</label>
            <div class="col-sm-12">
              <input name="tanggal_lahir" type="text" id="tanggal-lahir" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="tanggal-masuk">Tanggal Masuk</label>
            <div class="col-sm-12">
              <input name="tanggal_masuk" type="text" id="tanggal-masuk" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="status">Status</label>
            <div class="col-sm-12">
              <select name="status" id="status" class="select2 form-select" data-allow-clear="true">
                <option value="">Pilih Status</option>
                <option value="Aktif">Aktif</option>
                <option value="Cuti">Cuti</option>
                <option value="Keluar">Keluar</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="alamat-jalan">Alamat Jalan</label>
            <div class="col-sm-12">
              <input name="alamat_jalan" type="text" class="form-control" id="alamat-jalan" placeholder="Jl. Raya Keputih" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="provinsi">Provinsi</label>
            <div class="col-sm-12">
              <select name="provinsi" id="provinsi" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                <option value="Jawa Timur">Jawa Timur</option>
                <option value="Jawa Tengah">Jawa Tengah</option>
                <option value="Jawa Barat">Jawa Barat</option>
                <option value="Bali">Bali</option>
                <option value="Banten">Banten</option>
                <option value="DKI Jakarta">DKI Jakarta</option>
                <option value="DI Yogyakarta">DI Yogyakarta</option>
                <option value="Sumatera Utara">Sumatera Utara</option>
                <option value="Sumatera Selatan">Sumatera Selatan</option>
                <option value="Kalimantan Timur">Kalimantan Timur</option>
                <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                <option value="Kalimantan Barat">Kalimantan Barat</option>
                <option value="Sulawesi Utara">Sulawesi Utara</option>
                <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                <option value="Sulawesi Barat">Sulawesi Barat</option>
                <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                <option value="Maluku">Maluku</option>
                <option value="Maluku Utara">Maluku Utara</option>
                <option value="Papua">Papua</option>
                <option value="Papua Barat">Papua Barat</option>
                <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kode-pos">Kode Pos</label>
            <div class="col-sm-12">
              <input name="kode_pos" type="text" class="form-control" id="kode-pos" placeholder="123456" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="negara">Country</label>
            <div class="col-sm-12">
              <select name="negara" id="negara" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                <option value="Australia">Australia</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Belarus">Belarus</option>
                <option value="Brazil">Brazil</option>
                <option value="Canada">Canada</option>
                <option value="China">China</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
                <option value="India">India</option>
                <option value="India">India</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Italy">Italy</option>
                <option value="Japan">Japan</option>
                <option value="Korea">Korea, Republic of</option>
                <option value="Mexico">Mexico</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Philippines">Philippines</option>
                <option value="Russia">Russian Federation</option>
                <option value="South Africa">South Africa</option>
                <option value="Thailand">Thailand</option>
                <option value="Turkey">Turkey</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Emirates">United Arab Emirates</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
              </select>
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kelurahan">Kelurahan</label>
            <div class="col-sm-12">
              <input name="kelurahan" type="text" class="form-control" id="kelurahan" placeholder="Desa Keputih" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kecamatan">Kecamatan</label>
            <div class="col-sm-12">
              <input name="kecamatan" type="text" class="form-control" id="kecamatan" placeholder="Sukolilo" />
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kota">Kota</label>
            <div class="col-sm-12">
              <input name="kota" type="text" class="form-control" id="kota" placeholder="Surabaya" />
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-6 d-grid">
            <button type="submit" class="btn btn-primary">Kirim</button>
          </div>
          <div class="col-md-6 d-grid">
            <a href="{{ route('admin-mahasiswa-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
