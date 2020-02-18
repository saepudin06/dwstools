<?php


class Param_bill_organization_model extends Abstract_model {

   
    public $table           = "itkp_dws.p_organization";
    public $pkey            = "itkp_dws.p_organization_id";
    public $alias           = "";
    public $fields          = array(
                            );

    public $selectClause    = "*";
    public $fromClause      = "itkp_dws.p_organization";
    public $refs            = array();
    public $tempid          = '';

    function __construct() {
        parent::__construct();
    }


    function validate() {
        $ci =& get_instance();
        $userdata = $ci->session->userdata;

        if($this->actionType == 'CREATE') {

            $this->db->set('lastupdate',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updateby'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table);


        }else {

            $this->db->set('lastupdate',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updateby'] = $userdata['user_name'];

        }
        return true;
    }

}