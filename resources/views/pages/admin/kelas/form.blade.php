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
      <h5 class="mb-0">Form Kelas</h5> 
      {{-- <small class="text-muted float-end">Default label</small> --}}
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
            <label class="form-label" for="prodi">Program Studi</label>
            <div class="col-sm-12">
              <select name="prodi_id" id="prodi" class="select2 form-select @error('prodi_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($prodi as $p)
                  <option value="{{ $p->id }}" {{ (isset($kelas) && $kelas->prodi_id == $p->id) || old('prodi_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->nama }}
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
            <label class="form-label" for="pararel">Pararel</label>
            <div class="col-sm-12">
              <input type="text" id="pararel" name="pararel" value="{{ $kelas->pararel ?? old('pararel') }}" class="form-control @error('pararel') is-invalid @enderror" placeholder="IT-A" aria-label="IT-A" aria-describedby="pararel2" />
              @error('pararel')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          {{-- <div class="col-md-4">
            <label class="form-label" for="dosen">Dosen</label>
            <div class="col-sm-12">
              <select name="dosen_id" id="dosen" class="select2 form-select @error('dosen_id') is-invalid @enderror" data-allow-clear="true">
                <option value="">Select</option>
                @foreach($dosen as $d)
                  <option value="{{ $d->id }}"{{ (isset($kelas) && $kelas->dosen_id == $d->id) || old('dosen_id') == $d->id ? 'selected' : '' }}>
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
          </div> --}}
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
