<?php

/*
  classname :  First_job
  Date      : 29-11-2017 02:10:48
 
 */
class First_job extends Abstract_model {

    public $table           = 'p_first_job';
    public $pkey            = 'p_first_job_id';
    public $alias           = '';

    public $fields          = array(
								'p_first_job_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P First Job Id' ),
                                'p_job_id'=> array ('type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Job Id' ),
                                'data_type_id'=> array ('type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Program Code' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "(
        select a.*, 
            b.code AS program_code,
            c.code AS code_type
        from p_first_job a, p_reference_list b, p_reference_type c
        where a.data_type_id = b.p_reference_list_id
            and b.p_reference_type_id = c.p_reference_type_id 
    ) a";

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
            $this->db->set('creation_date',"now()",false);
            $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
        } else {
            //do something
            //example:
        }

        $this->db->set('updated_date',"now()",false);
        $this->record['updated_by'] = $userdata['user_name'];
        
        return true;
    }

}

/* End of file First_job.php */