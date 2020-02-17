<?php

/*
  classname :  Year_period_model
  Date      : 29-11-2017 02:10:48
 
 */
class Year_period_model extends Abstract_model {

    public $table           = 'p_year_period';
    public $pkey            = 'p_year_period_id';
    public $alias           = '';

    public $fields          = array(
								'p_year_period_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Year Period Id' ),
 								'code'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),
 								'start_date'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Start Date'),
                                'end_date'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'End Date'),
                                'period_status_id'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Period Status Id'),
 								'description'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "(
        select a.*, b.code status_code
        from p_year_period a, p_status_list b
        where a.period_status_id = b.p_status_list_id
    ) x";

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

        $this->record['start_date'] = $this->record['start_date'] == '' ? null : date('d-M-Y', strtotime($this->record['start_date']));
        $this->record['end_date'] = $this->record['end_date'] == '' ? null : date('d-M-Y', strtotime($this->record['end_date']));
        return true;
    }

}

/* End of file Year_period_model.php */