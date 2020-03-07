<?php

/*
  classname :  Param_dws_p_schembis_type
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_p_schembis_type extends Abstract_model {

    public $table           = 'ic_dws.dws_p_schembis_type';
    public $pkey            = 'p_schembis_type_id';
    public $alias           = '';

    public $fields          = array(
                                'p_schembis_type_id' => array ( 'pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => false, 'display' =>  'P Schembis Type Id' ),
                                'code' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Code' ),
                                'description' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Description' ),

                                'created_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'create_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
                                'update_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Update By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.dws_p_schembis_type";

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
            $this->record['create_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
             //$this->record[$this->pkey] = $this->generate_seq_id('prt.p_reference_type_id');
        }else {
            //do something
            //example:
            /* $this->record['updated_date'] = date('Y-m-d');
            $this->record['update_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        // $this->db->set('updated_date', "now()", false);
        $this->db->set('updated_date', "current_date", false);
        $this->record['update_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_dws_p_schembis_type.php */