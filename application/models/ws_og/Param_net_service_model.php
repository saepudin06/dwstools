<?php


class Param_net_service_model extends Abstract_model {

   
    public $table           = "og_dws.p_service_type";
    public $pkey            = "og_dws.p_service_type_id";
    public $alias           = "";
    public $fields          = array(
                            );

    public $selectClause    = "*";
    public $fromClause      = "og_dws.p_service_type";
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