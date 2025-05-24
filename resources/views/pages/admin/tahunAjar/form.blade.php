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
            action="{{ isset($tahunAjar) ? route('admin-tahun-ajar-update', $tahunAjar->id) : route('admin-tahun-ajar-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($tahunAjar))
            @method('PUT')
        @endif

        <div class="row">
          <div class="col-md-3">
            <label class="form-label" for="semester">Semester</label>
            <div class="col-sm-12">
              <select class="select2 form-select @error('semester') is-invalid @enderror" id="semester" name="semester">
                <option value="">Select</option>
                <option value="Ganjil" {{ (isset($tahunAjar) && $tahunAjar->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ (isset($tahunAjar) && $tahunAjar->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
              </select>
              @error('semester')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-3">
            <label class="form-label" for="tahun-mulai">Tahun Mulai</label>
            <div class="col-sm-12">
              <input type="text" id="tahun-mulai" name="tahun_mulai" value="{{ $tahunAjar->tahun_mulai ?? old('tahun_mulai') }}" class="form-control flatpickr-basic @error('tahun_mulai') is-invalid @enderror" placeholder="2024" aria-label="2024" min="1900" max="{{ date('Y') + 10 }}" aria-describedby="basic-icon-default-fullname2" />
              @error('tahun_mulai')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-3">
            <label class="form-label" for="tahun-berakhir">Tahun Berakhir</label>
            <div class="col-sm-12">
              <input type="text" id="tahun-berakhir" name="tahun_akhir" value="{{ $tahunAjar->tahun_akhir ?? old('tahun_akhir') }}" class="form-control flatpickr-basic @error('tahun_akhir') is-invalid @enderror" placeholder="2025" aria-label="2025" min="1900" max="{{ date('Y') + 10 }}" aria-describedby="basic-icon-default-fullname2" />
              @error('tahun_akhir')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-3">
            <label class="form-label" for="status">Status</label>
            <div class="col-sm-12">
              <select class="select2 form-select @error('status') is-invalid @enderror" id="status" name="status">
                <option value="">Select value</option>
                <option value="Aktif" {{ (isset($tahunAjar) && $tahunAjar->status == 'Aktif') || old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ (isset($tahunAjar) && $tahunAjar->status == 'Tidak Aktif') || old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
              </select>
              @error('status')
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
            <a href="{{ route('admin-tahun-ajar-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
