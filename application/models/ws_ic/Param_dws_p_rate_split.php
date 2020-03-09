<?php

/*
  classname :  Param_dws_p_rate_split
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_p_rate_split extends Abstract_model {

    public $table           = 'ic_dws.dws_p_rate_split';
    public $pkey            = 'p_rate_split_id';
    public $alias           = '';

    public $fields          = array(
                                'p_rate_split_id' => array ( 'pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => false, 'display' =>  'P Rate Split Id' ),
                                'orig_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Code Org' ),
                                'p_schembis_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'VC Name' ),
                                'p_zone_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Code Zone' ),
                                'tier_id' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Code Tier' ),
                                'rate' => array ( 'type' => 'int' , 'nullable' => false , 'unique' => false , 'display' =>  'Rate' ),

                                'create_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'update_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Update By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.vw_rate_split";

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
            // $this->db->set('create_date', "now()", false);
            $this->db->set('create_date', "current_date", false);
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
        return true;
    }

}

/* End of file Param_dws_p_rate_split.php */