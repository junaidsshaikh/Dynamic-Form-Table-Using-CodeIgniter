<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function __construct() {
		parent::__construct();
    }

    public function get_max_id($table_id, $table_name) {
        $row = $this->db->select_max($table_id)
					->get($table_name)->row_array();
		$max_id = $row[$table_id] + 1; 
		return $max_id;
    }

    public function insert_into_table($table_name, $data) {
        return $this->db->insert($table_name, $data);
    }

    
    public function insert_batch_into_table($table_name, $data) {
        return $this->db->insert_batch($table_name, $data);
    }

    public function get_multiple_rows_from_table($table_name, $where) {
        return $this->db->where($where)
                        ->get($table_name)
                        ->result();
    }

    public function delete_record_from_table($table_name, $where) {
        return $this->db->delete($table_name, $where);
    }

    public function get_single_row_from_table($table_name, $where) {
        return $this->db->where($where)
                        ->get($table_name)
                        ->row();

    }

    public function update_single_record_table($table_name, $where, $data) {
        return $this->db->update($table_name, $data, $where);
    }
}    

?>