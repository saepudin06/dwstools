<?php

/*
  classname :  Statuslist_model
  Date      : 29-11-2017 02:11:12
 
 */
class Statuslist_model extends Abstract_model {

    public $table           = 'p_status_list';
    public $pkey            = 'p_status_list_id';
    public $alias           = '';

    public $fields          = array(
								'p_status_list_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Status List Id' ),
 								'code'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Code' ),
 								'p_status_type_id'=> array (  'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'P Status Type Id' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'update_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' ),
                                'value'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "p_status_list";

    public $refs            = array();
    public $idretype        ='';

    function __construct() {
        parent::__construct();
        // $this->db = $this->load->database('tos_wsidd_prod',TRUE);
        // $this->db->_escape_char = ' ';
    }

    function validate() {

        $ci =& get_instance();

        $userdata = $ci->session->userdata;
       // $idreferencetype = $this->idretype;

        if($this->actionType == 'CREATE') {


            $this->db->set('creation_date',"now()",false);
            $this->record['created_by'] = $userdata['user_name'];
            $this->db->set('update_date',"now()",false);
            $this->record['update_by'] = $userdata['user_name'];
            // $this->record['p_reference_type_id'] = $idreferencetype;


            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
			
			if(empty($this->record['p_status_type_id']) || $this->record['p_status_type_id'] == '')
			unset($this->record['p_status_type_id']);

        }else {
          
            $this->db->set('update_date',"now()",false);
            $this->record['update_by'] = $userdata['user_name'];
			
			if(empty($this->record['p_status_type_id']) || $this->record['p_status_type_id'] == '')
			unset($this->record['p_status_type_id']);

        }
        return true;
    }

}

/* End of file Statuslist_model.php */