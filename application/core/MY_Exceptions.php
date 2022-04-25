<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

    function __construct() {
        parent::__construct();
    }

	// if($this->session->is_user == NULL)
    public function show_404($page = '', $log_error = TRUE)
    {
        $CI =& get_instance();
 		if(!empty(strpos(current_url(),ADMIN_URL))){
 
 			$pagedata['title'] = lang("BRAND_NAME")." | Page Not Found";
			$CI->load->view('admin/error_404');
	        echo $CI->output->get_output();
		}else{
			$pagedata['title'] = lang("BRAND_NAME")." | Page Not Found";
			$CI->load->view('admin/error_404');
	        echo $CI->output->get_output();
	        exit;
		}

    }
}