<?php

/*
  classname :  Param_dws_vw_scenario_schembis
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_vw_scenario_schembis extends Abstract_model {

    public $table           = 'ic_dws.dws_p_call_scenario';
    public $pkey            = 'p_call_scenario_id';
    public $alias           = '';

    public $fields          = array(
                                'p_call_scenario_id' => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'p_call_scenario_id'),
                                'p_schembis_id' => array('type' => 'int', 'nullable' => false, 'unique' => false, 'display' => 'VC Name'),
                                'orig_id' => array('type' => 'int', 'nullable' => false, 'unique' => false, 'display' => 'Orig Code'),
                                'term_id' => array('type' => 'int', 'nullable' => false, 'unique' => false, 'display' => 'Term Code'),
                                'zone_id' => array('type' => 'int', 'nullable' => false, 'unique' => false, 'display' => 'Zone Code'),
                                'p_service_type_id' => array('type' => 'int', 'nullable' => false, 'unique' => false, 'display' => 'SVC Code'),

                                'valid_from' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Valid From' ),
                                'valid_until' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Valid Until' ),

                                'created_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'update_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Update By' ),
                            );

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.vw_scenario_schembis";

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
            $this->db->set('created_date', "current_date", false);
            $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        $this->db->set('update_date', "current_date", false);
        $this->record['update_by'] = $userdata['user_name'];
        $this->record['valid_from'] = date('d-M-Y', strtotime($this->record['valid_from']));
        $this->record['valid_until'] = $this->record['valid_until'] == '' ? null : date('d-M-Y', strtotime($this->record['valid_until']));
        return true;
    }

}

/* End of file Param_dws_vw_scenario_schembis.php */