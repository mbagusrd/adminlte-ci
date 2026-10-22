<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Datatables & Plugins CSS -->
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/datatables-select/css/select.bootstrap4.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'); ?>">

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
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-select/js/dataTables.select.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/sweetalert2/sweetalert2.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/app-crud.js'); ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  AppCrud.init({
    baseUrl: '<?php echo site_url("groups"); ?>',
    entityName: 'Group',
    tableSelector: '#table-groups',
    modalSelector: '#modal-form',
    formSelector: '#form-group',
    idInputSelector: '#group-id',
    dataTableOptions: {
      order: [[2, 'asc']]
    }
  });
});
</script>
