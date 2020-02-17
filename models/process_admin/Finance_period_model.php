<?php

/*
  classname :  Finance_period_model
  Date      : 29-11-2017 02:11:12
 
 */
class Finance_period_model extends Abstract_model {

    public $table           = 'p_finance_period';
    public $pkey            = 'p_finance_period_id';
    public $alias           = '';

    public $fields          = array(
								'p_finance_period_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Finance Period Id' ),
 								'finance_period_code'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => true , 'display' =>  'Finance Period Code' ),
                                'start_date'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Start Date'),
                                'end_date'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'End Date'),
                                'period_status_id'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Period Status Id'),
                                'p_year_period_id'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'P Year Period Id'),
                                'ref_no'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Ref No' ),
                                'ref_date'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Ref Date' ),
 								'description'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
 								'creation_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Creation Date' ),
 								'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
 								'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
 								'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' ),
                                'invoice_date'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Invoice Date' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "p_finance_period";

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
            $this->db->set('updated_date',"now()",false);
            $this->record['updated_by'] = $userdata['user_name'];
            // $this->record['p_reference_type_id'] = $idreferencetype;


            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
			
			if(empty($this->record['p_reference_type_id']) || $this->record['p_reference_type_id'] == '')
			unset($this->record['p_reference_type_id']);

        }else {
          
            $this->db->set('updated_date',"now()",false);
            $this->record['updated_by'] = $userdata['user_name'];
			
			if(empty($this->record['p_reference_type_id']) || $this->record['p_reference_type_id'] == '')
			unset($this->record['p_reference_type_id']);

        }

        $this->record['start_date'] = $this->record['start_date'] == '' ? null : date('d-M-Y', strtotime($this->record['start_date']));
        $this->record['end_date'] = $this->record['end_date'] == '' ? null : date('d-M-Y', strtotime($this->record['end_date']));
        $this->record['ref_date'] = $this->record['ref_date'] == '' ? null : date('d-M-Y', strtotime($this->record['ref_date']));
        return true;
    }

}

/* End of file Finance_period_model.php */