<?php

/*
  classname :  Param_dws_p_tariff_used
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_p_tariff_used extends Abstract_model {

    public $table           = 'ic_dws.dws_p_tariff_used';
    public $pkey            = 'p_tariff_used_id';
    public $alias           = '';

    public $fields          = array(
                                'p_tariff_used_id' => array ( 'pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => false, 'display' =>  'P Tariff Used Id' ),
                                'orig_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Organization' ),
                                'term_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Term' ),
                                'zone_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Zone' ),
                                'usage' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Usage' ),
                                'usage2' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Usage2' ),
                                'total' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Total' ),
                                'valid_from' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Valid From' ),
                                'valid_until' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Valid Until' ),

                                'created_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'update_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Update By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.vw_dws_tariff_used";

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
            // $this->db->set('created_date', "now()", false);
            $this->db->set('created_date', "current_date", false);
            $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        // $this->db->set('update_date', "now()", false);
        $this->db->set('update_date', "current_date", false);
        $this->record['update_by'] = $userdata['user_name'];
        $this->record['valid_from'] = date('d-M-Y', strtotime($this->record['valid_from']));
        $this->record['valid_until'] = $this->record['valid_until'] == '' ? null : date('d-M-Y', strtotime($this->record['valid_until']));
        return true;
    }

}

/* End of file Param_dws_p_tariff_used.php */