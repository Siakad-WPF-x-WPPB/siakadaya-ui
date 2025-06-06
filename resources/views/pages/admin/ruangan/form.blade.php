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
      <h5 class="mb-0">Form Ruangan</h5> 
      {{-- <small class="text-muted float-end">Default label</small> --}}
    </div>
    <div class="card-body">
      <form id="formAccountSettings" method="POST"
            action="{{ isset($ruangan) ? route('admin-ruangan-update', $ruangan->id) : route('admin-ruangan-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($ruangan))
            @method('PUT')
        @endif

        <div class="row">
          <div class="col-md-4">
            <label class="form-label" for="kode">Kode</label>
            <div class="col-sm-12">
              <input type="text" id="kode" name="kode" value="{{ $ruangan->kode ?? old('kode') }}" class="form-control @error('kode') is-invalid @enderror" placeholder="IT" aria-label="IT" aria-describedby="kode2" />
              @error('kode')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="nama">Nama</label>
            <div class="col-sm-12">
              <input type="text" id="nama" name="nama" value="{{ $ruangan->nama ?? old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Teknik Informatika" aria-label="Teknik Informatika" aria-describedby="nama2" />
              @error('nama')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <label class="form-label" for="gedung">Gedung</label>
            <div class="col-sm-12">
              <input type="text" id="gedung" name="gedung" value="{{ $ruangan->gedung ?? old('gedung') }}" class="form-control @error('gedung') is-invalid @enderror" placeholder="Teknik Informatika" aria-label="Teknik Informatika" aria-describedby="gedung2" />
              @error('gedung')
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
            <a href="{{ route('admin-ruangan-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
