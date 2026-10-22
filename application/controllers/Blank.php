<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Blank extends CI_Controller
{
    public function index()
    {
        $this->render('Blank Page', 'Start creating your amazing application!');
    }

    public function level2_1()
    {
        $this->render('Level 2 (1)', 'Ini konten level 2 menu pertama.');
    }

    public function level3_1()
    {
        $this->render('Level 3 (1)', 'Ini konten level 3 sub menu pertama.');
    }

    public function level3_2()
    {
        $this->render('Level 3 (2)', 'Ini konten level 3 sub menu kedua.');
    }

    // ponytail: helper render controller lokal. upgrade ke Base_Controller jika controller lain butuh.
    private function render($title, $body)
    {
        $data = [
            'title'        => $title,
            'content_view' => 'pages/blank',
            'box_title'    => $title,
            'box_body'     => $body,
            'box_footer'   => 'Footer Box',
        ];

        $this->load->view('layouts/main', $data);
    }
}
