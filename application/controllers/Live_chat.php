<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Namespace */

include __DIR__.'/../third_party/src/Chat.php';

class Live_chat extends CI_Controller{
//class Live_chat extends CI_Controller {

	/*
		** @author subash_diego
		** @live chat application
		** @in php 7.2
		** (simple and robust)
	*/

	protected $clients;

	function __construct(){

		parent::__construct();

		//echo "<pre>"; print_r(get_declared_classes());die;
		
		if(!$this->session->userdata('user')){
			redirect('chatter');
		}
	}

	public function index()
	{
		//print_r($this->session->userdata());die;
					$this->db->order_by('message_id','DESC');
					$this->db->limit(50);
					$this->db->offset(0);
					$this->db->join('users as us','message.user_id = us.id');
		$messages = $this->db->get('message')->result();

		$messages = array_reverse($messages); 

		$this->load->view('chat',['user_list' => $this->db->get('users')->result(),'messages' => $messages]);
	}

	public function logout($value='')
	{
		$this->session->sess_destroy();

		redirect('chatter');
	}
}
