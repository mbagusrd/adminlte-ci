<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_model extends CI_Model
{
    protected $table = 'groups';
    protected $column_order = [null, null, 'name', 'created_at', 'updated_at'];
    protected $column_search = ['name'];
    protected $order = ['id' => 'desc'];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->where($this->table . '.deleted_at IS NULL', null, false);

        $search = $this->input->post('search');
        if (!empty($search['value'])) {
            $i = 0;
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $search['value']);
                } else {
                    $this->db->or_like($item, $search['value']);
                }
                $i++;
            }
            if ($i > 0) {
                $this->db->group_end();
            }
        }

        $order = $this->input->post('order');
        if (isset($order[0]['column'])) {
            $col_idx = (int) $order[0]['column'];
            $col_dir = $order[0]['dir'] === 'asc' ? 'asc' : 'desc';
            if (isset($this->column_order[$col_idx]) && $this->column_order[$col_idx] !== null) {
                $this->db->order_by($this->column_order[$col_idx], $col_dir);
            }
        } elseif (!empty($this->order)) {
            foreach ($this->order as $key => $val) {
                $this->db->order_by($key, $val);
            }
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        $length = (int) $this->input->post('length');
        $start = (int) $this->input->post('start');
        if ($length !== -1) {
            $this->db->limit($length, $start);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->count_all_results();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, [
            'id' => (int) $id,
            'deleted_at' => null,
        ])->row();
    }

    public function insert(array $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, array $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', (int) $id);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id)
    {
        $this->db->where('id', (int) $id);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->update($this->table, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }
}
