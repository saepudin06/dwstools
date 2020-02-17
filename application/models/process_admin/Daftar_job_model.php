<?php

/*
  classname :  Daftar_job_model
  Date      : 29-11-2017 02:10:48
 
 */
class Daftar_job_model extends Abstract_model {

    public $table           = 'p_job';
    public $pkey            = 'p_job_id';
    public $alias           = '';

    public $fields          = array(
								'p_job_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Job Id' ),
                                'p_job_type_id'=> array ('type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Job Type Id' ),
 								'code'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),
                                'procedure_name'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Procedure Name' ),
                                'listing_no'=> array (  'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'List NO' ),
                                'is_parallel'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Parallel' ),
                                'parallel_degree'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Tingkat Parallel' ),
                                'is_finish'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Selesai' ),
                                'is_reprocess'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Proses Ulang' ),
                                'exclude_in_queues'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Exclude dalam antrian' ),
                                'parent_id'=> array (  'type' => 'int' , 'nullable' => true, 'unique' => false , 'display' =>  'Parent ID' ),
                                'control_table_name'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Nama Table Control' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "vw_p_job";

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

/* End of file Daftar_job_model.php */