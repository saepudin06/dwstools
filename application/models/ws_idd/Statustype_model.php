<?php

/*
  classname :  Statustype_model
  Date      : 29-11-2017 02:10:48
 
 */
class Statustype_model extends Abstract_model {

    public $table           = 'p_status_type';
    public $pkey            = 'p_status_type_id';
    public $alias           = '';

    public $fields          = array(
								'p_status_type_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Status Type Id' ),
                                'code'=> array (  'type' => 'str' , 'nullable' => false, 'unique' => false , 'display' =>  'Code' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "p_status_type";

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
            $this->db->set('creation_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['created_by'] = $userdata['user_name'];
            $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updated_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
			 
			 

        }else {
            //do something
            //example:
            /* $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata['user_name']; */
            //if false please throw new Exception

           $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updated_by'] = $userdata['user_name'];

        }
        return true;
    }

}

/* End of file Statustype_model.php */