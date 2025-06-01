@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dosen')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/form-layouts.js'])
@endsection

@section('content')
    <div class="col-xxl">
        <div class="card mb-6">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Input Nilai Mahasiswa</h5>
                <small class="text-muted float-end">Form penilaian</small>
            </div>
            <div class="card-body">
                @if ($nilai->exists)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h6><i class="ti ti-alert-triangle me-2"></i>Hapus Nilai</h6>
                                <p class="mb-2">Menghapus nilai akan mengembalikan status mahasiswa menjadi "Belum Dinilai".</p>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="ti ti-trash me-1"></i>Hapus Nilai
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">
                                        <i class="ti ti-alert-triangle me-2"></i>Konfirmasi Hapus Nilai
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus nilai untuk mahasiswa:</p>
                                    <div class="alert alert-info">
                                        <strong>{{ $mahasiswa->nama }}</strong> ({{ $mahasiswa->nrp }})<br>
                                        <small>Nilai saat ini: {{ $nilai->nilai_angka }} ({{ $nilai->nilai_huruf }})</small>
                                    </div>
                                    <p class="text-danger"><small><i class="ti ti-info-circle me-1"></i>Tindakan ini tidak dapat dibatalkan!</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form method="POST" action="{{ route('dosen.nilai.destroy', ['jadwal' => $jadwal->id, 'mahasiswa' => $mahasiswa->id]) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="ti ti-trash me-1"></i>Ya, Hapus Nilai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Nama Mahasiswa</strong></label>
                            <div class="form-control-plaintext">{{ $mahasiswa->nama }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>NRP</strong></label>
                            <div class="form-control-plaintext">{{ $mahasiswa->nrp }}</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Mata Kuliah</strong></label>
                            <div class="form-control-plaintext">{{ $jadwal->matakuliah->nama }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><strong>Kelas</strong></label>
                            <div class="form-control-plaintext">{{ $jadwal->kelas->pararel }}</div>
                        </div>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Input Nilai -->
                <form id="formInputNilai" method="POST"
                    action="{{ route('dosen.nilai.store', ['jadwal' => $jadwal->id, 'mahasiswa' => $mahasiswa->id]) }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label" for="nilai_angka">Nilai Angka</label>
                            <div class="col-sm-12">
                                <input type="number" id="nilai_angka" name="nilai_angka"
                                    value="{{ old('nilai_angka', $nilai->nilai_angka) }}"
                                    class="form-control @error('nilai_angka') is-invalid @enderror" placeholder="0-100"
                                    min="0" max="100" required />
                                @error('nilai_angka')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        @if ($nilai->exists)
                            <div class="col-md-4">
                                <label class="form-label" for="nilai_huruf">Nilai Huruf</label>
                                <div class="col-sm-12">
                                    <input type="text" id="nilai_huruf" class="form-control"
                                        value="{{ $nilai->nilai_huruf }}" readonly placeholder="Otomatis" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="status">Status Kelulusan</label>
                                <div class="col-sm-12">
                                    <input type="text" id="status" class="form-control"
                                        value="{{ ucfirst($nilai->status) }}" readonly placeholder="Otomatis" />
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                        </div>
                        <div class="col-md-6 d-grid">
                            <a href="{{ route('dosen.jadwal.mahasiswa', $jadwal->id) }}"
                                class="btn btn-label-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
