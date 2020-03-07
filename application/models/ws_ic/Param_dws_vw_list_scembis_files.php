<?php

/*
  classname :  Param_dws_vw_list_scembis_files
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_vw_list_scembis_files extends Abstract_model {

    public $table           = 'ic_dws.dws_p_tariff_used';
    public $pkey            = 'p_tariff_used_id';
    public $alias           = '';

    public $fields          = array();

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.vw_list_scembis_files";

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
            // $this->db->set('created_date', "now()", false);
            // $this->db->set('created_date', "current_date", false);
            // $this->record['created_by'] = $userdata['user_name'];

            // $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        // $this->db->set('update_date', "now()", false);
        // $this->db->set('update_date', "current_date", false);
        // $this->record['update_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_dws_vw_list_scembis_files.php */