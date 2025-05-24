@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dosen')

<!-- Vendor Styles -->
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
  'resources/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.scss',
  'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
  'resources/assets/vendor/libs/@form-validation/popular.js',
  'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
  'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

<!-- Page Scripts -->
@section('page-script')
@vite(['resources/assets/js/dosen/table-nilai.js'])
@endsection

@section('content')
  <!-- DataTable with Buttons -->
  <div class="card">
    <div class="container">
      <h2>Input Nilai untuk {{ $mahasiswa->nama}} ({{ $mahasiswa->nrp}})</h2>
      <p>
          <strong>Mata Kuliah:</strong> {{ $jadwal->matakuliah->nama}} <br>
          <strong>Kelas:</strong> {{ $jadwal->kelas->pararel }}
      </p>
  
      @if (session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif
      @if ($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
  
      <form method="POST" action="{{ route('dosen.nilai.store', ['jadwal' => $jadwal->id, 'mahasiswa' => $mahasiswa->id]) }}">
          @csrf
          <div class="mb-3">
              <label for="nilai_angka" class="form-label">Nilai Angka (0-100)</label>
              <input type="number" class="form-control @error('nilai_angka') is-invalid @enderror" id="nilai_angka" name="nilai_angka" value="{{ old('nilai_angka', $nilai->nilai_angka) }}" required min="0" max="100">
              @error('nilai_angka')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
  
          @if($nilai->exists)
          <div class="mb-3">
              <label class="form-label">Nilai Huruf (Otomatis)</label>
              <input type="text" class="form-control" value="{{ $nilai->nilai_huruf }}" readonly>
          </div>
          <div class="mb-3">
              <label class="form-label">Status Kelulusan (Otomatis)</label>
              <input type="text" class="form-control" value="{{ ucfirst($nilai->status) }}" readonly>
          </div>
          @endif
  
          <button type="submit" class="btn btn-primary">Simpan Nilai</button>
          <a href="{{ route('dosen.jadwal.mahasiswa', $jadwal->id) }}" class="btn btn-secondary">Batal</a>
      </form>
  </div>
  </div>
  <!-- Modal to add new record -->
  <div class="offcanvas offcanvas-end" id="add-new-record">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="exampleModalLabel">New Record</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body flex-grow-1">
      <form class="add-new-record pt-0 row g-2" id="form-add-new-record" onsubmit="return false">
        <div class="col-sm-12">
          <label class="form-label" for="basicFullname">Full Name</label>
          <div class="input-group input-group-merge">
            <span id="basicFullname2" class="input-group-text"><i class="ti ti-user"></i></span>
            <input type="text" id="basicFullname" class="form-control dt-full-name" name="basicFullname" placeholder="John Doe" aria-label="John Doe" aria-describedby="basicFullname2" />
          </div>
        </div>
        <div class="col-sm-12">
          <label class="form-label" for="basicPost">Post</label>
          <div class="input-group input-group-merge">
            <span id="basicPost2" class="input-group-text"><i class='ti ti-briefcase'></i></span>
            <input type="text" id="basicPost" name="basicPost" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" aria-describedby="basicPost2" />
          </div>
        </div>
        <div class="col-sm-12">
          <label class="form-label" for="basicEmail">Email</label>
          <div class="input-group input-group-merge">
            <span class="input-group-text"><i class="ti ti-mail"></i></span>
            <input type="text" id="basicEmail" name="basicEmail" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
          </div>
          <div class="form-text">
            You can use letters, numbers & periods
          </div>
        </div>
        <div class="col-sm-12">
          <label class="form-label" for="basicDate">Joining Date</label>
          <div class="input-group input-group-merge">
            <span id="basicDate2" class="input-group-text"><i class='ti ti-calendar'></i></span>
            <input type="text" class="form-control dt-date" id="basicDate" name="basicDate" aria-describedby="basicDate2" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
          </div>
        </div>
        <div class="col-sm-12">
          <label class="form-label" for="basicSalary">Salary</label>
          <div class="input-group input-group-merge">
            <span id="basicSalary2" class="input-group-text"><i class='ti ti-currency-dollar'></i></span>
            <input type="number" id="basicSalary" name="basicSalary" class="form-control dt-salary" placeholder="12000" aria-label="12000" aria-describedby="basicSalary2" />
          </div>
        </div>
        <div class="col-sm-12">
          <button type="submit" class="btn btn-primary data-submit me-sm-4 me-1">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </div>
      </form>

    </div>
  </div>
  <!--/ DataTable with Buttons -->
@endsection
