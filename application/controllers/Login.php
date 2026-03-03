<?php defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->login_model->cek_login();

        if ($this->session->userdata("username") != "") {
            redirect();
        }
    }

    public function masuk()
    {
        $data_post = $this->input->post();

        if ($data_post) {
            $this->form_validation->set_rules('username', "Username", 'required');
            $this->form_validation->set_rules('password', "Password", 'required');

            if ($this->form_validation->run() == false) {
                $data_hasil = get_hasil(validation_errors(), 'false_input');
            } else {
                $dataLogin = $this->login_model->masuk($data_post);

                if ($dataLogin) {
                    $data_hasil = get_hasil();
                } else {
                    $data_hasil = get_hasil("Username atau password salah", false);
                }
            }

            echo json_encode($data_hasil);
        }
    }

    public function keluar()
    {
        $this->session->sess_destroy();
        redirect();
    }
}
