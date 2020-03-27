<?php

/*
  classname :  Param_p_customer_konv
  Date      : 29-11-2017 02:10:48
 
 */
class Param_p_customer_konv extends Abstract_model {

    public $table           = 'og_dws.p_customer_konv';
    public $pkey            = 'p_customer_konv_id';
    public $alias           = '';

    public $fields          = array(
                                'p_customer_konv_id' => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'p_customer_konv_id'),
                                'customer' => array('type' => 'str', 'nullable' => false, 'unique' => false, 'display' => 'Customer'),
                                'customer_konv' => array('type' => 'str', 'nullable' => false, 'unique' => false, 'display' => 'Customer Conv'),

                                'created_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' ),
                            );

    public $selectClause    = "*";
    public $fromClause      = "og_dws.p_customer_konv";

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
        $this->record['updated_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_p_customer_konv.php */