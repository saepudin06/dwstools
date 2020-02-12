<?php

/*
  classname :  Preferencetype
  Date      : 29-11-2017 02:10:48
 
 */
class Globalparam_model extends Abstract_model {

    public $table           = 'p_global_param';
    public $pkey            = 'p_global_param_id';
    public $alias           = '';

    public $fields          = array(
								'p_global_param_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Global Param Id' ),
                                'code'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => true , 'display' =>  'Code' ),
 								'value'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Value' ),
 								'type_1'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Type 1' ),
                                'is_range'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Is Range' ),
                                'value_2'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Value 2' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = " * ";
    public $fromClause      = "p_global_param";

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
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata['user_name'];
            */
            $this->db->set('creation_date',"now()",false);
            $this->record['created_by'] = $userdata['user_name'];
            $this->db->set('updated_date',"now()",false);
            $this->record['updated_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
			 
			 

        }else {
            //do something
            //example:
            /* $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata['user_name']; */
            //if false please throw new Exception

           $this->db->set('updated_date',"now()",false);
            $this->record['updated_by'] = $userdata['user_name'];

        }
        return true;
    }

}

/* End of file Icons.php */