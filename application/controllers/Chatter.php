<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chatter extends CI_Controller {

	/*
		** @author subash_diego
		** @live chat application
		** @in php 7.2
		** (simple and robust)
	*/

	function __construct(){

		parent::__construct();

		if($this->session->userdata('user')){
			redirect('live_chat');
		}
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function signup()
	{
		$this->load->view('signup');
	}

	public function sign_up_access($perms = []){

		$email = $this->input->post('email');
		$pass  = $this->input->post('password');
		$name  = $this->input->post('name');

		$this->db->where('email',$email);
		if(count($this->db->get('users')->result())){

			$this->load->view('signup',['message' => 'email id already exist']);

		}else{

			if($this->db->insert('users',['name' => $name,'email' => $email,'password' => sha1($pass)])){
				$this->load->view('login',['message' => 'sign up success, please login here']);
			}else{
				$this->load->view('signup',['message' => 'Sign up failed']);
			}
		}
	}

	/* bit is used to generate */
    function token_generator( $bit = ''){

        $bit!='' ? (int) $bit : 32; 

        $token = bin2hex(random_bytes($bit));

        return $token;
    }

	public function login_access($perms =[]){

		$email = $this->input->post('email');
		$pass  = $this->input->post('password');

		$this->db->where(['email' => $email,'password' => sha1($pass)]);
		$user_info = $this->db->get('users')->row();

		if(!empty($user_info)){

			/*INSERT INTO RANDOM */

			$token 	 = $this->token_generator(32);

			$user_id = $user_info->id??'';

			//UPDATE TOKEN
				$this->db->where('id',$user_id);
				$this->db->update('users',['token' => $token]);

			//REFRESHED DATA IS GETTING
				$this->db->where('id',$user_id);
				$user_info = $this->db->get('users')->row();

				$this->session->set_userdata('user',$user_info);

				if($this->session->userdata('user')){
					redirect('live_chat');
				}else{
					$this->load->view('login',['message' => 'Session cannot set now, try again later']);
				}
		}else{

			$this->load->view('login',['message' => 'Email and password not valid']);
		}
	}
}
