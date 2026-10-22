/**
 * AppCrud - Helper reusable untuk CRUD DataTables & AJAX Form
 */
var AppCrud = (function ($) {
  'use strict';

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

    var dtDefaultOptions = {
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      select: {
        style: 'single'
      },
      ajax: {
        url: config.baseUrl + '/datatables',
        type: 'POST'
      },
      columnDefs: [
        { targets: 0, visible: false, searchable: false },
        { targets: 1, orderable: false, searchable: false, className: 'text-center' }
      ]
    };

    var dtConfig = $.extend(true, {}, dtDefaultOptions, config.dataTableOptions);
    var table = $(config.tableSelector).DataTable(dtConfig);

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
            Swal.fire('Error', msg, 'error');
          }
        });
      });
    }

    if (config.btnDeleteSelector) {
      $(config.btnDeleteSelector).on('click', function () {
        if (!selectedId) return;

        Swal.fire({
          title: 'Hapus ' + config.entityName + '?',
          text: 'Data akan dihapus.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
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
                  Swal.fire({
                    icon: 'success',
                    title: 'Terhapus!',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                  });
                }
              },
              error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menghapus data.';
                Swal.fire('Error', msg, 'error');
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
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
              });
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
              Swal.fire('Error', msg, 'error');
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
    init: init
  };
})(jQuery);
