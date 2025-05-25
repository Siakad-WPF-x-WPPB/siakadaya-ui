@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('content')
  <!-- Card Border Shadow -->
  <div class="row g-6">
    <!-- Card Mahasiswa -->
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-primary h-full">
        <div class="card-body">
          <h6 class="mb-4 ">Total Mahasiswa</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-primary"><i class='ti ti-user ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $mahasiswa }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-warning h-full">
        <div class="card-body">
          <h6 class="mb-4">Total Dosen</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-warning"><i class='ti ti-user-square ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $dosen }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-danger h-full">
        <div class="card-body">
          <h6 class="mb-4">Total Matakuliah</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-danger"><i class='ti ti-books ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $matakuliah }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-info h-full">
        <div class="card-body">
          <h6 class="mb-4">Total Kelas</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-info"><i class='ti ti-chalkboard ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $kelas }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-primary h-full">
        <div class="card-body">
          <h6 class="mb-4 ">Total Jadwal Kuliah</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-primary"><i class='ti ti-alarm ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $jadwal }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-warning h-full">
        <div class="card-body">
          <h6 class="mb-4">Total Tahun Ajar</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-warning"><i class='ti ti-calendar-clock ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $tahunAjar }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-danger h-full">
        <div class="card-body">
          <h6 class="mb-4">Total Program Studi</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-danger"><i class='ti ti-building ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $programStudi }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card card-border-shadow-info h-full">
        <div class="card-body">
          <h6 class="mb-4">Total Ruangan</h6>
          <div class="d-flex align-items-center">
            <div class="avatar me-4">
              <span class="avatar-initial rounded bg-label-info"><i class='ti ti-building-warehouse ti-28px'></i></span>
            </div>
            <h4 class="mb-0">{{ $ruangan }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--/ Card Border Shadow -->
@endsection
