<?php

/*
  classname :  Preferencelist
  Date      : 29-11-2017 02:11:12
 
 */
class Referencelist_model extends Abstract_model {

    public $table           = 'p_reference_list';
    public $pkey            = 'p_reference_list_id';
    public $alias           = 'prl';

    public $fields          = array(
								'p_reference_list_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Reference List Id' ),
 								'code'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => true , 'display' =>  'Code' ),
 								'p_reference_type_id'=> array (  'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'P Reference Type Id' ),
 								'reference_name'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Reference Name' ),
 								'listing_no'=> array (  'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Listing No' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' ),
                                'value'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    =   " 
									prl.p_reference_list_id,
 									prl.code,
 									prl.p_reference_type_id,
 									prl.reference_name,
 									prl.listing_no,
 									prl.description,
 									prl.creation_date,
 									prl.created_by,
 									prl.updated_date,
 									prl.updated_by
                                ";
    public $fromClause      = " p_reference_list prl ";

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


            $this->db->set('creation_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['created_by'] = $userdata['user_name'];
            $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updated_by'] = $userdata['user_name'];
            // $this->record['p_reference_type_id'] = $idreferencetype;


            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
			
			if(empty($this->record['p_reference_type_id']) || $this->record['p_reference_type_id'] == '')
			unset($this->record['p_reference_type_id']);

        }else {
          
            $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updated_by'] = $userdata['user_name'];
			
			if(empty($this->record['p_reference_type_id']) || $this->record['p_reference_type_id'] == '')
			unset($this->record['p_reference_type_id']);

        }
        return true;
    }

}

/* End of file Icons.php */