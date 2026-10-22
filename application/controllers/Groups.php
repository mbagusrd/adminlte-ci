<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Groups extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Group_model', 'group_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'title'        => 'Data Groups',
            'content_view' => 'pages/groups/index',
        ];

        $this->load->view('layouts/main', $data);
    }

    public function datatables()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $list = $this->group_model->get_datatables();
        $data = [];
        $no = (int) $this->input->post('start');

        foreach ($list as $item) {
            $no++;
            $row = [];
            $row[] = $item->id;
            $row[] = $no;
            $row[] = html_escape($item->name);
            $row[] = $item->created_at ? html_escape($item->created_at) : '-';
            $row[] = $item->updated_at ? html_escape($item->updated_at) : '-';
            $data[] = $row;
        }

        $output = [
            'draw'            => (int) $this->input->post('draw'),
            'recordsTotal'    => $this->group_model->count_all(),
            'recordsFiltered' => $this->group_model->count_filtered(),
            'data'            => $data,
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    public function find($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $row = $this->group_model->find($id);
        if (!$row) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan.']));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'data' => $row]));
    }

    public function save()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->form_validation->set_rules('name', 'Nama Group', 'trim|required|max_length[50]');

        if ($this->form_validation->run() === false) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(422)
                ->set_output(json_encode([
                    'status' => false,
                    'message' => form_error('name', '', '') ?: 'Validasi gagal.'
                ]));
        }

        $id = $this->input->post('id');
        $name = $this->input->post('name', true);

        if (empty($id)) {
            $this->group_model->insert(['name' => $name]);
            $message = 'Group berhasil ditambahkan.';
        } else {
            $exists = $this->group_model->find($id);
            if (!$exists) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan.']));
            }
            $this->group_model->update($id, ['name' => $name]);
            $message = 'Group berhasil diubah.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => $message]));
    }

    public function delete($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $row = $this->group_model->find($id);
        if (!$row) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(404)
                ->set_output(json_encode(['status' => false, 'message' => 'Data tidak ditemukan.']));
        }

        $this->group_model->soft_delete($id);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['status' => true, 'message' => 'Group berhasil dihapus (soft delete).']));
    }
}
