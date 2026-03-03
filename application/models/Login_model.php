<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{
    private $tabel_user = "s_user";
    private $tabel_grup = "s_grup";

    public function __construct()
    {
        parent::__construct();
    }

    public function masuk($data, $mode = "")
    {
        $cek_data_user = false;
        $cek_data_password = false;

        $dataLogin = array();

        $qdataLogin = $this->db->select("id_user, nama, username, a.id_grup, b.nm_grup, passwd")
            ->from($this->tabel_user . " a")->join($this->tabel_grup . " b ", "a.id_grup = b.id_grup")
            ->where("username", strtoupper($data['username']))
            // ->where("passwd", $data['password'])
            ->get();

        if ($qdataLogin->num_rows() > 0) {
            $dataLogin = $qdataLogin->row_array();

            if (password_verify($data['password'], $dataLogin['passwd'])) {
                $cek_data_password = true;
            }

            $cek_data_user = true;
        }

        if (ENVIRONMENT == 'development') {
            $cek_data_password = true;
        }

        if ($cek_data_user === true and $cek_data_password === true) {
            $this->session->set_userdata($dataLogin);

            return true;
        }

        return false;
    }

    public function cek_login()
    {
        if ($this->session->userdata("username") == "") {
            view_page('home/login', '', true);
        }
    }

    public function cek_akses_menu($mode_setting = "0")
    {
        $id_grup = $this->session->userdata("id_grup");

        if ($mode_setting == "1") {
            if ($id_grup != "1") {
                view_page('home/forbidden', '', true);
            }
        } else {
            $data_menu = $this->db->from($this->tabel_menu . " a")->join($this->tabel_akses . " b", "a.id_menu=b.id_menu")
                ->where("b.id_grup", $id_grup)
                ->like("a.link", uri_string(), "both")
                ->get();

            if ($data_menu->num_rows() < 1) {
                view_page('home/forbidden', '', true);
            }
        }
    }
}
