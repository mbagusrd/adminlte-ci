<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="setting_grup nav-link active" data-toggle="pill" href="#setting_grup" role="tab"><i class="fas fa-users"></i> Grup</a>
            </li>
            <li class="nav-item">
                <a class="setting_user nav-link" data-toggle="pill" href="#setting_user" role="tab"><i class="fas fa-user"></i> User</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="setting_grup" role="tabpanel">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button class="btn btn-success btn-small" onclick="add_grup()">
                            <i class="fas fa-plus"></i> Tambah</button>
                        <button class="btn btn-warning btn-small" onclick="edit_grup()">
                            <i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-small" onclick="del_grup()">
                            <i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabel_grup" class="table table-bordered table-sm table-hover table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="50px">No.</th>
                                    <th>Nama Grup</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="setting_user" role="tabpanel">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button class="btn btn-success btn-small" onclick="add_user()">
                            <i class="fas fa-plus"></i> Tambah</button>
                        <button class="btn btn-warning btn-small" onclick="edit_user()">
                            <i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-small" onclick="del_user()">
                            <i class="fas fa-trash"></i> Hapus</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="tabel_user" class="table table-bordered table-sm table-hover table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="50px">No.</th>
                                    <th>Nama User</th>
                                    <th>Username</th>
                                    <th>Nama Grup</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</div>
<!-- Modal -->
<div class="modal fade" id="modal-grup">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Grup</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="fm_modal_grup" onsubmit="return false">
                    <div class="form-group">
                        <label>Nama Grup</label>
                        <input type="text" id="nm_grup" name="nm_grup" class="form-control" required="" style="text-transform: uppercase;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_grup()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-user">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="fm_modal_user" onsubmit="return false">
                    <div class="form-group">
                        <label>Nama User</label>
                        <input type="text" id="nama" name="nama" class="form-control" required="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="username" name="username" class="form-control" required="" style="text-transform: uppercase;" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" id="passwd" name="passwd" class="form-control">
                        <small>Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                    <div class="form-group">
                        <label>Grup</label>
                        <select id="id_grup" name="id_grup" class="form-control select2" required="" style="width: 100%">
                            <!-- <option value="text">text</option> -->
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="simpan_user()">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
edit_mode = 0;

$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');

    if ($(this).hasClass("setting_grup")) {
        setTimeout(function() {
            get_grup();
        }, 300);
    }

    if ($(this).hasClass("setting_user")) {
        setTimeout(function() {
            get_user();
        }, 300);
    }
});

$('#modal-grup').on('shown.bs.modal', function() {
    $('#fm_modal_grup').valid();
    $('#fm_modal_grup #nm_grup').focus();
});

$('#modal-user').on('shown.bs.modal', function() {
    $('#fm_modal_user').valid();
    $('#fm_modal_user #nama').focus();
});

function get_grup() {
    url_tabel_grup = situs + "setting/get_grup/1";
    id_tabel_grup = "tabel_grup";

    init_datatable_server(id_tabel_grup, url_tabel_grup, [{
        data: "nomor",
        className: "text-right",
        width: "50px"
    }, {
        data: "nm_grup"
    }]);
}

function get_user() {
    url_tabel_user = situs + "setting/get_user";
    id_tabel_user = "tabel_user";

    init_datatable_server(id_tabel_user, url_tabel_user, [{
        data: "nomor",
        className: "text-right",
        width: "50px"
    }, {
        data: "nama"
    }, {
        data: "username"
    }, {
        data: "nm_grup"
    }]);
}

setTimeout(function() {
    get_grup();
}, 700);

function add_grup() {
    dataUrl = situs + 'setting/add_grup';
    edit_mode = 0;

    clear_form('fm_modal_grup');

    $('#modal-grup').modal('show');

}

function edit_grup() {
    row = $('#tabel_grup').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'setting/edit_grup/' + row.id_grup;
        edit_mode = 1;

        set_form('fm_modal_grup', row);

        $('#modal-grup').modal('show');
    } else {
        alert("Pilih data di tabel");
    }
}

function simpan_grup() {
    validasi = $('#fm_modal_grup').valid();

    if (validasi) {
        $.ajax({
            url: dataUrl,
            data: $('#fm_modal_grup').serialize(),
            dataType: "JSON",
            type: "POST",
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                pesan(res.msg, 1);

                if (res.status) {
                    if (edit_mode) {
                        $('#modal-grup').modal('hide');
                    } else {
                        clear_form('fm_modal_grup');
                        $('#fm_modal_grup').valid();
                        $('#fm_modal_grup #nm_grup').focus();
                    }

                    get_grup();
                }
            }
        });
    }
}

function del_grup() {
    row = $('#tabel_grup').DataTable().row({
        selected: true
    }).data();

    if (row) {
        prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

        if (prompt) {
            $.ajax({
                url: situs + "setting/del_grup",
                data: "id_grup=" + row.id_grup,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_grup();
                    }
                }
            });
        }
    } else {
        alert("Pilih data di tabel");
    }
}

init_select2_ajax("#fm_modal_user #id_grup", situs + 'setting/select_grup');

function add_user() {
    dataUrl = situs + 'setting/add_user';
    edit_mode = 0;

    clear_form('fm_modal_user');

    $('#modal-user').modal('show');

}

function edit_user() {
    row = $('#tabel_user').DataTable().row({
        selected: true
    }).data();

    if (row) {
        dataUrl = situs + 'setting/edit_user/' + row.id_user;
        edit_mode = 1;

        set_form('fm_modal_user', row);
        set_select2_value("#fm_modal_user #id_grup", row.id_grup, row.nm_grup);

        $('#modal-user').modal('show');
    } else {
        alert("Pilih data di tabel");
    }
}

function simpan_user() {
    validasi = $('#fm_modal_user').valid();

    if (validasi) {
        $.ajax({
            url: dataUrl,
            data: $('#fm_modal_user').serialize(),
            dataType: "JSON",
            type: "POST",
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                pesan(res.msg, 1);

                if (res.status) {
                    if (edit_mode) {
                        $('#modal-user').modal('hide');
                    } else {
                        clear_form('fm_modal_user');
                        $("#fm_modal_user").valid();
                        $('#fm_modal_user #nama').focus();
                    }

                    get_user();
                }
            }
        });
    }
}

function del_user() {
    row = $('#tabel_user').DataTable().row({
        selected: true
    }).data();

    if (row) {
        prompt = confirm("Anda Yakin Ingin Menghapus Data Ini?");

        if (prompt) {
            $.ajax({
                url: situs + "setting/del_user",
                data: "id_user=" + row.id_user,
                dataType: "JSON",
                type: "POST",
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    pesan(res.msg, 1);

                    if (res.status) {
                        get_user();
                    }
                }
            });
        }
    } else {
        alert("Pilih data di tabel");
    }
}
</script>