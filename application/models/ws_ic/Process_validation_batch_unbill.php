<?php

/**
 * Process_validation_batch Model
 *
 */
class Process_validation_batch_unbill extends Abstract_model {

    public $table           = "input_data_control";
    public $pkey            = "input_data_control_id";
    public $alias           = "";

    public $fields          = array(
                                'input_data_control_id'             => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'input_data_control_id'),
                                'input_data_class_id'           => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'input_data_class_id'),
                                'p_finance_period_id'    => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Finance Period'),
                                'input_file_name'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'input_file_name'),
                                'parameters'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'parameters'),

                                'creation_date'          => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),

                            );

    public $selectClause    = "*";
    public $fromClause      = "v_input_data_control";

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

        $input_data_class_id = 8;
        $periode = $this->db->where('p_finance_period_id', $this->record['p_finance_period_id'])->get('p_finance_period')->row_array();
        
        $this->db->set('input_file_name', "VALIDATIONUNBILL_IC_".$periode['finance_period_code']); //IC_POPULATEDATAIC_2019-JAN
        $this->db->set('parameters', $periode['finance_period_code']);
        $this->db->set('input_data_class_id', $input_data_class_id);

        // $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
        // $this->record['updated_by'] = $userdata['user_name'];
        return true;
    }

}

/* End of file Process_validation_batch.php */