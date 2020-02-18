<?php

/**
 * Param_bill_err_msg Model
 *
 */
class Param_bill_err_msg extends Abstract_model {

    public $table           = "og_dws.p_error_message";
    public $pkey            = "code";
    public $alias           = "";

    public $fields          = array();

    public $selectClause    = "*";
    public $fromClause      = "og_dws.p_error_message";

    public $refs            = array();

    function __construct() {
        parent::__construct();
    }

    function validate() {

        $ci =& get_instance();
        $userdata = $ci->session->userdata;

        if($this->actionType == 'CREATE') {
            //do something
            // example :
            // $this->db->set('creation_date',"now()",false);
            // $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);

        } else {
            //do something
            //example:
            //if false please throw new Exception
        }

        // $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
        // $this->record['updated_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Param_bill_err_msg.php */