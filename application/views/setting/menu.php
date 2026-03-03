<div class="card card-primary card-outline card-outline-tabs mb-8">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="entri_menu nav-link active" data-toggle="pill" href="#entri_menu"><i class="fas fa-list"></i> Entri Menu</a>
            </li>
            <li class="nav-item">
                <a class="setting_menu nav-link" data-toggle="pill" href="#setting_menu"><i class="fas fa-cog"></i> Setting Menu</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="entri_menu">
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label>Tambah Folder Menu</label>
                        <div class="form-inline">
                            <form id="form_tambah_folder_menu" onsubmit="tambah_all_menu_folder();return false" class="d-flex flex-wrap">
                                <input type="text" id="nm_folder" name="nm_folder" class="form-control mr-2 mb-2" placeholder="Nama Folder" autocomplete="off" />
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Folder</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-7 mb-3">
                        <label>Tambah Menu</label>
                        <div class="form-inline">
                            <form id="form_tambah_all_menu_baru" onsubmit="tambah_all_menu_baru(); return false;" class="d-flex flex-wrap">
                                <input type="text" name="nm_menu" id="nm_menu" class="form-control mr-2 mb-2" placeholder="Nama Menu" autocomplete="off">
                                <input type="text" name="link" id="link" class="form-control mr-2 mb-2" placeholder="Link Menu" autocomplete="off">
                                <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Menu</button>
                            </form>
                        </div>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button class="btn btn-primary" onclick="simpan_all_menu()"><i class="fa fa-save"></i> Update Menu</button>
                        <small class="float-right mt-2">Untuk mengedit menu silahkan ubah nama atau link-nya kemudian klik tombol update menu</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="form_all_menu" onsubmit="return false;">
                            <table class="table table-bordered table-sm table-hover table-striped" id="tabel_all_menu" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="10%">No.</th>
                                        <th width="10%">Jenis Menu</th>
                                        <th width="35%">Nama Menu</th>
                                        <th width="35%">Link</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="setting_menu">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label>Pilih Grup:</label>
                        <select id="grup" class="select2" onchange="get_menu_layout('0', this.value, '0', '')">
                            <?php echo $opt_grup; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Import Menu dari Grup:</label>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <select id="menuGrup" class="select2">
                                    <?php echo $opt_grup; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-success" onclick="copy_menu()"><i class="fa fa-copy"></i> Ambil Menu</button>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item" id="teks_level_0"><i class="fa fa-list"></i> Main Menu</li>
                    <li class="breadcrumb-item" id="teks_level_1" style="visibility: hidden;"></li>
                    <li class="breadcrumb-item" id="teks_level_2" style="visibility: hidden;"></li>
                </ul>
                <div class="row">
                    <input type="hidden" name="id_parent" id="id_parent" value="0">
                    <input type="hidden" name="level" id="level" value="0">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="h5">Active Menu</span>
                                        <button class="btn btn-primary float-right" onclick="simpan_active_menu()"><i class="fa fa-save"></i> Simpan Order</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <small>ubah order menu, lalu klik tombol "Simpan Order"</small>
                                <form id="form_active_menu" onsubmit="return false"></form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-3">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <span class="h5">Inactive Menu</span>
                                <button class="btn btn-success float-right" onclick="tambah_active_menu()"><i class="fa fa-plus"></i> Tambah Menu</button>
                            </div>
                        </div>
                        <div id="div_inactive_menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
url_get_active_menu = "";
url_get_inactive_menu = "";
url_tambah_active_menu = "";
url_simpan_active_menu = "";
url_hapus_active_menu = "";
edit_mode = 0;
all_menu_table = null;
grup_select2 = null;
menu_grup_select2 = null;

$('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');

    if ($(this).hasClass("entri_menu")) {
        setTimeout(function() {
            get_all_menu();
        }, 300);
    }

    if ($(this).hasClass("setting_menu")) {
        grup_select2 = init_select2("#grup.select2");
        menu_grup_select2 = init_select2("#menuGrup.select2");

        grup_select2.val("").trigger('change');
        menu_grup_select2.val("").trigger('change');

        setTimeout(function() {}, 300);
    }
});

$(document).ready(function() {
    setTimeout(function() {
        get_all_menu();
    }, 700);
});

function get_all_menu() {
    url_tabel = situs + "setting/get_all_menu";
    id_tabel_menu = "tabel_all_menu";

    all_menu_table = init_datatable_server(id_tabel_menu, url_tabel, [{
        data: "nomor",
        className: "text-right"
    }, {
        data: "jns_menu",
        render: function(data, type, row, meta) {
            return (data == "1") ? "Folder" : "Menu";
        }
    }, {
        data: "nm_menu",
        render: function(data, type, row, meta) {
            return "<input type='hidden' name='id_menu[]' value='" + row.id_menu + "'><input type='text' id='nm_menu_" + row.id_menu + "' name='nm_menu[]' value='" + data + "' class='form-control' style='width: 90%' />";
        }
    }, {
        data: "link",
        render: function(data, type, row, meta) {
            if (row.jns_menu == "2") {
                return "<input type='text' id='link_" + row.id_menu + "' name='link[]' value='" + data + "' class='form-control' style='width: 90%' />";
            } else {
                return "<input type='hidden' name='link[]' value=''>";
            }
        }
    }, {
        data: null,
        defaultContents: "",
        className: "text-center",
        render: function(data, type, row, meta) {
            return "<button class='btn btn-danger' onclick=\"hapus_menu('" + row.id_menu + "')\"><i class='fa fa-trash'></i></button>";
        }
    }], {
        scrollY: 450,
        scrollX: false,
        select: false
    });
}

function simpan_all_menu() {
    data_form = $("#form_all_menu").serialize();

    $.ajax({
        url: situs + "setting/simpan_all_menu",
        data: data_form,
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            proses();
        },
        success: function(res) {
            pesan(res.msg, 1);

            get_all_menu();
        }
    });
}

function hapus_menu(var_id_menu) {
    konfirmasi = confirm("Anda yakin hapus data ini?");

    if (konfirmasi) {
        $.ajax({
            url: situs + "setting/hapus_menu",
            data: "id_menu=" + var_id_menu,
            type: 'post',
            dataType: 'json',
            success: function(res) {
                pesan(res.msg, 1);

                get_all_menu();
            }
        });
    }
}

function open_controller_modal() {
    get_new_controller();

    $("#modal_controller").modal('show');
}

function get_new_controller() {
    $.ajax({
        url: situs + "setting/get_new_controller",
        success: function(res) {
            $("#div_controller_list").html(res);
        }
    });
}

function tambah_all_menu() {
    konfirmasi = confirm("Anda yakin data sudah benar?");

    if (konfirmasi) {
        data = "";

        banyak_data = $("#tabel_new_controller").DataTable().rows().data().length;

        for (i = 1; i <= banyak_data; i++) {
            if ($("#new_nm_menu_" + i).val()) {
                data += (i == 1) ? "" : "&";
                data += "link[]=" + $("#new_link_" + i).val() + "&nm_menu[]=" + ($("#new_nm_menu_" + i).val());
            }
        }

        $.ajax({
            url: situs + "setting/tambah_all_menu",
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(res) {
                $("#modal_controller").modal('hide');

                get_all_menu();

                pesan(res.msg, 1);
            }
        });
    }
}

function tambah_all_menu_folder() {
    nama_folder = $("#form_tambah_folder_menu #nm_folder").val();

    if (nama_folder) {
        $.ajax({
            url: situs + "setting/tambah_all_menu_folder",
            data: $("#form_tambah_folder_menu").serialize(),
            type: 'post',
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                no_proses();
                get_all_menu();
            }
        });
    } else {
        alert('Silahkan masukkan nama folder');
    }
}

function tambah_all_menu_baru() {
    nama_menu = $("#form_tambah_all_menu_baru #nm_menu").val();
    link_menu = $("#form_tambah_all_menu_baru #link").val();

    if (nama_menu && link_menu) {
        $.ajax({
            url: situs + "setting/tambah_all_menu_baru",
            data: $("#form_tambah_all_menu_baru").serialize(),
            type: 'post',
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                pesan("Menu berhasil ditambah");
                get_all_menu();
            }
        });
    } else {
        alert('Silahkan masukkan nama dan link menu');
    }
}

function get_menu_layout(level, id_grup, id_parent, teks_menu) {
    if (id_grup) {
        if (level == "0") {
            link_breadcumb = "<a href='javascript:void(0)' onclick=\"get_menu_layout('0', '" + id_grup + "', '0', '')\"><i class='fa fa-list'></i> Main Menu</a>";

            $('#teks_level_0').html(link_breadcumb);
            $('#teks_level_1').html('').css("visibility", "hidden");
            $('#teks_level_2').html('').css("visibility", "hidden");

            $("#id_parent").val("0");
            $("#level").val("0");

        } else if (level == "1") {
            link_breadcumb = "<a href='javascript:void(0)' onclick=\"get_menu_layout('1', '" + id_grup + "', '" + id_parent + "', '" + teks_menu + "')\"><i class='fa fa-list'></i> " + teks_menu + "</a>";

            $('#teks_level_1').html(link_breadcumb).css("visibility", "visible");
            $('#teks_level_2').html('').css("visibility", "hidden");

            $("#id_parent").val(id_parent);
            $("#level").val("1");

        } else if (level == "2") {
            link_breadcumb = "<a href='javascript:void(0)' onclick=\"get_menu_layout('2', '" + id_grup + "', '" + id_parent + "', '" + teks_menu + "')\"><i class='fa fa-list'></i> " + teks_menu + "</a>";

            $('#teks_level_2').html(link_breadcumb).css("visibility", "visible");

            $("#id_parent").val(id_parent);
            $("#level").val("2");
        }

        var current_id_parent = $("#id_parent").val();
        var current_level = $("#level").val();

        url_get_active_menu = situs + "setting/get_active_menu?id_grup=" + id_grup + "&id_parent=" + current_id_parent + "&level=" + current_level;
        url_get_inactive_menu = situs + "setting/get_inactive_menu?id_grup=" + id_grup + "&level=" + current_level + "&id_parent=" + current_id_parent;
        url_tambah_active_menu = situs + "setting/tambah_active_menu?id_grup=" + id_grup + "&id_parent=" + current_id_parent + "&level=" + current_level;
        url_simpan_active_menu = situs + "setting/simpan_active_menu?id_grup=" + id_grup + "&id_parent=" + current_id_parent + "&level=" + current_level;
        url_hapus_active_menu = situs + "setting/hapus_active_menu?id_grup=" + id_grup + "&id_parent=" + current_id_parent + "&level=" + current_level;

        get_active_menu();
        get_inactive_menu();
    } else {
        url_get_active_menu = "";
        url_get_inactive_menu = "";
        url_tambah_active_menu = "";
        url_simpan_active_menu = "";

        $('#teks_level_0').html('<i class="fa fa-list"></i> Main Menu');
        $('#teks_level_1').html('');
        $('#teks_level_2').html('');
        $('#div_inactive_menu').html('');
        $('#form_active_menu').html('');
    }
}

function get_inactive_menu() {
    $.ajax({
        url: url_get_inactive_menu,
        beforeSend: function() {
            $html = "<center><i class=\"fa fa-pulse fa-spinner\"></i> Harap Tunggu</center>";

            $('#div_inactive_menu').html($html);
        },
        success: function(res) {
            $('#div_inactive_menu').html(res);
        }
    });
}

function get_active_menu() {
    $.ajax({
        url: url_get_active_menu,
        beforeSend: function() {
            $html = "<center><i class=\"fa fa-pulse fa-spinner\"></i> Harap Tunggu</center>";

            $('#form_active_menu').html($html);
        },
        success: function(res) {
            $('#form_active_menu').html(res);
        }
    });
}

function tambah_active_menu() {
    if (url_tambah_active_menu) {
        data_menu = $("#tabel_inactive_menu").DataTable().rows({
            selected: true
        }).data();

        if (data_menu.length > 0) {
            id_menu = "";

            for (i = 0; i < data_menu.length; i++) {
                id_menu += (i > 0) ? "&" : "";
                id_menu += "id_menu[]=" + data_menu[i][4];
            }

            $.ajax({
                url: url_tambah_active_menu,
                data: id_menu,
                type: 'post',
                beforeSend: function() {
                    proses();
                },
                success: function(res) {
                    get_active_menu();
                    get_inactive_menu();
                    pesan('Menu berhasil ditambah', 0);
                }
            });
        } else {
            alert("Silahkan Pilih Menu");
        }
    } else {
        alert("Silahkan Pilih Grup");
    }
}

function hapus_active_menu(id_menu) {
    konfirmasi = confirm("Anda yakin hapus data ini?");

    if (konfirmasi) {
        $.ajax({
            url: url_hapus_active_menu,
            data: "id_menu=" + id_menu,
            type: 'post',
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                get_active_menu();
                get_inactive_menu();
                pesan('Menu berhasil dihapus', 0);
            }
        });
    }
}

function simpan_active_menu() {
    if (url_simpan_active_menu) {
        $.ajax({
            url: url_simpan_active_menu,
            data: $("#form_active_menu").serialize(),
            type: 'post',
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                get_active_menu();
                pesan('Menu berhasil disimpan', 0);
            }
        });
    } else {
        alert("Silahkan pilih grup");
    }
}

function copy_menu() {
    konfirmasi = confirm("Anda yakin?");

    if (konfirmasi) {
        data_copy = "grup=" + $("#grup").val() + "&dari_grup=" + $("#menuGrup").val();

        $.ajax({
            url: situs + "setting/copy_menu",
            data: data_copy,
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                proses();
            },
            success: function(res) {
                pesan(res.msg);

                get_menu_layout('0', $("#grup").val(), '0', '');
            }
        });
    }
}
</script>