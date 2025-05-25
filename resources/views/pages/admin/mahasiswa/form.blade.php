@extends('layouts/layoutMaster')

@section('title', ' Horizontal Layouts - Forms')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/form-layouts.js', 'resources/assets/js/admin/form-mahasiswa.js'])
    @if(isset($mahasiswa) && $mahasiswa->prodi_id)
        <script>
            var mahasiswaProdiId = '{{ $mahasiswa->prodi_id }}';
        </script>
    @endif
@endsection

@section('content')
<div class="col-xxl">
  <div class="card mb-6">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">Form Mahasiswa</h5>
       {{-- <small class="text-muted float-end">Default label</small> --}}
    </div>
    <div class="card-body">
      <form id="formAccountSettings" method="POST"
            action="{{ isset($mahasiswa) ? route('admin-mahasiswa-update', $mahasiswa->id) : route('admin-mahasiswa-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($mahasiswa))
            @method('PUT')
        @endif

        <h6>1. Account Details</h6>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label" for="email">Email</label>
            <div class="col-sm-12">
              <input type="text" id="email" name="email" value="{{ $mahasiswa->email ?? old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="adrian@it.student.pens.ac.id" aria-label="john.doe" aria-describedby="email2" />
              @error('email')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="password">Password</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" {{ isset($mahasiswa) ? '' : 'required' }} />
                <span class="input-group-text cursor-pointer" id="password2"><i class="ti ti-eye-off"></i></span>
              </div>
              @error('password')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
              @if(isset($mahasiswa))
                <small class="text-muted">Leave blank to keep current password</small>
              @endif
            </div>
          </div>
        </div>
        <hr class="my-6 mx-n4" />
        <h6>2. Personal Information</h6>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="nrp">NRP</label>
            <div class="col-sm-12">
              <input name="nrp" value="{{ $mahasiswa->nrp ?? old('nrp') }}" type="text" class="form-control @error('nrp') is-invalid @enderror" id="nrp" placeholder="3123500038" />
              @error('nrp')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="nama">Nama</label>
            <div class="col-sm-12">
              <input name="nama" value="{{ $mahasiswa->nama ?? old('nama') }}" type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Denis Beban" />
              @error('nama')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="prodi">Program Studi</label>
            <div class="col-sm-12">
              <select name="prodi_id" id="prodi" class="select2 form-select @error('prodi_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($program_studi as $prodi)
                  <option value="{{ $prodi->id }}"
                          {{ (isset($mahasiswa) && $mahasiswa->prodi_id == $prodi->id) ? 'selected' : '' }}>
                    {{ $prodi->nama }}
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
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kelas">Kelas</label>
            <div class="col-sm-12">
              <select name="kelas_id" id="kelas" class="select2 form-select @error('kelas_id') is-invalid @enderror" data-allow-clear="true" disabled>
                  <option value="">Select value</option>
                  @if(isset($mahasiswa) && $mahasiswa->kelas_id)
                      <option value="{{ $mahasiswa->kelas->id }}" selected>{{ $mahasiswa->kelas->pararel }}</option>
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
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="jenis-kelamin">Jenis Kelamin</label>
            <div class="col-sm-12">
              <select name="jenis_kelamin" id="jenis-kelamin" class="select2 form-select @error('jenis_kelamin') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                <option value="L" {{ (isset($mahasiswa) && $mahasiswa->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ (isset($mahasiswa) && $mahasiswa->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
              </select>
              @error('jenis_kelamin')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="agama">Agama</label>
            <div class="col-sm-12">
              <select name="agama" id="agama" class="select2 form-select @error('agama') is-invalid @enderror" data-allow-clear="true">
                <option value="">Pilih Agama</option>
                <option value="Islam" {{ (isset($mahasiswa) && $mahasiswa->agama == 'Islam') ? 'selected' : '' }}>Islam</option>
                <option value="Kristen" {{ (isset($mahasiswa) && $mahasiswa->agama == 'Kristen') ? 'selected' : '' }}>Kristen</option>
                <option value="Katolik" {{ (isset($mahasiswa) && $mahasiswa->agama == 'Katolik') ? 'selected' : '' }}>Katolik</option>
                <option value="Hindu" {{ (isset($mahasiswa) && $mahasiswa->agama == 'Hindu') ? 'selected' : '' }}>Hindu</option>
                <option value="Buddha" {{ (isset($mahasiswa) && $mahasiswa->agama == 'Buddha') ? 'selected' : '' }}>Buddha</option>
                <option value="Konghuchu" {{ (isset($mahasiswa) && $mahasiswa->agama == 'Konghuchu') ? 'selected' : '' }}>Konghuchu</option>
              </select>
              @error('agama')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="telepon">Telepon</label>
            <div class="col-sm-12">
              <input name="telepon" value="{{ $mahasiswa->telepon ?? old('telepon') }}" type="text" id="telepon" class="form-control phone-mask @error('telepon') is-invalid @enderror" placeholder="0895301391873" aria-label="0895301391873" aria-describedby="basic-default-phone" />
              @error('telepon')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="semester">Semester</label>
            <div class="col-sm-12">
              <input name="semester" value="{{ $mahasiswa->semester ?? old('semester') }}" type="number" class="form-control @error('semester') is-invalid @enderror" id="semester" placeholder="1" />
              @error('semester')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="tanggal-lahir">Tanggal Lahir</label>
            <div class="col-sm-12">
              <input name="tanggal_lahir" value="{{ $mahasiswa->tanggal_lahir ?? old('tanggal_lahir') }}" type="text" id="tanggal-lahir" class="form-control dob-picker @error('tanggal_lahir') is-invalid @enderror" placeholder="YYYY-MM-DD" />
              @error('tanggal_lahir')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="tanggal-masuk">Tanggal Masuk</label>
            <div class="col-sm-12">
              <input name="tanggal_masuk" value="{{ $mahasiswa->tanggal_masuk ?? old('tanggal_masuk') }}" type="text" id="tanggal-masuk" class="form-control dob-picker @error('tanggal_masuk') is-invalid @enderror" placeholder="YYYY-MM-DD" />
              @error('tanggal_masuk')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="status">Status</label>
            <div class="col-sm-12">
              <select name="status" id="status" class="select2 form-select @error('status') is-invalid @enderror" data-allow-clear="true">
                <option value="">Pilih Status</option>
                <option value="Aktif" {{ (isset($mahasiswa) && $mahasiswa->status == 'Aktif') ? 'selected' : '' }}>Aktif</option>
                <option value="Cuti" {{ (isset($mahasiswa) && $mahasiswa->status == 'Cuti') ? 'selected' : '' }}>Cuti</option>
                <option value="Keluar" {{ (isset($mahasiswa) && $mahasiswa->status == 'Keluar') ? 'selected' : '' }}>Keluar</option>
              </select>
              @error('status')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="alamat-jalan">Alamat Jalan</label>
            <div class="col-sm-12">
              <input name="alamat_jalan" value="{{ $mahasiswa->alamat_jalan ?? old('alamat_jalan') }}" type="text" class="form-control @error('alamat_jalan') is-invalid @enderror" id="alamat-jalan" placeholder="Jl. Raya Keputih" />
              @error('alamat_jalan')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="provinsi">Provinsi</label>
            <div class="col-sm-12">
              <select name="provinsi" id="provinsi" class="select2 form-select @error('provinsi') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                <option value="Jawa Timur" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Jawa Timur') ? 'selected' : '' }}>Jawa Timur</option>
                <option value="Jawa Tengah" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Jawa Tengah') ? 'selected' : '' }}>Jawa Tengah</option>
                <option value="Jawa Barat" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Jawa Barat') ? 'selected' : '' }}>Jawa Barat</option>
                <option value="Bali" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Bali') ? 'selected' : '' }}>Bali</option>
                <option value="Banten" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Banten') ? 'selected' : '' }}>Banten</option>
                <option value="DKI Jakarta" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'DKI Jakarta') ? 'selected' : '' }}>DKI Jakarta</option>
                <option value="DI Yogyakarta" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'DI Yogyakarta') ? 'selected' : '' }}>DI Yogyakarta</option>
                <option value="Sumatera Utara" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Sumatera Utara') ? 'selected' : '' }}>Sumatera Utara</option>
                <option value="Sumatera Selatan" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Sumatera Selatan') ? 'selected' : '' }}>Sumatera Selatan</option>
                <option value="Kalimantan Timur" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Kalimantan Timur') ? 'selected' : '' }}>Kalimantan Timur</option>
                <option value="Kalimantan Selatan" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Kalimantan Selatan') ? 'selected' : '' }}>Kalimantan Selatan</option>
                <option value="Kalimantan Tengah" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Kalimantan Tengah') ? 'selected' : '' }}>Kalimantan Tengah</option>
                <option value="Sulawesi Utara" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Sulawesi Utara') ? 'selected' : '' }}>Sulawesi Utara</option>
                <option value="Sulawesi Selatan" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Sulawesi Selatan') ? 'selected' : '' }}>Sulawesi Selatan</option>
                <option value="Sulawesi Tengah" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Sulawesi Tengah') ? 'selected' : '' }}>Sulawesi Tengah</option>
                <option value="Maluku" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Maluku') ? 'selected' : '' }}>Maluku</option>
                <option value="Maluku Utara" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Maluku Utara') ? 'selected' : '' }}>Maluku Utara</option>
                <option value="Papua" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Papua') ? 'selected' : '' }}>Papua</option>
                <option value="Papua Barat" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Papua Barat') ? 'selected' : '' }}>Papua Barat</option>
                <option value="Nusa Tenggara Barat" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Nusa Tenggara Barat') ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                <option value="Nusa Tenggara Timur" {{ (isset($mahasiswa) && $mahasiswa->provinsi == 'Nusa Tenggara Timur') ? 'selected' : '' }}>Nusa Tenggara Barat</option>
              </select>
              @error('provinsi')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kode-pos">Kode Pos</label>
            <div class="col-sm-12">
              <input name="kode_pos" value="{{ $mahasiswa->kode_pos ?? old('kode_pos') }}" type="text" class="form-control @error('kode_pos') is-invalid @enderror" id="kode-pos" placeholder="123456" />
              @error('kode_pos')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="negara">Country</label>
            <div class="col-sm-12">
              <select name="negara" id="negara" class="select2 form-select @error('negara') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                <option value="Australia" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Australia') ? 'selected' : '' }}>Australia</option>
                <option value="Bangladesh" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Bangladesh') ? 'selected' : '' }}>Bangladesh</option>
                <option value="Belarus" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Belarus') ? 'selected' : '' }}>Belarus</option>
                <option value="Brazil" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Brazil') ? 'selected' : '' }}>Brazil</option>
                <option value="Canada" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Canada') ? 'selected' : '' }}>Canada</option>
                <option value="China" {{ (isset($mahasiswa) && $mahasiswa->negara == 'China') ? 'selected' : '' }}>China</option>
                <option value="France" {{ (isset($mahasiswa) && $mahasiswa->negara == 'France') ? 'selected' : '' }}>France</option>
                <option value="Germany" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Germany') ? 'selected' : '' }}>Germany</option>
                <option value="India" {{ (isset($mahasiswa) && $mahasiswa->negara == 'India') ? 'selected' : '' }}>India</option>
                <option value="Indonesia" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Indonesia') ? 'selected' : '' }}>Indonesia</option>
                <option value="Italy" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Italy') ? 'selected' : '' }}>Italy</option>
                <option value="Japan" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Japan') ? 'selected' : '' }}>Japan</option>
                <option value="Korea" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Korea') ? 'selected' : '' }}>Korea, Republic of</option>
                <option value="Mexico" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Mexico') ? 'selected' : '' }}>Mexico</option>
                <option value="Pakistan" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Pakistan') ? 'selected' : '' }}>Pakistan</option>
                <option value="Philippines" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Philippines') ? 'selected' : '' }}>Philippines</option>
                <option value="Russia" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Russia') ? 'selected' : '' }}>Russian Federation</option>
                <option value="South Africa" {{ (isset($mahasiswa) && $mahasiswa->negara == 'South Africa') ? 'selected' : '' }}>South Africa</option>
                <option value="Thailand" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Thailand') ? 'selected' : '' }}>Thailand</option>
                <option value="Turkey" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Turkey') ? 'selected' : '' }}>Turkey</option>
                <option value="Ukraine" {{ (isset($mahasiswa) && $mahasiswa->negara == 'Ukraine') ? 'selected' : '' }}>Ukraine</option>
                <option value="United Arab Emirates" {{ (isset($mahasiswa) && $mahasiswa->negara == 'United Arab Emirates') ? 'selected' : '' }}>United Arab Emirates</option>
                <option value="United Kingdom" {{ (isset($mahasiswa) && $mahasiswa->negara == 'United Kingdom') ? 'selected' : '' }}>United Kingdom</option>
                <option value="United States" {{ (isset($mahasiswa) && $mahasiswa->negara == 'United States') ? 'selected' : '' }}>United States</option>
              </select>
              @error('negara')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kelurahan">Kelurahan</label>
            <div class="col-sm-12">
              <input name="kelurahan" value="{{ $mahasiswa->kelurahan ?? old('kelurahan') }}" type="text" class="form-control @error('kelurahan') is-invalid @enderror" id="kelurahan" placeholder="Desa Keputih" />
              @error('kelurahan')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kecamatan">Kecamatan</label>
            <div class="col-sm-12">
              <input name="kecamatan" value="{{ $mahasiswa->kecamatan ?? old('kecamatan') }}" type="text" class="form-control @error('kecamatan') is-invalid @enderror" id="kecamatan" placeholder="Sukolilo" />
              @error('kecamatan')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-6">
            <label class="form-label" for="kota">Kota</label>
            <div class="col-sm-12">
              <input name="kota" value="{{ $mahasiswa->kota ?? old('kota') }}" type="text" class="form-control @error('kota') is-invalid @enderror" id="kota" placeholder="Surabaya" />
              @error('kota')
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
            <a href="{{ route('admin-mahasiswa-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
