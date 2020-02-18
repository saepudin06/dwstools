<?php

/*
  classname :  Param_bill_billcomponent
  Date      : 29-11-2017 02:10:48
 
 */
class Param_bill_billcomponent extends Abstract_model {

    public $table           = 'itkp_dws.p_bill_component';
    public $pkey            = 'p_bill_component_id';
    public $alias           = '';

    public $fields          = array(
								'p_bill_component_id'=> array (   'pkey' => true,'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'P Bill Component Id' ),
                                'code'=> array (  'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),
                                'description'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Description' ),
                                'display_order'=> array (  'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Urutan' ),
                                'p_invoice_component_id'=> array (  'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'P Invoice Component ID' ),
 								'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
 								'update_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Update By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "itkp_dws.p_bill_component";

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
            $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name'];
            */

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
			 
			 

        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        $this->db->set('update_date',"now()",false);
        $this->record['update_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_bill_billcomponent.php */