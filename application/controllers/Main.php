<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('error_reporting', 0);
class Main extends CI_Controller {

    public function __construct() {		
		parent::__construct();
		$this->load->model("main_model");
    }

    public function index() {
		$this->add();
    }
    public function add() {
		$this->load->view('add_bill');
    }
    
    public function add_to_cart() {
        
        $name =$this->input->post("txtName");
        $total =$this->input->post("txtGrandTotal");
        
        $id = $max_id=$this->main_model->get_max_id('id','bill_info');

        $list = array();
        $title =$this->input->post("txtTitle");
        for($i=0; $i<count($title); $i++) {
            $data = [
                'bill_id' => $id,
                'title' => $this->input->post("txtTitle")[$i],
				'description' => $this->input->post("txtDescription")[$i],
				'count' => $this->input->post("txtCount")[$i],
				'amount' => $this->input->post("txtItemAmount")[$i],
                'total' => $this->input->post("txtTotal")[$i],
            ];
            array_push($list,$data);
        }
        $data = [
            'id' => $id,
            'name' => $name,
            'total' => $total,
            'status' => "1",
        ];

        if( $this->main_model->insert_into_table("bill_info", $data) and 
            $this->main_model->insert_batch_into_table("bill_cart_info", $list)) {
                echo '<div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong> Bill is created successfully.
                    </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> Something went wrong.
                </div>';
        }
    }

    public function get_display_bills() {
        $arrayList = [];
		$draw = intval($this->input->get("draw"));
       	$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $table_name = "bill_info";
        $where = ["status" => "1"];
        $list = $this->main_model->get_multiple_rows_from_table($table_name, $where);
        $i = 0;
        foreach($list as $row) {
            $arrayList[] = [
                ++$i,
                $row->name,
                $row->total,
                nice_date($row->created_at, 'd-m-Y'),
                '<a href="'.base_url("main/edit/".$row->id).'" class="btn btn-info" name="btnEdit">Edit</a> 
                <button type="button" class="btn btn-danger" name="btnDelete" id="'.$row->id.'" data-id="'.$row->id.'">Delete</button>'
            ];
        }
        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($arrayList),
            "recordsFiltered" => count($arrayList),
            "data" => $arrayList
         );
     
        echo json_encode($output);
    }

    public function delet_single_bill() {
        $_id =$this->input->post("_id");
        if( $this->main_model->delete_record_from_table("bill_info", ["id"=>$_id]) and 
            $this->main_model->delete_record_from_table("bill_cart_info", ["bill_id"=>$_id])) {
                echo '<div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong> Bill is deleted successfully.
                    </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> Something went wrong.
                </div>';
        }
    }

    public function edit($id) {
        if($id) {
            $bill = $this->main_model->get_single_row_from_table("bill_info", ["id"=>$id]);
            $bill_cart = $this->main_model->get_multiple_rows_from_table("bill_cart_info", ["bill_id"=>$id]);
            $data = ['bill'=>$bill, "bill_cart"=>$bill_cart];
            $this->load->view('edit_bill', $data);
        } else {
            redirect("main/");
        }
    }

    public function update_bill() {
        $id =$this->input->post("txtId");
        $name =$this->input->post("txtName");
        $total =$this->input->post("txtGrandTotal");

        // $list = array();
        $title =$this->input->post("txtTitle");
        for($i=0; $i<count($title); $i++) {
            // $cart_id = ;
                $data = [
                    'bill_id' => $id,
                    'title' => $this->input->post("txtTitle")[$i],
                    'description' => $this->input->post("txtDescription")[$i],
                    'count' => $this->input->post("txtCount")[$i],
                    'amount' => $this->input->post("txtItemAmount")[$i],
                    'total' => $this->input->post("txtTotal")[$i],
                ];

            if($this->input->post("txtCartId")[$i] != "-") {
                //update cart
                $where = ["id"=>$this->input->post("txtCartId")[$i]];
                $this->main_model->update_single_record_table("bill_cart_info", $where, $data);
            } else {
                // insert cart
                $this->main_model->insert_into_table("bill_cart_info", $data);
            }
            // array_push($list,$data);
        }
        $data = [
            'name' => $name,
            'total' => $total,
            'status' => "1",
        ];
        $where = ["id"=>$id];

        if( $this->main_model->update_single_record_table("bill_info", $where, $data)) {
                echo '<div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong> Bill is updated successfully.
                    </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Success!</strong> Something went wrong.
                </div>';
        }
    }
}
?>