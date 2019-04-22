<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Auth extends REST_Controller {

    function __construct() {
        parent::__construct();
    }

    public function register_post() {
		$registerData = [
			'nim' => $this->post('nim'),
			'password' => $this->post('password'),
			'fullname' => $this->post('fullname'),
			'departmen' => $this->post('departmen'),
			'program' => $this->post('program'),
			'phone' => $this->post('phone'),
			'position' => $this->post('position'),
		];
		$query = $this->db->insert('user', $registerData);
        $output = [
            'errCode' => '00',
            'message' => 'Berhasil mendaftarkan user',
		];
		if($this->db->affected_rows() > 0){
			$output['message'] = 'Berhasil mendaftarkan pengguna baru!';
		} else {
			$output['errCode'] = '01';
			$output['message'] = 'Gagal mendaftarkan pengguna baru, silahkan coba lagi.';
		}
        $this->set_response($output, 200);
	}
	
	public function login_post() {
		$nim = $this->post('nim');
		$password = $this->post('password');
		$query = $this->db
					->where('nim', $nim)
					->where('password', $password)
					->get('user');
        $output = [
            'errCode' => '00',
            'message' => 'Berhasil mendaftarkan user',
		];
		if($query->num_rows() > 0){
			$output['message'] = 'Berhasil masuk';
		}
		if($query->num_rows() == 0){
			$output['errCode'] = '01';
			$output['message'] = 'NIM atau Password Salah!';
		}
        $this->set_response($output, 200);
    }
}
