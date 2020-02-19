<?php

/**
 * Process_validation_process Model
 *
 */
class Process_validation_process extends Abstract_model {

    public $table           = "";
    public $pkey            = "";
    public $alias           = "";

    public $fields          = array();

    public $selectClause    = "*";
    public $fromClause      = "v_job_control";

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
            $this->db->set('creation_date',"now()",false);
            // $this->record['created_by'] = $userdata['user_name'];

            $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);

        } else {
            //do something
            //example:
            //if false please throw new Exception
        }

        $res_code = $this->db->where('p_reference_list_id', $this->record['input_data_class_id'])->get('p_reference_list')->row_array();
        $periode = $this->db->where('p_finance_period_id', $this->record['p_finance_period_id'])->get('p_finance_period')->row_array();
        $this->record['input_file_name'] = "IDD_OLO_VALIDATION_".$res_code['code']."_".$periode['p_finance_period_id'];
        $this->record['parameters'] = $periode['p_finance_period_id'];

        // $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
        // $this->record['updated_by'] = $userdata['user_name'];
        return true;
    }
    
    function setFromClause($fromClause) {
        $this->fromClause = $fromClause;
    }

}

/* End of file Process_validation_process.php */