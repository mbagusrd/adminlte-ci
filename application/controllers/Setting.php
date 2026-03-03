<?php defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->login_model->cek_login();

        $this->login_model->cek_akses_menu("1");

        $this->load->model("setting_model");
    }

    public function index($page)
    {
        if ($page == "grup-user") {
            $data['judul_menu'] = "Setting Grup dan User";

            view_page("setting/grup_user", $data);
        }

        if ($page == "menu") {
            $data_grup = $this->setting_model->get_grup()->result_array();
            $opt_grup  = "<option value=\"\">[-PILIH-]</option>";

            foreach ($data_grup as $key => $value) {
                $opt_grup .= "<option value=\"" . $value['id_grup'] . "\">" . $value['nm_grup'] . "</option>";
            }

            $data['opt_grup']   = $opt_grup;
            $data['judul_menu'] = "Setting Menu";

            view_page("setting/menu", $data);
        }
    }

    public function get_grup($tanpa_admin = "")
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->setting_model->get_grup(1, $cari, $tanpa_admin)->row(0)->numrows;
        $data_item    = $this->setting_model->get_grup("", $cari, $tanpa_admin, "", $offset, $limit);

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function add_grup()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->setting_model->insert_grup($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            echo json_encode($hasil);
        }
    }

    public function edit_grup($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->setting_model->update_grup($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_grup()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->setting_model->delete_grup($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Dihapus";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Dihapus";
            }

            echo json_encode($hasil);
        }
    }

    public function select_grup()
    {
        $data_req      = get_request();
        $value         = isset($data_req['value']) ? $data_req['value'] : "";
        $q             = isset($data_req['q']) ? $data_req['q'] : $value;
        $cari['value'] = $q;

        $data = $this->setting_model->get_grup("", $cari, "", 0, 100)->result_array();

        foreach ($data as $key => $value) {
            $data[$key]['id']   = $value['id_grup'];
            $data[$key]['text'] = $value['nm_grup'];
        }

        $arrData['results'] = $data;

        echo json_encode($arrData);
    }

    public function get_user($tanpa_administrator = "")
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->setting_model->get_user(1, $cari, "")->row(0)->numrows;
        $data_item    = $this->setting_model->get_user("", $cari, "", "", $offset, $limit);

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function add_user()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->setting_model->insert_user($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Ditambah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Ditambah";
            }

            echo json_encode($hasil);
        }
    }

    public function edit_user($id)
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->setting_model->update_user($data_post, $id);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Diubah";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Diubah";
            }

            echo json_encode($hasil);
        }
    }

    public function del_user()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $query = $this->setting_model->delete_user($data_post);

            if ($query) {
                $hasil['status'] = true;
                $hasil['msg']    = "Data Berhasil Dihapus";
            } else {
                $hasil['status'] = false;
                $hasil['msg']    = "Data Gagal Dihapus";
            }

            echo json_encode($hasil);
        }
    }

    public function select_user()
    {
        $data_req      = get_request();
        $value         = isset($data_req['value']) ? $data_req['value'] : "";
        $q             = isset($data_req['q']) ? $data_req['q'] : $value;
        $cari['value'] = $q;

        $data = $this->setting_model->get_user("", $cari, "", 0, 100)->result_array();

        $arrData['results'] = $data;

        echo json_encode($arrData);
    }

    public function setsession()
    {
        $data_req = get_request();

        $this->session->set_userdata($data_req);

        $output = "";

        foreach ($data_req as $key => $value) {
            $output .= $key . ": " . $value . "\n";
        }

        echo $output;
    }

    public function get_all_menu()
    {
        $data = get_request();

        $cari['value'] = $data['search']['value'];
        $offset        = $data['start'];
        $limit         = $data['length'];

        $data_numrows = $this->setting_model->get_menu(1, $cari)->row(0)->numrows;
        $data_item    = $this->setting_model->get_menu(0, $cari, "", "", $offset, $limit);

        $array['recordsTotal']    = $data_numrows;
        $array['recordsFiltered'] = $array['recordsTotal'];
        $array['data']            = $data_item->result_array();

        echo json_encode($array);
    }

    public function tambah_all_menu()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data = $data_post;

            foreach ($data['link'] as $key => $value) {
                $data_insert['jns_menu'] = '2';
                $data_insert['nm_menu']  = $data['nm_menu'][$key];
                $data_insert['link']     = $value;

                $this->setting_model->insert_menu($data_insert);
            }

            $json['msg'] = "Data berhasil ditambah";

            echo json_encode($json);
        }
    }

    public function tambah_all_menu_folder()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            if ($data_post['nm_folder']) {
                $data_insert['jns_menu'] = '1';
                $data_insert['nm_menu']  = $data_post['nm_folder'];
                $data_insert['link']     = "";

                $this->setting_model->insert_menu($data_insert);
            }
        }
    }

    public function tambah_all_menu_baru()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            if ($data_post['nm_menu'] and $data_post['link']) {
                $data_insert['jns_menu'] = '2';
                $data_insert['nm_menu']  = $data_post['nm_menu'];
                $data_insert['link']     = $data_post['link'];

                $this->setting_model->insert_menu($data_insert);
            }
        }
    }

    public function simpan_all_menu()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            if (isset($data_post['id_menu']) and sizeof($data_post['id_menu']) > 0) {
                foreach ($data_post['id_menu'] as $key => $value) {
                    $data_update['nm_menu'] = $data_post['nm_menu'][$key];
                    $data_update['link']    = $data_post['link'][$key];
                    $this->setting_model->update_menu($data_update, $value);
                }
            }

            $json['msg'] = "Data berhasil disimpan";

            echo json_encode($json);
        }
    }

    public function hapus_menu()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->setting_model->delete_menu($data_post);

            $json['msg'] = "Data berhasil dihapus";

            echo json_encode($json);
        }
    }

    public function ambil_menu()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->setting_model->ambil_menu($data_post['grup'], $data_post['dari_grup']);
        }
    }

    public function get_isi_controller($sub_dir = "")
    {
        $array_terlarang = array(".", "..", "Cek_info.php", "Home.php", "Login.php", "Pesan.php", "index.html", "setting");

        $path_controller = APPPATH . "controllers/" . $sub_dir;

        $file_menu = scandir($path_controller);

        foreach ($array_terlarang as $value) {
            $key = array_search($value, $file_menu);

            unset($file_menu[$key]);
        }

        return $file_menu;
    }

    public function get_new_controller()
    {
        $cari_array['value']    = "2";
        $cari_array['field'][0] = "jns_menu";

        $data_active_menu = $this->setting_model->get_menu(0, $cari_array)->result_array();

        $array_active_menu = array();

        foreach ($data_active_menu as $value) {
            $array_active_menu[] = $value['link'];
        }

        $menu_file_1 = $this->get_isi_controller();

        $path_controller = APPPATH . "controllers/";

        echo "<table id='tabel_new_controller' class='table table-condensed table-hover table striped' style='width: 100%'>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>File Menu</th>
                        <th>Nama Menu</th>
                    </tr>
                </thead>
                <tbody>";

        $no = 1;

        foreach ($menu_file_1 as $value1) {
            if (is_dir($path_controller . $value1)) {
                $menu_file_2 = $this->get_isi_controller($value1);

                foreach ($menu_file_2 as $value2) {
                    $teks_menu = strtolower($value1) . "/" . str_replace(".php", "", strtolower($value2));

                    if (!in_array($teks_menu, $array_active_menu)) {
                        echo "<tr>
                                <td class='text-right'>" . $no . "</td>
                                <td>" . $teks_menu . "</td>
                                <td><input type='hidden' id='new_link_" . $no . "' name='link[]' value='" . $teks_menu . "' /><input type='text' class='form-control' id='new_nm_menu_" . $no . "' name='nm_menu[]' style='width: 100%' /></td>
                            </tr>";

                        $no++;
                    }
                }
            } else {
                $teks_menu = str_replace(".php", "", strtolower($value1));

                if (!in_array($teks_menu, $array_active_menu)) {
                    echo "<tr>
                            <td class='text-right'>" . $no . "</td>
                            <td>" . $teks_menu . "</td>
                            <td><input type='hidden' id='new_link_" . $no . "' name='link[]' value='" . $teks_menu . "' /><input type='text' class='form-control' id='new_nm_menu_" . $no . "' name='nm_menu[]' style='width: 100%' /></td>
                        </tr>";

                    $no++;
                }
            }
        }

        echo "</tbody>
            </table>";

        echo "<script>
            $('#tabel_new_controller').DataTable({
            select: false
            });
        </script>";
    }

    public function get_inactive_menu()
    {
        $data = get_request();

        $id_parent = isset($data['id_parent']) ? $data['id_parent'] : "0";
        $level     = isset($data['level']) ? $data['level'] : "0";

        $data_inactive_menu = $this->setting_model->get_inactive_menu($data['id_grup'], $level, $id_parent);

        echo "<table id='tabel_inactive_menu' class='table table-sm table-hover table striped nowrap' width='100%'>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jenis Menu</th>
                        <th>Nama Menu</th>
                        <th>Link</th>
                        <th>id_menu</th>
                    </tr>
                </thead>
                <tbody>";

        $no = 1;

        foreach ($data_inactive_menu->result_array() as $key => $value) {
            $teks_jenis_menu = ($value['jns_menu'] == "1") ? "Folder" : "Menu";

            echo "<tr>
                    <td class='text-right'>" . $no . "</td>
                    <td>" . $teks_jenis_menu . "</td>
                    <td>" . $value['nm_menu'] . "</td>
                    <td>" . $value['link'] . "</td>
                    <td>" . $value['id_menu'] . "</td>
                </tr>";

            $no++;
        }

        echo "</tbody>
            </table>";

        echo "<script>
                $('#tabel_inactive_menu').DataTable({
                ordering: false,
                paging: false,
                select: true,
                scrollX: true,
                columnDefs: [
                    {targets: -1, visible: false}
                    ]
                });
                </script>";
    }

    public function get_active_menu()
    {
        $data = get_request();

        $id_parent = isset($data['id_parent']) ? $data['id_parent'] : "0";
        $level     = isset($data['level']) ? $data['level'] : "0";

        $data_active_menu = $this->setting_model->get_active_menu($data['id_grup'], $id_parent, $level);

        echo "<table id='tabel_active_menu' class='table table-sm table-hover table striped nowrap' width='100%'>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Menu</th>
                        <th>Link</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

        $no = 1;

        foreach ($data_active_menu->result_array() as $key => $value) {
            if ($value['jns_menu'] == '1') {
                $next_level = $level + 1;

                $teks_nama_menu = "<input type='hidden' name='id_menu[]' value='" . $value['id_menu'] . "' />
                    <a href='javascript:void(0)' onclick=\"get_menu_layout('" . $next_level . "', '" . $data['id_grup'] . "', '" . $value['id_menu'] . "', '" . $value['nm_menu'] . "')\">" . $value['nm_menu'] . "</a>";
            } else {
                $teks_nama_menu = "<input type='hidden' name='id_menu[]' value='" . $value['id_menu'] . "' />" . $value['nm_menu'];
            }

            echo "<tr>
                    <td><i class='fa fa-bars'></i> " . $no . "</td>
                    <td>" . $teks_nama_menu . "</td>
                    <td>" . $value['link'] . "</td>
                    <td><button class='btn btn-danger' onclick=\"hapus_active_menu('" . $value['id_menu'] . "')\"><i class='fa fa-trash'></i></button></td>
                </tr>";

            $no++;
        }

        echo "</tbody>
            </table>";

        echo "<script>
                $('#tabel_active_menu').DataTable({
                    columnDefs: [
                        {targets: 0, className: 'reorder'},
                        {targets: -1, className: 'control'}
                    ],
                    rowReorder: {
                        update: false
                    },
                    paging: false,
                    searching: false,
                    ordering: false,
                    select: false,
                    scrollX: true
                });
            </script>";
    }

    public function tambah_active_menu()
    {
        $data_req = get_request();

        if ($data_req) {
            if (sizeof($data_req['id_menu']) > 0) {
                foreach ($data_req['id_menu'] as $key => $value) {
                    $data_insert['id_grup']    = $data_req['id_grup'];
                    $data_insert['id_menu']    = $value;
                    $data_insert['id_parent']  = $data_req['id_parent'];
                    $data_insert['level']      = $data_req['level'];

                    $this->setting_model->tambah_active_menu($data_insert);
                }
            }
        }
    }

    public function hapus_active_menu()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $data_req = get_request();

            $this->setting_model->hapus_active_menu($data_req);
        }
    }

    public function simpan_active_menu()
    {
        $data_req = get_request();

        if ($data_req) {
            if (sizeof($data_req['id_menu']) > 0) {
                foreach ($data_req['id_menu'] as $key => $value) {
                    $sort = $key + 1;

                    $data_active_menu['id_menu']   = $value;
                    $data_active_menu['id_grup']   = $data_req['id_grup'];
                    $data_active_menu['id_parent'] = $data_req['id_parent'];
                    $data_active_menu['level']     = $data_req['level'];
                    $data_active_menu['sort']      = $sort;

                    $this->setting_model->simpan_active_menu($data_active_menu);
                }
            }
        }
    }

    public function copy_menu()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            if ($data_post['grup'] == $data_post['dari_grup']) {
                $hasil['status'] = false;
                $hasil['msg']    = "Grup tidak boleh sama";
            } else {
                $query = $this->setting_model->copy_menu($data_post['grup'], $data_post['dari_grup']);

                if ($query) {
                    $hasil['status'] = true;
                    $hasil['msg']    = "Data Berhasil Ditambah";
                } else {
                    $hasil['status'] = false;
                    $hasil['msg']    = "Data Gagal Ditambah";
                }
            }

            echo json_encode($hasil);
        }
    }
}
