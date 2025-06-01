@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Jadwal Kuliah')

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
    {{-- @vite(['resources/assets/js/dosen/table-jadwal.js']) --}}
    <script>
        // Excel Import Modal Handler
        document.addEventListener('DOMContentLoaded', function() {
            const importBtn = document.getElementById('importExcelBtn');
            if (importBtn) {
                importBtn.addEventListener('click', function() {
                    var modal = new bootstrap.Modal(document.getElementById('importModal'));
                    modal.show();
                });
            }

            // File input change handler
            const fileInput = document.getElementById('excelFile');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const fileName = this.files[0]?.name || '';
                    document.getElementById('fileName').textContent = fileName ? `File: ${fileName}` : '';
                });
            }
        });
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"> Mahasiswa Terdaftar untuk Jadwal:</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success" id="importExcelBtn">
                    <i class="ti ti-file-import me-2"></i>Import Nilai Excel
                </button>
                <a href="{{ route('dosen.nilai.template', $jadwal->id) }}" class="btn btn-outline-primary">
                    <i class="ti ti-download me-2"></i>Download Template
                </a>
            </div>
        </div>
        <div class="card-body">

        @if (session('success'))
            <div class="alert alert-success mt-3 alert-dismissible">
                <i class="ti ti-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger mt-3 alert-dismissible">
                <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('warning') && session('import_errors'))
            <div class="alert alert-warning mt-3">
                <i class="ti ti-alert-triangle me-2"></i>{{ session('warning') }}
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-warning" type="button" data-bs-toggle="collapse" data-bs-target="#errorDetails">
                        <i class="ti ti-eye me-1"></i>Lihat Detail Error
                    </button>
                </div>
                <div class="collapse mt-2" id="errorDetails">
                    <div class="card card-body bg-light">
                        <ul class="mb-0 small">
                            @foreach (session('import_errors') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <h6>
            <strong>Mata Kuliah:</strong> {{ $jadwal->matakuliah->nama ?? 'N/A' }} <br>
            <strong>Kelas:</strong> {{ $jadwal->kelas->pararel ?? 'N/A' }} <br>
            <strong>Hari:</strong> {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
        </h6>
        </div>
        <div class="card-datatable table-responsive pt-0">
            @if ($mahasiswas->isEmpty())
            <h5 class="text-center">Belum ada mahasiswa yang mengambil jadwal ini atau FRS mahasiswa belum disetujui dosen wali. </h5>
            @else
            <table class="datatables-basic table">
                <thead>
                    <tr>
                        <th>NRP</th>
                        <th>Nama Mahasiswa</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                    <tbody>
                        @foreach ($mahasiswas as $mahasiswa)
                        @php
                            $nilaiDetail = null;
                            foreach ($mahasiswa->frs as $frs) {
                                foreach ($frs->frsDetail as $detail) {
                                    if ($detail->jadwal_id == $jadwal->id && $detail->status == 'disetujui') {
                                        $nilaiDetail = $detail;
                                        break 2;
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $mahasiswa->nrp ?? 'N/A' }}</td>
                            <td>{{ $mahasiswa->nama ?? 'N/A' }}</td>
                            <td>{{ $nilaiDetail && $nilaiDetail->nilai ? $nilaiDetail->nilai->nilai_angka : '-' }}</td>
                            <td>{{ $nilaiDetail && $nilaiDetail->nilai ? $nilaiDetail->nilai->nilai_huruf : '-' }}</td>
                            <td>
                                <a href="{{ route('dosen.nilai.create', ['jadwal' => $jadwal->id, 'mahasiswa' => $mahasiswa->id]) }}"
                                    class="btn btn-md btn-success">Input Nilai</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
        </div>
    </div>

    <!-- Import Excel Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="ti ti-file-import me-2"></i>Import Nilai dari Excel
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('dosen.nilai.import', $jadwal->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Instructions -->
                        <div class="alert alert-info mb-3">
                            <h6 class="mb-2"><i class="ti ti-info-circle me-1"></i>Petunjuk Import:</h6>
                            <ol class="mb-0 small">
                                <li>Download template Excel terlebih dahulu dengan tombol "Download Template"</li>
                                <li>Buka file template dan isi kolom <strong>"nilai_angka"</strong> dengan nilai 0-100</li>
                                <li><strong>Jangan mengubah</strong> kolom "nrp" dan "nama"</li>
                                <li>Simpan file Excel dan upload di form ini</li>
                                <li>Nilai huruf dan status kelulusan akan dihitung otomatis</li>
                            </ol>
                        </div>

                        <!-- File Input -->
                        <div class="mb-3">
                            <label class="form-label" for="excelFile">Pilih File Excel</label>
                            <input type="file" id="excelFile" name="file"
                                   class="form-control"
                                   accept=".xlsx,.xls,.csv" required>
                            <div class="form-text" id="fileName"></div>
                        </div>

                        <!-- Template Download Link -->
                        <div class="mb-3">
                            <a href="{{ route('dosen.nilai.template', $jadwal->id) }}"
                               class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="ti ti-download me-1"></i>Download Template Excel
                            </a>
                            <small class="text-muted d-block mt-1">Template berisi semua mahasiswa yang terdaftar di jadwal ini</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-upload me-1"></i>Import Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
