<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Utilities extends CI_Controller {
    // redirect if needed, otherwise display the user list
    function index()
    {
        $this->load->database();
    }

    function save_to_excel(){
        
        $this->load->library(array('ws_ic/report_daily_validation_controller'));
        $this->report_daily_validation_controller->save_to_excel();

    }
}
?>