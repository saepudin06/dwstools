<?php

/*
  classname :  Report_daily_validation
  Date      : 29-11-2017 02:10:48
 
 */
class Report_daily_validation extends Abstract_model {

    public $table           = '';
    public $pkey            = '';
    public $alias           = '';

    public $fields          = array();

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.vw_rpt_harian_poti_ic";

    public $refs            = array();

    function __construct() {
        parent::__construct();
        // $this->db = $this->load->database('tos_wsidd_prod',TRUE);
        // $this->db->_escape_char = ' ';
    }

    function validate() {

        $ci =& get_instance();
        $userdata = $ci->session->userdata;

        if($this->actionType == 'CREATE') {
            //do something
            // example :
            // $this->db->set('created_date', "current_date", false);
            // $this->record['created_by'] = $userdata['user_name'];

            // $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
        }else {
            //do something
        }

        // $this->db->set('update_date', "current_date", false);
        // $this->record['update_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Report_daily_validation.php */