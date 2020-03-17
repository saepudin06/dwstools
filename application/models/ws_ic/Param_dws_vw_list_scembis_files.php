<?php

/*
  classname :  Param_dws_vw_list_scembis_files
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_vw_list_scembis_files extends Abstract_model {

    public $table           = 'ic_dws.dws_p_schembis';
    public $pkey            = 'p_schembis_id';
    public $alias           = '';

    public $fields          = array(
                                'p_schembis_id' => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'p_schembis_id'),
                                'p_regulation_id' => array('type' => 'int', 'nullable' => true, 'unique' => false, 'display' => 'Regulation No'),
                                'p_schembis_type_id' => array('type' => 'int', 'nullable' => true, 'unique' => false, 'display' => 'Schem Type'),
                                'vc_name' => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Vc Name'),
                                'description' => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),
                                'valid_from' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Valid From' ),
                                'valid_until' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Valid Until' ),
                                'fax' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Fax' ),

                                'total_tier' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Total Tier' ),
                                'limit1' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Limit 1' ),
                                'limit2' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Limit 2' ),
                                'limit3' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Limit 3' ),
                                'rate_tier1' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Rate Tier 1' ),
                                'rate_tier2' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Rate Tier 2' ),
                                'rate_tier3' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Rate Tier 3' ),
                                'rate_tier4' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Rate Tier 4' ),
                                'cap_revenue' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Cap Revenue' ),
                                'flat_rate' => array ( 'type' => 'int' , 'nullable' => true , 'unique' => false , 'display' =>  'Flat Rate' ),

                                'create_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Create Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'update_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Update Date' ),
                                'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' ),
                            );

    public $selectClause    = "*";
    public $fromClause      = "ic_dws.vw_list_scembis_files";

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
            $this->db->set('create_date', "current_date", false);
            $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);
        }else {
            //do something
            //example:
            /* $this->record['update_date'] = date('Y-m-d');
            $this->record['updated_by'] = $userdata['user_name']; */
            //if false please throw new Exception

        }

        foreach ($this->fields as $val => $value) {
            if ($value['type'] == 'int' && $value['nullable'] && !isset($value['pkey'])){
                $this->record[$val] = $this->record[$val] == '' ? null : $this->record[$val];
            }
        }

        $this->db->set('update_date', "current_date", false);
        $this->record['updated_by'] = $userdata['user_name'];
        $this->record['valid_from'] = date('d-M-Y', strtotime($this->record['valid_from']));
        $this->record['valid_until'] = $this->record['valid_until'] == '' ? null : date('d-M-Y', strtotime($this->record['valid_until']));
        return true;
    }

}

/* End of file Param_dws_vw_list_scembis_files.php */