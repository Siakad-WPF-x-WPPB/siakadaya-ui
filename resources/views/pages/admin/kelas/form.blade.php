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
            action="{{ isset($kelas) ? route('admin-kelas-update', $kelas->id) : route('admin-kelas-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($kelas))
            @method('PUT')
        @endif

        <div class="row">
          <div class="col-md-6">
            <label class="form-label" for="pararel">Pararel</label>
            <div class="col-sm-12">
              <div class="input-group input-group-merge">
                <input type="text" id="pararel" name="pararel" value="{{ $kelas->pararel ?? old('pararel') }}" class="form-control" placeholder="IT-A" aria-label="IT-A" aria-describedby="pararel2" />
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="dosen-id">Dosen</label>
            <div class="col-sm-12">
              <select name="dosen_id" id="dosen-id" class="select2 form-select" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($dosen as $d)
                  <option value="{{ $d->id }}"{{ (isset($kelas) && $kelas->dosen_id == $d->id) ? 'selected' : '' }}>
                    {{ $d->nama }} - {{ $d->nip }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-md-6 d-grid">
            <button type="submit" class="btn btn-primary">Kirim</button>
          </div>
          <div class="col-md-6 d-grid">
            <a href="{{ route('admin-kelas-index') }}" type="reset" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
