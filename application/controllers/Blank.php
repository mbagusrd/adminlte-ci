<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blank extends CI_Controller
{
    public function index()
    {
        $data = [
            'title'        => 'Blank Page',
            'content_view' => 'pages/blank',
            'box_title'    => 'Title Box',
            'box_body'     => 'Start creating your amazing application!',
            'box_footer'   => 'Footer Box',
        ];

        $this->load->view('layouts/main', $data);
    }
}
