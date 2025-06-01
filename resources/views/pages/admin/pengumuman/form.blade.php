@extends('layouts/layoutMaster')

@section('title', 'Form Pengumuman')

@section('content')
<div class="col-xxl">
  <div class="card mb-6">
    <div class="card-header d-flex align-items-center justify-content-between">
      <h5 class="mb-0">{{ isset($pengumuman) ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}</h5>
    </div>
    <div class="card-body">
      <form id="formPengumuman" method="POST"
            action="{{ isset($pengumuman) ? route('admin-pengumuman-update', $pengumuman->id) : route('admin-pengumuman-store') }}"
            enctype="multipart/form-data">
        @csrf

        @if(isset($pengumuman))
            @method('PUT')
        @endif

        <div class="row mb-4">
          <div class="col-md-12">
            <label class="form-label" for="judul">Judul Pengumuman</label>
            <div class="col-sm-12">
              <input type="text" id="judul" name="judul" 
                     value="{{ $pengumuman->judul ?? old('judul') }}" 
                     class="form-control @error('judul') is-invalid @enderror" 
                     placeholder="Masukkan judul pengumuman" 
                     aria-label="Judul Pengumuman" />
              @error('judul')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-12">
            <label class="form-label" for="isi">Isi Pengumuman</label>
            <div class="col-sm-12">
              <textarea id="isi" name="isi" 
                        class="form-control @error('isi') is-invalid @enderror" 
                        rows="8" 
                        placeholder="Masukkan isi pengumuman">{{ $pengumuman->isi ?? old('isi') }}</textarea>
              @error('isi')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label" for="tanggal_dibuat">Tanggal Dibuat</label>
            <div class="col-sm-12">
              <input type="date" id="tanggal_dibuat" name="tanggal_dibuat" 
                     value="{{ isset($pengumuman) ? date('Y-m-d', strtotime($pengumuman->tanggal_dibuat)) : old('tanggal_dibuat', date('Y-m-d')) }}" 
                     class="form-control @error('tanggal_dibuat') is-invalid @enderror" />
              @error('tanggal_dibuat')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="status">Status</label>
            <div class="col-sm-12">
              <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                <option value="">Pilih Status</option>
                <option value="aktif" {{ (isset($pengumuman) && $pengumuman->status == 'aktif') || old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ (isset($pengumuman) && $pengumuman->status == 'nonaktif') || old('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
              </select>
              @error('status')
                <div class="invalid-feedback d-block">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>
        
        <!-- error handling for all fields -->
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="row mt-4">
          <div class="col-md-6 d-grid">
            <button type="submit" class="btn btn-primary">
              {{ isset($pengumuman) ? 'Update' : 'Simpan' }}
            </button>
          </div>
          <div class="col-md-6 d-grid">
            <a href="{{ route('admin-pengumuman-index') }}" class="btn btn-label-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection