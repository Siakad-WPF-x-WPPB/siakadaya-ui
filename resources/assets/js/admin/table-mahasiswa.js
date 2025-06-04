/**
 * DataTables Basic
 */

'use strict';
// import API_ENDPOINTS from '../../config/apiConfig';

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

let fv, offCanvasEl;

// datatable (jquery)
$(function () {
  var dt_basic_table = $('.datatables-basic'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    dt_basic;

  // Initialize filters
  let prodiFilter = '';
  let kelasFilter = '';
  let statusFilter = '';
  let semesterFilter = '';
  let genderFilter = '';
  let yearFilter = '';

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '/api/mahasiswa',
        data: function(d) {
          d.prodi_filter = prodiFilter;
          d.kelas_filter = kelasFilter;
          d.status_filter = statusFilter;
          d.semester_filter = semesterFilter;
          d.gender_filter = genderFilter;
          d.year_filter = yearFilter;
        },
        dataSrc: function (json) {
          console.log('Fetched data: ', json);

          if (json.filterOptions) {
            updateFilterDropdowns(json.filterOptions);
          }

          return json.data;
        }
      },
      columns: [
        { data: '' },
        { data: 'id' },
        { data: 'nrp' },
        { data: 'nama_mahasiswa' },
        { data: 'program_studi' },
        { data: 'kelas' },
        { data: 'jenis_kelamin' },
        { data: 'status' },
        { data: '' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          // For Checkboxes
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 4,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
          // For NRP
          targets: 2,
          searchable: true,
          orderable: true,
          responsivePriority: 5
        },
        {
          // For Nama
          targets: 3,
          searchable: true,
          orderable: true,
          responsivePriority: 3
        },
        {
          // For Program Studi
          targets: 4,
          searchable: false,
          orderable: true,
          responsivePriority: 5,
          render: function (data, type, full, meta) {
            var $program_studi = full['program_studi'];

            // Array of available badge classes
            var badgeClasses = [
              'bg-label-primary',
              'bg-label-success',
              'bg-label-danger',
              'bg-label-warning',
              'bg-label-info',
              'bg-label-secondary',
              'bg-label-dark'
            ];

            // Generate consistent color based on string hash
            function getHashColor(str) {
              var hash = 0;
              for (var i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
              }
              return badgeClasses[Math.abs(hash) % badgeClasses.length];
            }

            var badgeClass = getHashColor($program_studi);

            return (
              '<span class="badge ' + badgeClass + '">' + $program_studi + '</span>'
            );
          }
        },
        {
          // For Kelas
          targets: 5,
          searchable: false,
          orderable: true,
          responsivePriority: 6,
        },
        {
          // For Jenis Kelamin
          targets: 6,
          searchable: false,
          orderable: true,
          responsivePriority: 7,
          render: function (data, type, full, meta) {
            var $jenis_kelamin = full['jenis_kelamin'];
            var $kelamin_label = {
              L: { title: 'Laki-laki', class: 'bg-label-info' },
              P: { title: 'Perempuan', class: 'bg-label-success' }
            };
            if (typeof $kelamin_label[$jenis_kelamin] === 'undefined') {
              return data;
            }
            return (
              '<span class="badge ' +
              $kelamin_label[$jenis_kelamin].class +
              '">' +
              $kelamin_label[$jenis_kelamin].title +
              '</span>'
            );
          }
        },
        {
          // For Status
          targets: 7,
          searchable: false,
          orderable: true,
          responsivePriority: 8,
          render: function (data, type, full, meta) {
            var $status = full['status'];
            var $status_label = {
              Aktif: { title: 'Aktif', class: 'bg-label-success' },
              Cuti: { title: 'Cuti', class: 'bg-label-warning' },
              Keluar: { title: 'Keluar', class: 'bg-label-danger' }
            };
            if (typeof $status_label[$status] === 'undefined') {
              return data;
            }
            return (
              '<span class="badge ' + $status_label[$status].class + '">' + $status_label[$status].title + '</span>'
            );
          }
        },
        {
          // Actions
          targets: -1,
          title: 'Actions',
          orderable: false,
          searchable: false,
          render: function (data, type, full, meta) {
            return (
              // '<div class="d-inline-block">' +
              // '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>' +
              // '<ul class="dropdown-menu dropdown-menu-end m-0">' +
              // '<li><a href="javascript:;" class="dropdown-item">Details</a></li>' +
              // '<li><a href="javascript:;" class="dropdown-item">Archive</a></li>' +
              // '<div class="dropdown-divider"></div>' +
              // '<li><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></li>' +
              // '</ul>' +
              // '</div>' +
              '<div class="d-flex">' +
              '<a href="/admin/mahasiswa/' + full.id + '/edit" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit"><i class="ti ti-pencil ti-md"></i></a>' +
              '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-destroy"><i class="ti ti-trash ti-md"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'asc']],
      dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 10,
      lengthMenu: [10, 25, 50, 75, 100],
      language: {
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-primary dropdown-toggle me-4 waves-effect waves-light border-none',
          text: '<i class="ti ti-file-export ti-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-1" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [3, 4, 5, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [3, 4, 5, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [3, 4, 5, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-description me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [3, 4, 5, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-1" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [3, 4, 5, 6, 7],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        {
          text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
          className: 'create-new btn btn-primary waves-effect waves-light',
          action: function () {
            window.location = '/admin/mahasiswa/create';
          }
        }
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['full_name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      },
      initComplete: function (settings, json) {
        $('.card-header').after('<hr class="my-0">');

        var filterHtml = '<div class="filter-container p-6">' + getFilterHTML() + '</div>';
        $('.datatables-basic').closest('.card-datatable').before(filterHtml);

        initializeFilterEvents();

        loadFilterOptions();
      }
    });
    $('div.head-label').html('<h5 class="card-title mb-0">Tabel Mahasiswa</h5>');
  }

  // Function to generate filter HTML
  function getFilterHTML() {
    return `
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 text-muted">
          <i class="ti ti-filter me-2"></i>Filter Data
        </h6>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="reset-filters">
          <i class="ti ti-refresh me-1"></i>Reset
        </button>
      </div>
      <div class="row g-3">
        <div class="col-md-4">
          <label for="prodi-filter" class="form-label fw-medium">
            <i class="ti ti-school me-1"></i>Program Studi
          </label>
          <select id="prodi-filter" class="form-select">
            <option value="">Semua Program Studi</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="kelas-filter" class="form-label fw-medium">
            <i class="ti ti-users me-1"></i>Kelas
          </label>
          <select id="kelas-filter" class="form-select">
            <option value="">Semua Kelas</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="status-filter" class="form-label fw-medium">
            <i class="ti ti-user-check me-1"></i>Status
          </label>
          <select id="status-filter" class="form-select">
            <option value="">Semua Status</option>
          </select>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-4">
          <label for="semester-filter" class="form-label fw-medium">
            <i class="ti ti-calendar me-1"></i>Semester
          </label>
          <select id="semester-filter" class="form-select">
            <option value="">Semua Semester</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
            <option value="7">Semester 7</option>
            <option value="8">Semester 8</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="gender-filter" class="form-label fw-medium">
            <i class="ti ti-gender-male me-1"></i>Jenis Kelamin
          </label>
          <select id="gender-filter" class="form-select">
            <option value="">Semua Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
        <div class="col-md-4">
          <label for="year-filter" class="form-label fw-medium">
            <i class="ti ti-calendar-time me-1"></i>Tahun Masuk
          </label>
          <select id="year-filter" class="form-select">
            <option value="">Semua Tahun</option>
          </select>
        </div>
      </div>
    `;
  }

  // Function to initialize filter events
  function initializeFilterEvents() {
    // Program Studi filter change
    $('#prodi-filter').on('change', function() {
      prodiFilter = $(this).val();
      kelasFilter = ''; // Reset kelas filter
      $('#kelas-filter').val(''); // Reset kelas dropdown

      // Load kelas for selected prodi
      if (prodiFilter) {
        loadKelasForProdi(prodiFilter);
      } else {
        loadAllKelas();
      }

      // Reload table
      dt_basic.ajax.reload();
    });

    // Kelas filter change
    $('#kelas-filter').on('change', function() {
      kelasFilter = $(this).val();
      dt_basic.ajax.reload();
    });

    // Status filter change
    $('#status-filter').on('change', function() {
      statusFilter = $(this).val();
      dt_basic.ajax.reload();
    });

    $('#semester-filter').on('change', function() {
      semesterFilter = $(this).val();
      dt_basic.ajax.reload();
    });

    $('#gender-filter').on('change', function() {
      genderFilter = $(this).val();
      dt_basic.ajax.reload();
    });

    $('#year-filter').on('change', function() {
      yearFilter = $(this).val();
      dt_basic.ajax.reload();
    });

    // Reset filters
    $('#reset-filters').on('click', function() {
      resetAllFilters();
    });
  }

  // Function to reset all filters
  function resetAllFilters() {
    prodiFilter = '';
    kelasFilter = '';
    statusFilter = '';
    semesterFilter = '';
    genderFilter = '';
    yearFilter = '';

    $('#prodi-filter').val('');
    $('#kelas-filter').val('');
    $('#status-filter').val('');
    $('#semester-filter').val('');
    $('#gender-filter').val('');
    $('#year-filter').val('');

    loadAllKelas();
    dt_basic.ajax.reload();
  }

  // Function to load filter options
  function loadFilterOptions() {
    $.ajax({
      url: '/api/mahasiswa/filter-options',
      method: 'GET',
      success: function(response) {
        if (response.success) {
          updateFilterDropdowns(response.data);
        }
      },
      error: function(xhr) {
        console.error('Error loading filter options:', xhr);
      }
    });
  }

  // Function to load kelas for specific prodi
  function loadKelasForProdi(prodiId) {
    $.ajax({
      url: `/api/kelas-by-prodi/${prodiId}`,
      method: 'GET',
      success: function(response) {
        if (response.success) {
          updateKelasDropdown(response.data);
        }
      },
      error: function(xhr) {
        console.error('Error loading kelas:', xhr);
      }
    });
  }

  // Function to load all kelas
  function loadAllKelas() {
    $.ajax({
      url: '/api/mahasiswa/filter-options',
      method: 'GET',
      success: function(response) {
        if (response.success) {
          updateKelasDropdown(response.data.kelas);
        }
      },
      error: function(xhr) {
        console.error('Error loading all kelas:', xhr);
      }
    });
  }

  // Function to update filter dropdowns
  function updateFilterDropdowns(data) {
    // Update Program Studi
    if (data.program_studi) {
      const prodiSelect = $('#prodi-filter');
      prodiSelect.empty().append('<option value="">Semua Program Studi</option>');
      data.program_studi.forEach(function(prodi) {
        prodiSelect.append(`<option value="${prodi.id}">${prodi.nama}</option>`);
      });
    }

    // Update Kelas
    if (data.kelas) {
      updateKelasDropdown(data.kelas);
    }

    // Update Status
    if (data.status) {
      const statusSelect = $('#status-filter');
      statusSelect.empty().append('<option value="">Semua Status</option>');
      data.status.forEach(function(status) {
        statusSelect.append(`<option value="${status.value}">${status.label}</option>`);
      });
    }

    // Update Tahun Masuk
    if (data.years) {
    const yearSelect = $('#year-filter');
    yearSelect.empty().append('<option value="">Semua Tahun</option>');
    data.years.forEach(function(year) {
      yearSelect.append(`<option value="${year.value}">${year.label}</option>`);
    });
  }
  }

  // Function to update kelas dropdown
  function updateKelasDropdown(kelasData) {
    const kelasSelect = $('#kelas-filter');
    kelasSelect.empty().append('<option value="">Semua Kelas</option>');

    kelasData.forEach(function(kelas) {
      const label = kelas.prodi_nama ? `${kelas.pararel} (${kelas.prodi_nama})` : kelas.pararel;
      kelasSelect.append(`<option value="${kelas.id}">${label}</option>`);
    });
  }

  // Delete record with route destroy
  $('.datatables-basic tbody').on('click', '.item-destroy', function () {
    var data = dt_basic.row($(this).parents('tr')).data();
    if (!data || typeof data.id === 'undefined') {
      console.error('Error: Tidak dapat menemukan ID dari data baris.', data);
      Swal.fire({
        title: 'Error!',
        text: 'Tidak dapat menemukan ID data untuk dihapus.',
        icon: 'error'
      });
      return;
    }
    var id = data.id;

    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: 'Anda tidak akan dapat mengembalikan ini!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#7367F0',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya',
      cancelButtonText: 'Batal' // Tambahkan teks untuk tombol batal jika diinginkan
    }).then(result => {
      if (result.isConfirmed) {
        // Send DELETE request to the se qrver
        $.ajax({
          url: '/api/mahasiswa/destroy/' + id, // Pastikan URL ini benar
          type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Contoh untuk Laravel
          },
          success: function (response) {
            Swal.fire({
              title: 'Dihapus!',
              text: response.message || 'Data Tahun Ajar berhasil dihapus.',
              icon: 'success'
            });
            // Refresh the DataTable
            if (typeof dt_basic !== 'undefined') {
              dt_basic.ajax.reload(null, false); // false agar paging tidak reset
            }
          },
          error: function (xhr) {
            // Coba ambil pesan error dari responseJSON, jika tidak ada, tampilkan pesan umum
            let errorMessage = 'Gagal menghapus data.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
              // Kadang error tidak dalam format JSON
              try {
                const parsedError = JSON.parse(xhr.responseText);
                if (parsedError && parsedError.message) {
                  errorMessage = parsedError.message;
                }
              } catch (e) {
                // Biarkan errorMessage default jika parsing gagal
              }
            }

            Swal.fire({
              title: 'Gagal!',
              text: errorMessage,
              icon: 'error'
            });
          }
        });
      }
    });
  });
});
