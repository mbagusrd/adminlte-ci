<?php defined('BASEPATH') or exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    private $tabel_grup = "s_grup";
    private $tabel_user = "s_user";
    private $tabel_menu = "s_menu";
    private $tabel_akses = "s_akses";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_grup($numrows = 0, $cari = "", $tanpa_admin = "", $order = "", $offset = 0, $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "(@nomor:=@nomor+1) nomor, id_grup, nm_grup";

        $this->db->select($select);

        if (is_array($cari) and isset($cari['value']) and $cari['value'] != "") {
            $cari_field = isset($cari['field'][0]) ? $cari['field'] : array("nm_grup");

            $this->db->group_start();

            foreach ($cari_field as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        if (!$numrows) {
            $set_order = ($order) ? $order : "nm_grup";

            $this->db->order_by($set_order);
        }

        if ($offset == "") {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($tanpa_admin != "") {
            $this->db->where("id_grup !=", "1");
        }

        return $this->db->get($this->tabel_grup . ", (select @nomor:=" . $offset . ") z");
    }

    public function insert_grup($data)
    {
        $is_success = 0;

        while ($is_success != 1) {
            $id_grup = get_maxid($this->tabel_grup, "id_grup");

            $set_data = array(
                "id_grup" => $id_grup,
                "nm_grup" => strtoupper($data['nm_grup']),
            );

            $insert = $this->db->set($set_data)->insert($this->tabel_grup);

            if ($insert) {
                $is_success = 1;
            }
        }

        return true;
    }

    public function update_grup($data, $id)
    {
        $set_data = array(
            // "id_grup" => $id_grup,
            "nm_grup" => strtoupper($data['nm_grup']),
        );

        return $this->db->set($set_data)->where("id_grup", $id)->update($this->tabel_grup);
    }

    public function delete_grup($data)
    {
        if ($data['id_grup'] == "1") {
            return false;
        } else {
            return $this->db->where("id_grup", $data['id_grup'])->delete($this->tabel_grup);
        }
    }

    public function get_user($numrows = 0, $cari = "", $tanpa_administrator = "", $order = "", $offset = 0, $limit = "")
    {
        $this->db->select("id_user, nama, username, a.id_grup, b.nm_grup")
            ->from($this->tabel_user . " a")
            ->join($this->tabel_grup . " b", "a.id_grup = b.id_grup", "left");

        if (is_array($cari) and isset($cari['value']) and $cari['value'] != "") {
            $cari_field = isset($cari['field'][0]) ? $cari['field'] : array("nama", "username", "b.nm_grup");

            $this->db->group_start();

            foreach ($cari_field as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }

            $this->db->group_end();
        }

        if (!$numrows) {
            $set_order = ($order) ? $order : "nama";

            $this->db->order_by($set_order);
        }

        if ($offset == "") {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($tanpa_administrator != "") {
            $this->db->where("a.id_user !=", "1");
        }

        $query_select = str_replace("`", "", $this->db->get_compiled_select());

        $select = ($numrows) ? "count(*) numrows" : "*, (@nomor:=@nomor+1) nomor";

        $query_select2 = str_replace("`", "", $this->db->select($select)->from("(" . $query_select . ") x, (select @nomor:=" . $offset . ") z")->get_compiled_select());

        return $this->db->query($query_select2);
    }

    public function insert_user($data)
    {
        // $is_success = 0;

        // while ($is_success != 1) {

        // }

        $id_user = get_maxid($this->tabel_user, "id_user");

        $set_data = array(
            "id_user"  => $id_user,
            "nama"     => $data['nama'],
            "username" => $data['username'],
            "passwd"   => password_hash($data['passwd'], PASSWORD_DEFAULT),
            "id_grup"  => $data['id_grup'],
        );

        $insert = $this->db->set($set_data)->insert($this->tabel_user);

        if ($insert) {
            // $is_success = 1;
            return true;
        } else {
            $this->insert_user($data);
        }
    }

    public function update_user($data, $id)
    {
        $set_data = array(
            "nama"     => $data['nama'],
            "username" => $data['username'],
            "id_grup"  => $data['id_grup'],
        );

        if ($data['passwd'] != "") {
            $set_data['passwd'] = password_hash($data['passwd'], PASSWORD_DEFAULT);
        }

        return $this->db->set($set_data)->where("id_user", $id)->update($this->tabel_user);
    }

    public function delete_user($data)
    {
        if ($data['id_user'] == "1") {
            return false;
        } else {
            return $this->db->where("id_user", $data['id_user'])->delete($this->tabel_user);
        }
    }

    public function get_menu($numrows = 0, $cari = "", $sort = "", $order = "", $offset = 0, $limit = "")
    {
        $select = ($numrows) ? "count(*) numrows" : "(@nomor:=@nomor+1) nomor, id_menu, jns_menu, nm_menu, link";

        $this->db->select($select);

        if (is_array($cari) and isset($cari['value']) and $cari['value'] != "") {
            $set_field = isset($cari['field'][0]) ? $cari['field'] : array("nm_menu", "link");

            $this->db->group_start();
            foreach ($set_field as $key => $value) {
                $this->db->or_like($value, $cari['value']);
            }
            $this->db->group_end();
        }

        if (!$numrows) {
            $sort = ($sort) ? $sort : "jns_menu, nm_menu";

            $this->db->order_by($sort);
        }

        if ($offset == "") {
            $offset = 0;
        }

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get($this->tabel_menu . ", (select @nomor:=" . $offset . ") z");
    }

    public function insert_menu($data)
    {
        $is_sukses = 0;

        while ($is_sukses != 1) {
            $id = get_maxid($this->tabel_menu, "id_menu");

            $set_data = array(
                "id_menu"  => $id,
                "jns_menu" => $data['jns_menu'],
                "nm_menu"  => $data['nm_menu'],
                "link"     => $data['link'],
            );

            $insert_data =  $this->db->set($set_data)->insert($this->tabel_menu);

            if ($insert_data) {
                $is_sukses = 1;
            }
        }

        return true;
    }

    public function update_menu($data, $id)
    {
        $set_data = array(
            "nm_menu" => $data['nm_menu'],
            "link"    => $data['link'],
        );

        return $this->db->set($set_data)->where("id_menu", $id)->update($this->tabel_menu);
    }

    public function delete_menu($data)
    {
        // Hapus dari tabel akses di mana menu ini adalah parent atau child
        $this->db->where("id_parent", $data['id_menu'])
            ->delete($this->tabel_akses);

        $this->db->where("id_menu", $data['id_menu'])
            ->delete($this->tabel_akses);

        return $this->db->where("id_menu", $data['id_menu'])->delete($this->tabel_menu);
    }

    public function get_inactive_menu($id_grup, $level, $id_parent)
    {
        $this->db->where("(jns_menu = '2' and id_menu not in (select id_menu from " . $this->tabel_akses . " where id_grup = '" . $id_grup . "'))");

        if ($level < 2) {
            $this->db->or_group_start()
                ->where("jns_menu", '1')
                ->where("id_menu not in (select id_menu from " . $this->tabel_akses . " where id_grup = '" . $id_grup . "')")
                ->group_end();
        }

        return $this->db->order_by("jns_menu, nm_menu")
            ->get($this->tabel_menu);
    }

    public function get_active_menu($id_grup, $id_parent, $level)
    {
        return $this->db->from($this->tabel_menu . " a")
            ->join($this->tabel_akses . " b", "a.id_menu=b.id_menu")
            ->select("a.id_menu, a.jns_menu, a.nm_menu, a.link, b.id_grup, b.id_parent, b.level, b.sort")
            ->where("id_grup", $id_grup)
            ->where("b.id_parent", $id_parent)
            ->where("b.level", $level)
            ->order_by("sort")
            ->get();
    }

    public function tambah_active_menu($data)
    {
        $data_sort = $this->db->select("ifnull(max(sort), 0) + 1 sort")
            ->where("id_grup", $data['id_grup'])
            ->where("id_parent", $data['id_parent'])
            ->where("level", $data['level'])
            ->get($this->tabel_akses);

        $sort = ($data_sort->num_rows() > 0) ? $data_sort->row(0)->sort : 1;

        $set_data = array(
            "id_grup"   => $data['id_grup'],
            "id_menu"   => $data['id_menu'],
            "id_parent" => $data['id_parent'],
            "level"     => $data['level'],
            "sort"      => $sort,
        );

        return $this->db->set($set_data)->insert($this->tabel_akses);
    }

    private function hapus_child_menu($id_grup, $id_parent, $level)
    {
        // Ambil semua child menu
        $children = $this->db->where("id_grup", $id_grup)
            ->where("id_parent", $id_parent)
            ->where("level", $level)
            ->get($this->tabel_akses);

        // Hapus setiap child dan turunannya secara rekursif
        foreach ($children->result_array() as $child) {
            // Hapus child dari level yang lebih dalam
            $this->hapus_child_menu($id_grup, $child['id_menu'], $level + 1);

            // Hapus child ini sendiri
            $this->db->where("id_grup", $id_grup)
                ->where("id_parent", $id_parent)
                ->where("id_menu", $child['id_menu'])
                ->where("level", $level)
                ->delete($this->tabel_akses);
        }
    }

    public function hapus_active_menu($data)
    {
        // Hapus semua child menu secara rekursif
        $this->hapus_child_menu($data['id_grup'], $data['id_menu'], $data['level'] + 1);

        // Hapus menu itu sendiri
        return $this->db->where("id_parent", $data['id_parent'])
            ->where("id_menu", $data['id_menu'])
            ->where("id_grup", $data['id_grup'])
            ->where("level", $data['level'])
            ->delete($this->tabel_akses);
    }

    public function simpan_active_menu($data)
    {
        return $this->db->set("sort", $data['sort'])
            ->where("id_grup", $data['id_grup'])
            ->where("id_menu", $data['id_menu'])
            ->where("id_parent", $data['id_parent'])
            ->where("level", $data['level'])
            ->update($this->tabel_akses);
    }

    public function copy_menu($grup, $dari_grup)
    {
        $this->db->where("id_grup", $grup)->delete($this->tabel_akses);

        $query = "insert into " . $this->tabel_akses . " (id_grup, id_menu, id_parent, level, sort)
                    select '" . $grup . "' grup, id_menu, id_parent, level, sort
                    from " . $this->tabel_akses . "
                    where id_grup = '" . $dari_grup . "'";

        return $this->db->simple_query($query);
    }

    public function get_sidebar_menu()
    {
        $sidebar_menu = array();

        $id_grup = $this->session->userdata("id_grup");

        // Level 0 - Main Menu
        $menu_list0 = $this->db->from($this->tabel_menu . " a")->join($this->tabel_akses . " b", "a.id_menu=b.id_menu")
            ->where("b.id_grup", $id_grup)
            ->where("b.level", '0')
            ->order_by("b.sort")
            ->get();

        $sidebar_menu = $menu_list0->result_array();

        foreach ($menu_list0->result_array() as $key => $value) {
            // Level 1 - Sub Menu
            $menu_list1 = $this->db->from($this->tabel_menu . " a")->join($this->tabel_akses . " b", "a.id_menu=b.id_menu")
                ->where("b.id_grup", $id_grup)
                ->where("b.id_parent", $value['id_menu'])
                ->where("b.level", '1')
                ->order_by("b.sort")
                ->get();

            $sidebar_menu1 = $menu_list1->result_array();

            foreach ($menu_list1->result_array() as $key1 => $value1) {
                // Level 2 - Sub Sub Menu
                $menu_list2 = $this->db->from($this->tabel_menu . " a")->join($this->tabel_akses . " b", "a.id_menu=b.id_menu")
                    ->where("b.id_grup", $id_grup)
                    ->where("b.id_parent", $value1['id_menu'])
                    ->where("b.level", '2')
                    ->order_by("b.sort")
                    ->get();

                $sidebar_menu1[$key1]['sub_menu'] = $menu_list2->result_array();
            }

            $sidebar_menu[$key]['sub_menu'] = $sidebar_menu1;
        }

        return $sidebar_menu;
    }
}
