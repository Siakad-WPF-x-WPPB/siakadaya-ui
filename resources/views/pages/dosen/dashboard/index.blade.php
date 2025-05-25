@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('content')
  <!-- Card Border Shadow -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table">
        <thead>
          <tr>
            <th></th>
            <th>Hari</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Kelas</th>
            <th>Matakuliah</th>
            <th>Program Studi</th>
            <th>Ruangan</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($jadwals as $item)
            <tr>
              <td></td>
              <td>{{ $item->hari }}</td>
              <td>{{ $item->jam_mulai }}</td>
              <td>{{ $item->jam_selesai }}</td>
              <td>{{ $item->kelas->pararel }}</td>
              <td>{{ $item->matakuliah->nama }}</td>
              <td>{{ $item->kelas->programStudi->nama }}</td>
              <td>{{ $item->ruangan->nama }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!--/ Card Border Shadow -->
@endsection
