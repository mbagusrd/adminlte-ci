<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // $this->session->set_userdata("id_grup", "1");
        // $this->session->set_userdata("username", "mbagusrd");
        // $this->session->set_userdata("nama", "bagus");
        // $this->session->set_userdata("nm_grup", "admin");

        $this->login_model->cek_login();

        // $this->load->model("pesan_model");
        // if ($this->session->userdata('username') != "") {
        //     if (strtoupper(substr(PHP_OS, 0, 3)) === "WIN") {
        //         bg_proses("node " . FCPATH . "node_modules/server.js");
        //     } else {
        //         bg_proses("");
        //     }
        // }
    }

    public function index()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if ($current_url != current_url()) {
            redirect();
        }

        $data['judul_menu'] = "Dashboard";
        view_page('', $data);
    }

    public function kosong()
    {
        echo "halaman kosong";
    }
}
