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
          <div class="col-md-6">
            <label class="form-label" for="semester">Semester</label>
            <div class="col-sm-12">
              <select class="select2 form-select" id="semester" name="semester">
                <option value="Ganjil" {{ (isset($tahunAjar) && $tahunAjar->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ (isset($tahunAjar) && $tahunAjar->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="tahun">Tahun</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="text" id="tahun" name="tahun" value="{{ $tahunAjar->tahun ?? old('tahun') }}" class="form-control flatpickr-basic" placeholder="2024/2025" aria-label="2024/2025" aria-describedby="basic-icon-default-fullname2" />
              </div>
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
