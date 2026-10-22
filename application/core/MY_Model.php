<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $table = '';
    protected $primary_key = 'id';
    protected $column_order = [];
    protected $column_search = [];
    protected $order = ['id' => 'desc'];
    protected $use_soft_delete = true;
    protected $use_timestamps = true;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    protected function _get_datatables_query()
    {
        $this->db->from($this->table);

        if ($this->use_soft_delete) {
            $this->db->where($this->table . '.deleted_at IS NULL', null, false);
        }

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
        if ($this->use_soft_delete) {
            $this->db->where('deleted_at IS NULL', null, false);
        }
        return $this->db->count_all_results();
    }

    public function find($id)
    {
        $where = [$this->primary_key => $id];
        if ($this->use_soft_delete) {
            $where['deleted_at'] = null;
        }
        return $this->db->get_where($this->table, $where)->row();
    }

    public function all()
    {
        if ($this->use_soft_delete) {
            $this->db->where('deleted_at IS NULL', null, false);
        }
        return $this->db->get($this->table)->result();
    }

    public function insert(array $data)
    {
        if ($this->use_timestamps) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, array $data)
    {
        if ($this->use_timestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        $this->db->where($this->primary_key, $id);
        if ($this->use_soft_delete) {
            $this->db->where('deleted_at IS NULL', null, false);
        }
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id)
    {
        if (!$this->use_soft_delete) {
            return false;
        }
        $this->db->where($this->primary_key, $id);
        $this->db->where('deleted_at IS NULL', null, false);
        return $this->db->update($this->table, [
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete($id)
    {
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table);
    }
}
