<?php

/*
  classname :  Param_dws_p_signature
  Date      : 29-11-2017 02:10:48
 
 */
class Param_dws_p_signature extends Abstract_model {

    public $table           = 'ic_dws.dws_p_signature';
    public $pkey            = 'p_signature_id';
    public $alias           = '';

    public $fields          = array(
                                'p_signature_id' => array ( 'pkey' => true, 'type' => 'int', 'nullable' => false, 'unique' => false, 'display' =>  'P Signature Id' ),
                                'user_id' => array ( 'type' => 'str' , 'nullable' => false, 'unique' => false , 'display' =>  'User ID' ),
                                'signaturerole_id' => array ( 'type' => 'str' , 'nullable' => false, 'unique' => false , 'display' =>  'Signature Role ID' ),
                                'position_name' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Position Name' ),
                                'signature_img' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Signature Image' ),
                                'valid_from' => array ( 'type' => 'str' , 'nullable' => false , 'unique' => false , 'display' =>  'Valid From' ),
                                'valid_until' => array ( 'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Valid Until' ),

                                'created_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Created Date' ),
                                'created_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Created By' ),
                                'updated_date'=> array (  'type' => 'date' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated Date' ),
                                'updated_by'=> array (  'type' => 'str' , 'nullable' => true , 'unique' => false , 'display' =>  'Updated By' )
                            );

    public $selectClause    = "*";
    public $fromClause      = "(
        select a.p_signature_id, 
            a.user_id,
            a.position_name,
            a.signature_img,
            a.created_date,
            a.created_by,
            a.updated_date,
            a.updated_by,
            a.signaturerole_id,
            to_char(a.valid_from, 'yyyy-mm-dd') valid_from,
            to_char(a.valid_until, 'yyyy-mm-dd') valid_until,
            b.code role_code, 
            b.code_type_Ref,
            c.user_name
        from ic_dws.dws_p_signature a, vw_role_signature_ref b, users c
        where a.signaturerole_id = b.p_reference_list_id
            and a.user_id = c.user_id
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

        $this->db->set('updated_date', "current_date", false);
        $this->record['updated_by'] = $userdata['user_name'];
        $this->record['valid_from'] = date('d-M-Y', strtotime($this->record['valid_from']));
        $this->record['valid_until'] = $this->record['valid_until'] == '' ? null : date('d-M-Y', strtotime($this->record['valid_until']));
        return true;
    }

}

/* End of file Param_dws_p_signature.php */