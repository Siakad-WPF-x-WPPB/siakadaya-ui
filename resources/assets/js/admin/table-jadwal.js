/**
 * DataTables Basic
 */

'use strict';

let fv, offCanvasEl;

// datatable (jquery)
$(function () {
  var dt_basic_table = $('.datatables-basic'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    dt_basic;

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    dt_basic = dt_basic_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '/api/jadwal',
        dataSrc: function (json) {
          // console.log('Fetched data: ', json);
          return json.data;
        }
      },
      columns: [
        { data: '' },
        { data: 'id' },
        { data: 'kelas_display' },
        { data: 'dosen' },
        { data: 'matakuliah' },
        { data: 'ruangan' },
        {
          data: null,
          render: function(data, type, row) {
            return row.hari + ' ' + row.waktu;
          }
        },
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
          // For Kelas
          targets: 2,
          searchable: true,
          orderable: true,
          responsivePriority: 5
        },
        {
          // For Nama Dosen
          targets: 3,
          searchable: true,
          orderable: true,
          responsivePriority: 3,
        },
        {
          // For Matakuliah
          targets: 4,
          searchable: true,
          orderable: true,
          responsivePriority: 5
        },
        {
          // For Ruangan
          targets: 5,
          searchable: true,
          orderable: true,
          responsivePriority: 5
        },
        {
          // For Hari, Jam Mulai, Jam Selesai
          targets: 6,
          searchable: true,
          orderable: true,
          responsivePriority: 5
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
              '<a href="/admin/jadwal-kuliah/' + full.id + '/edit" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit"><i class="ti ti-pencil ti-md"></i></a>' +
              '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-destroy"><i class="ti ti-trash ti-md"></i></a>' +
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
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
                columns: [2, 3, 4, 5, 6, 7],
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
                columns: [2, 3, 4, 5, 6, 7],
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
                columns: [2, 3, 4, 5, 6, 7],
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
                columns: [2, 3, 4, 5, 6, 7],
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
                columns: [2, 3, 4, 5, 6, 7],
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
            window.location = '/admin/jadwal-kuliah/create';
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
        $('.dt-action-buttons').addClass('pt-0');
        $('.dataTables_filter').addClass('me-3 ps-0');
      }
    });
    $('div.head-label').html('<h5 class="card-title mb-0">Tabel Jadwal</h5>');
  }

  // Delete record with route destroy
  $('.datatables-basic tbody').on('click', '.item-destroy', function () {
    var data = dt_basic.row($(this).parents('tr')).data();
    var id = data.id; // Get the ID of the record to delete

    // Show confirmation dialog
    if (confirm('Are you sure you want to delete this record?')) {
      // Send DELETE request to the server
      $.ajax({
        url: '/api/jadwal/destroy/' + id,
        type: 'DELETE',
        success: function (response) {
          // Show success message
          // toastr.success(response.message || 'Data kelas berhasil dihapus.');
          alert(response.message || 'Data kelas berhasil dihapus.');

          // Refresh the DataTable
          dt_basic.ajax.reload(null, false);
        },
        error: function (xhr) {
          // Show error message
          // toastr.error(xhr.responseJSON?.message || 'Failed to delete record');
          alert('Failed to delete record: ' + (xhr.responseJSON?.message || error));
        }
      });
    }
  });
});
