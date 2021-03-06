<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agegate extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
        $this->load->helper('form');
        header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
    }

    function index()
    {
        $data['message'] = null;
        if($this->session->userdata('user_age') == 'approved')
        {
            if($this->agent->is_mobile() == true)
            {
                redirect('mobile/twitter');
            }else{
                redirect('landing');
            }
            exit;
        }

        if($this->session->userdata('user_age') == 'denied')
        {
            redirect('restricted/age');
            exit;
        }

        $data['yield'] = $this->load->view('facebook/agegate',$data, TRUE);
        if($this->agent->is_mobile() == true)
        {
            $this->load->view('layout/mobile', $data);
        }else{
            header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
            $this->load->view('layout/general', $data);
        }
    }
  
    function authenticate()
    {   
        $this->load->helper('birthday_helper');
        $this->load->library('user_agent');

        $data = array();
        
        $data['message'] = null;

        $day    = ($_POST['day'] != 'value')     ? $_POST['day']     : 1;
        $month  = ($_POST['month'] != 'value')   ? $_POST['month']   : 1;
        $year   = ($_POST['year'] != 'value')    ? $_POST['year']    : 2012;
        $age = birthday("$day/$month/$year");
        
        if ($age < 18){
            header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
            $this->session->set_userdata('user_age', 'denied');
            redirect('restricted/age');
            
        }else{
            $this->session->set_userdata('user_age', 'approved');
            if($this->agent->is_mobile() == true)
            {
                redirect('mobile/twitter');
            }else{
                header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
                redirect('landing','location');
            }            
        }
    }
}