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
    @vite(['resources/assets/js/form-layouts.js'])
@endsection

@section('content')
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Form Matakuliah</h5>
                {{-- <small class="text-muted float-end">Default label</small> --}}
            </div>
            <div class="card-body">
                <form id="formAccountSettings" method="POST"
                    action="{{ isset($matakuliah) ? route('admin-mata-kuliah-update', $matakuliah->id) : route('admin-mata-kuliah-store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    @if (isset($matakuliah))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="kode">Kode Mata Kuliah</label>
                            <div class="col-sm-12">
                                <input type="text" id="kode" name="kode"
                                    value="{{ $matakuliah->kode ?? old('kode') }}"
                                    class="form-control @error('kode') is-invalid @enderror" placeholder="IT1203"
                                    aria-label="IT-A" aria-describedby="kode2" />
                                @error('kode')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="prodi">Program Studi</label>
                            <div class="col-sm-12">
                              <select name="prodi_id" id="prodi"
                              class="select2 form-select @error('prodi_id') is-invalid @enderror"
                              {{ isset($matakuliah) && $matakuliah->jadwal->count() ? 'disabled' : '' }} data-allow-clear="true">
                              <option value="">Select</option>
                              @foreach ($program_studi as $pstudi)
                                  <option
                                      value="{{ $pstudi->id }}"{{ isset($matakuliah) && $matakuliah->prodi_id == $pstudi->id ? 'selected' : '' }}>
                                      {{ $pstudi->nama }} - {{ $pstudi->kode }}
                                  </option>
                              @endforeach
                          </select>
                          @error('prodi_id')
                              <div class="invalid-feedback d-block">
                                  {{ $message }}
                              </div>
                          @enderror
                          @if (isset($matakuliah) && $matakuliah->jadwal->count())
                              <input type="hidden" name="prodi_id" value="{{ $matakuliah->prodi_id }}">
                              <div class="text-danger mt-2">
                                  Program Studi tidak dapat diubah karena sudah ada jadwal kuliah terkait.
                              </div>
                          @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="nama">Nama Mata Kuliah</label>
                            <div class="col-sm-12">
                                <input type="text" id="nama" name="nama"
                                    value="{{ $matakuliah->nama ?? old('nama') }}"
                                    class="form-control @error('nama') is-invalid @enderror" placeholder="IT1203"
                                    aria-label="IT-A" aria-describedby="nama2" />
                                @error('nama')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="semester">Semester</label>
                            <div class="col-sm-12">
                                <input type="text" id="semester" name="semester"
                                    value="{{ $matakuliah->semester ?? old('semester') }}"
                                    class="form-control @error('semester') is-invalid @enderror" placeholder="1"
                                    aria-label="1" aria-describedby="semester2" />
                                @error('semester')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="sks">SKS</label>
                            <div class="col-sm-12">
                                <input type="number" id="sks" name="sks"
                                    value="{{ $matakuliah->sks ?? old('sks') }}"
                                    class="form-control @error('sks') is-invalid @enderror" placeholder="2" aria-label="2"
                                    aria-describedby="sks2" />
                                @error('sks')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="tipe">Tipe Mata Kuliah</label>
                            <div class="col-sm-12">
                                <select name="tipe" id="tipe"
                                    class="select2 form-select @error('tipe') is-invalid @enderror" data-allow-clear="true">
                                    <option value="">Select</option>
                                    <option
                                        value="MW"{{ isset($matakuliah) && $matakuliah->tipe == 'MW' ? 'selected' : '' }}>
                                        Mata Kuliah Wajib
                                    </option>
                                    <option
                                        value="MPP"{{ isset($matakuliah) && $matakuliah->tipe == 'MPP' ? 'selected' : '' }}>
                                        Mata Kuliah Pilihan Prodi
                                    </option>
                                    <option
                                        value="MPK"{{ isset($matakuliah) && $matakuliah->tipe == 'MPK' ? 'selected' : '' }}>
                                        Mata Kuliah Pilihan
                                    </option>
                                    <option
                                        value="MPI"{{ isset($matakuliah) && $matakuliah->tipe == 'MPI' ? 'selected' : '' }}>
                                        Mata Kuliah Pilihan Institusi
                                    </option>
                                    <option
                                        value="MBKM"{{ isset($matakuliah) && $matakuliah->tipe == 'MBKM' ? 'selected' : '' }}>
                                        Mata Kuliah Merdeka Belajar Kampus Merdeka
                                    </option>
                                </select>
                                @error('tipe')
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
                            <a href="{{ route('admin-mata-kuliah-index') }}" type="reset"
                                class="btn btn-label-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
