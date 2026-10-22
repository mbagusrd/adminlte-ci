/**
 * AppCrud - Helper reusable untuk CRUD DataTables & SweetAlert2
 */
var AppCrud = (function ($) {
  'use strict';

  var alert = {
    success: function (message, title) {
      return Swal.fire({
        icon: 'success',
        title: title || 'Berhasil',
        text: message,
        timer: 1500,
        showConfirmButton: false
      });
    },

    error: function (message, title) {
      return Swal.fire({
        icon: 'error',
        title: title || 'Error',
        text: message || 'Terjadi kesalahan sistem.'
      });
    },

    confirm: function (options) {
      var config = $.extend({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin?',
        icon: 'warning',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal'
      }, options);

      return Swal.fire({
        title: config.title,
        text: config.text,
        icon: config.icon,
        showCancelButton: true,
        confirmButtonColor: config.confirmButtonColor,
        cancelButtonColor: config.cancelButtonColor,
        confirmButtonText: config.confirmButtonText,
        cancelButtonText: config.cancelButtonText
      });
    }
  };

  function createDataTable(selector, options) {
    var defaults = {
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      select: {
        style: 'single'
      },
      columnDefs: [
        { targets: 0, visible: false, searchable: false },
        { targets: 1, orderable: false, searchable: false, className: 'text-center' }
      ]
    };

    var config = $.extend(true, {}, defaults, options);
    return $(selector).DataTable(config);
  }

  function createSelect2(selector, options) {
    var defaults = {
      theme: 'bootstrap4',
      width: '100%',
      placeholder: 'Pilih data',
      allowClear: true,
      ajax: {
        url: '',
        type: 'GET',
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term || '',
            page: params.page || 1
          };
        },
        processResults: function (data, params) {
          params.page = params.page || 1;
          var items = data.items || data.data || data;
          var more = false;
          if (data.total_count !== undefined) {
            more = (params.page * 10) < data.total_count;
          } else if (data.more !== undefined) {
            more = data.more;
          }
          return {
            results: items,
            pagination: {
              more: more
            }
          };
        },
        cache: true
      }
    };

    var config = $.extend(true, {}, defaults, options);
    return $(selector).select2(config);
  }

  function init(options) {
    var config = $.extend(true, {
      baseUrl: '',
      tableSelector: '',
      formSelector: '',
      modalSelector: '',
      modalTitleSelector: '#modal-title',
      btnAddSelector: '#btn-add',
      btnEditSelector: '#btn-edit',
      btnDeleteSelector: '#btn-delete',
      btnSaveSelector: '#btn-save',
      idInputSelector: '',
      entityName: 'Data',
      primaryKey: 'id',
      onPopulateForm: null,
      onResetForm: null,
      dataTableOptions: {}
    }, options);

    var selectedId = null;

    var dtOptions = $.extend(true, {
      ajax: {
        url: config.baseUrl + '/datatables',
        type: 'POST'
      }
    }, config.dataTableOptions);

    var table = createDataTable(config.tableSelector, dtOptions);

    function updateActionButtons() {
      var hasSelected = selectedId !== null;
      if (config.btnEditSelector) {
        $(config.btnEditSelector).prop('disabled', !hasSelected);
      }
      if (config.btnDeleteSelector) {
        $(config.btnDeleteSelector).prop('disabled', !hasSelected);
      }
    }

    table.on('select', function (e, dt, type, indexes) {
      if (type === 'row') {
        var rowData = table.rows(indexes).data()[0];
        selectedId = rowData[0];
        updateActionButtons();
      }
    });

    table.on('deselect', function (e, dt, type, indexes) {
      if (type === 'row') {
        selectedId = null;
        updateActionButtons();
      }
    });

    table.on('draw', function () {
      selectedId = null;
      updateActionButtons();
    });

    function resetForm() {
      var $form = $(config.formSelector);
      if ($form.length) {
        $form[0].reset();
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');
      }
      if (config.idInputSelector) {
        $(config.idInputSelector).val('');
      }
      if (typeof config.onResetForm === 'function') {
        config.onResetForm($form);
      }
    }

    if (config.btnAddSelector) {
      $(config.btnAddSelector).on('click', function () {
        resetForm();
        if (config.modalTitleSelector) {
          $(config.modalTitleSelector).text('Tambah ' + config.entityName);
        }
        $(config.modalSelector).modal('show');
      });
    }

    if (config.btnEditSelector) {
      $(config.btnEditSelector).on('click', function () {
        if (!selectedId) return;
        resetForm();

        $.ajax({
          url: config.baseUrl + '/find/' + selectedId,
          type: 'GET',
          dataType: 'json',
          success: function (res) {
            if (res.status && res.data) {
              if (typeof config.onPopulateForm === 'function') {
                config.onPopulateForm(res.data, $(config.formSelector));
              } else {
                $.each(res.data, function (key, val) {
                  var $field = $(config.formSelector).find('[name="' + key + '"]');
                  if ($field.length) {
                    $field.val(val);
                  }
                });
              }
              if (config.modalTitleSelector) {
                $(config.modalTitleSelector).text('Edit ' + config.entityName);
              }
              $(config.modalSelector).modal('show');
            }
          },
          error: function (xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal mengambil data.';
            alert.error(msg);
          }
        });
      });
    }

    if (config.btnDeleteSelector) {
      $(config.btnDeleteSelector).on('click', function () {
        if (!selectedId) return;

        alert.confirm({
          title: 'Hapus ' + config.entityName + '?',
          text: 'Data akan dihapus.',
          confirmButtonText: 'Ya, Hapus!'
        }).then(function (result) {
          if (result.isConfirmed) {
            $.ajax({
              url: config.baseUrl + '/delete/' + selectedId,
              type: 'POST',
              dataType: 'json',
              success: function (res) {
                if (res.status) {
                  selectedId = null;
                  updateActionButtons();
                  table.ajax.reload(null, false);
                  alert.success(res.message, 'Terhapus!');
                }
              },
              error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menghapus data.';
                alert.error(msg);
              }
            });
          }
        });
      });
    }

    if (config.formSelector) {
      $(config.formSelector).on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');

        var $btn = $(config.btnSaveSelector);
        var origText = $btn.text();
        $btn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
          url: config.baseUrl + '/save',
          type: 'POST',
          data: $form.serialize(),
          dataType: 'json',
          success: function (res) {
            $btn.prop('disabled', false).text(origText);
            if (res.status) {
              $(config.modalSelector).modal('hide');
              selectedId = null;
              updateActionButtons();
              table.ajax.reload(null, false);
              alert.success(res.message, 'Berhasil');
            }
          },
          error: function (xhr) {
            $btn.prop('disabled', false).text(origText);
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
              var msg = xhr.responseJSON.message;
              var $input = $form.find('input[type="text"]:visible, select:visible, textarea:visible').first();
              $input.addClass('is-invalid');
              $input.siblings('.invalid-feedback').text(msg);
            } else {
              var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
              alert.error(msg);
            }
          }
        });
      });
    }

    return {
      table: table,
      reload: function (resetPaging) {
        selectedId = null;
        updateActionButtons();
        table.ajax.reload(null, resetPaging === false ? false : true);
      },
      getSelectedId: function () {
        return selectedId;
      }
    };
  }

  return {
    init: init,
    createDataTable: createDataTable,
    createSelect2: createSelect2,
    alert: alert
  };
})(jQuery);
