<?php

/*
  classname :  Param_dws_p_organization_conv
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_p_organization_conv extends Abstract_model {

    public $table           = 'ic_dws.dws_p_organization_conv';
    public $pkey            = 'p_organization_conv_id';
    public $alias           = '';

    public $fields          = array(
                                'p_organization_conv_id' => array ( 'pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => false, 'display' =>  'P Organization Conv Id' ),
                                'p_organization_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Organization Id' ),
                                'code' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),

                                'created_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "public.vw_dws_p_organization_conv";

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
            $this->db->set('created_date', "current_date", false);
            $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        // $this->db->set('update_date', "now()", false);
        $this->db->set('update_date', "current_date", false);
        $this->record['updated_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_dws_p_organization_conv.php */