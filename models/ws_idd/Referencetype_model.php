<?php

/*
  classname :  Preferencetype
  Date      : 29-11-2017 02:10:48
 
 */
class Referencetype_model extends Abstract_model {

    public $table           = 'p_reference_type';
    public $pkey            = 'p_reference_type_id';
    public $alias           = 'prt';

    public $fields          = array(
								'p_reference_type_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Reference Type Id' ),
 								'code'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),
 								'reference_name'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Reference Name' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    =   " 
									prt.p_reference_type_id,
 									prt.code,
 									prt.reference_name,
 									prt.description,
 									prt.creation_date,
 									prt.created_by,
 									prt.updated_date,
 									prt.updated_by
                                ";
    public $fromClause      = " p_reference_type prt ";

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