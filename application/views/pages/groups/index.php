<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Datatables & Plugins CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-select/css/select.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') ?>">

<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Daftar Groups</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-sm btn-primary" id="btn-add">
        <i class="fas fa-plus"></i> Tambah
      </button>
      <button type="button" class="btn btn-sm btn-info" id="btn-edit" disabled>
        <i class="fas fa-edit"></i> Edit
      </button>
      <button type="button" class="btn btn-sm btn-danger" id="btn-delete" disabled>
        <i class="fas fa-trash"></i> Hapus
      </button>
    </div>
  </div>
  <div class="card-body">
    <table id="table-groups" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
      <thead>
        <tr>
          <th>ID</th>
          <th style="width: 5%">No</th>
          <th>Nama Group</th>
          <th style="width: 25%">Dibuat</th>
          <th style="width: 25%">Diubah</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-group">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Tambah Group</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="group-id">
          <div class="form-group">
            <label for="group-name">Nama Group <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" id="group-name" maxlength="50" placeholder="Masukkan nama group" required>
            <div class="invalid-feedback" id="group-name-error"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Datatables & Plugins JS -->
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-select/js/dataTables.select.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var baseUrl = '<?= site_url("groups") ?>';
  var selectedId = null;

  var table = $('#table-groups').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    select: {
      style: 'single'
    },
    ajax: {
      url: baseUrl + '/datatables',
      type: 'POST'
    },
    columnDefs: [
      { targets: 0, visible: false, searchable: false },
      { targets: 1, orderable: false, searchable: false, className: 'text-center' }
    ],
    order: [[2, 'asc']]
  });

  function updateActionButtons() {
    var hasSelected = selectedId !== null;
    $('#btn-edit').prop('disabled', !hasSelected);
    $('#btn-delete').prop('disabled', !hasSelected);
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
    $('#form-group')[0].reset();
    $('#group-id').val('');
    $('#group-name').removeClass('is-invalid');
    $('#group-name-error').text('');
  }

  $('#btn-add').on('click', function () {
    resetForm();
    $('#modal-title').text('Tambah Group');
    $('#modal-form').modal('show');
  });

  $('#btn-edit').on('click', function () {
    if (!selectedId) return;
    resetForm();

    $.ajax({
      url: baseUrl + '/find/' + selectedId,
      type: 'GET',
      dataType: 'json',
      success: function (res) {
        if (res.status && res.data) {
          $('#group-id').val(res.data.id);
          $('#group-name').val(res.data.name);
          $('#modal-title').text('Edit Group');
          $('#modal-form').modal('show');
        }
      },
      error: function (xhr) {
        var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal mengambil data.';
        Swal.fire('Error', msg, 'error');
      }
    });
  });

  $('#btn-delete').on('click', function () {
    if (!selectedId) return;

    Swal.fire({
      title: 'Hapus Group?',
      text: 'Data akan dihapus secara soft delete.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then(function (result) {
      if (result.isConfirmed) {
        $.ajax({
          url: baseUrl + '/delete/' + selectedId,
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

  $('#form-group').on('submit', function (e) {
    e.preventDefault();
    $('#group-name').removeClass('is-invalid');
    $('#group-name-error').text('');

    var $btn = $('#btn-save');
    $btn.prop('disabled', true).text('Menyimpan...');

    $.ajax({
      url: baseUrl + '/save',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (res) {
        $btn.prop('disabled', false).text('Simpan');
        if (res.status) {
          $('#modal-form').modal('hide');
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
        $btn.prop('disabled', false).text('Simpan');
        if (xhr.status === 422 && xhr.responseJSON) {
          $('#group-name').addClass('is-invalid');
          $('#group-name-error').text(xhr.responseJSON.message);
        } else {
          var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
          Swal.fire('Error', msg, 'error');
        }
      }
    });
  });
});
</script>
