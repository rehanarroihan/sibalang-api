<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Stuff extends REST_Controller {
    function __construct() {
        parent::__construct();
	}
	
	public function index_get() {
		$output = [
            'errCode' => '00',
			'message' => 'Success',
			'stuffs' => []
		];
		$allStuff = $this->db->get('stuff');
		if ($allStuff->num_rows() > 0) {
			$output['stuffs'] = $allStuff->result();
		}
		$this->set_response($output, 200);
	}

    public function index_post() {
		//Default output
		$output = [
            'errCode' => '00',
            'message' => 'Berhasil',
		];

		//Genereate time 
		$date = new DateTime(date("Y/m/d h:i:sa"));
		$now = $date->format('Y-m-d H:i:s');

		//upload operation
		$config['upload_path'] = './file/';
		$config['allowed_types'] = 'gif|jpg|png';
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('photo')){
			$inputData = [
				'nim' => $this->post('nim'),
				'name' => $this->post('name'),
				'description' => $this->post('description'),
				'date' => $now,
				'photo' => $this->upload->data()['file_name'],
				'turned' => 1
			];
			$query = $this->db->insert('stuff', $inputData);
			if($this->db->affected_rows() > 0){
				$output['message'] = 'Berhasil memasukkan data baru!';
			} else {
				$output['errCode'] = '01';
				$output['message'] = 'Gagal memasukkan data baru, silahkan coba lagi.';
			}
		}else{
			$output['errCode'] = '01';
			$output['message'] = $this->upload->display_errors();
		}
    	$this->set_response($output, 200);
	}

	public function turn_post() {
		//Default output
		$output = [
            'errCode' => '00',
            'message' => 'Berhasil mendaftarkan user',
		];

		$this->db->set('turned', 1)->where('id', $this->post('stuff_id'))->update('stuff');
		if($this->db->affected_rows() > 0){
			$output['message'] = 'Status barang telah di ubah';
		} else {
			$output['errCode'] = '01';
			$output['message'] = 'Gagal memperbarui status barang, silahkan coba lagi';
		}
		$this->set_response($output, 200);
	}
}
