<?php

/*
  classname :  Param_dws_p_zone
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_p_zone extends Abstract_model {

    public $table           = 'ic_dws.dws_p_zone';
    public $pkey            = 'p_zone_id';
    public $alias           = '';

    public $fields          = array(
                                'p_zone_id' => array ( 'pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => false, 'display' =>  'P Zone Id' ),
                                'code' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),
                                'p_sap_zone_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'SAP Code' ),
                                'sap_code' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'SAP Code' ),
                                'zone_soki' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Zone Soki' ),
                                'description' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.dws_p_zone";

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
            /* $this->record['created_date'] = date('Y-m-d');
            $this->record['created_by'] = $userdata['user_name'];
            $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name'];
            */

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
			 
			 

        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        $this->db->set('update_date', "now()", false);
        $this->record['update_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_dws_p_zone.php */