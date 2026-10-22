<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group_model extends MY_Model
{
    protected $table = 'groups';
    protected $column_order = [null, null, 'name', 'created_at', 'updated_at'];
    protected $column_search = ['name'];
    protected $order = ['id' => 'desc'];
}
