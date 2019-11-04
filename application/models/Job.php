<?php

/**
 * Pembuatan schema Model
 *
 */
class Job extends Abstract_model {

    public $table           = "log_process_job_m4l";
    public $pkey            = "";
    public $alias           = "lg";

    public $fields          = array(


                            );

    public $selectClause    = " lg.*";
    public $fromClause      = "log_process_job_m4l ";

    public $refs            = array();

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('default', TRUE);
        $this->db->_escape_char = ' ';
    }

    function validate() {
        $ci =& get_instance();

        if($this->actionType == 'CREATE') {
            //do something
            // example :
            $this->record['log_date'] = date('Y-m-d');
            $this->record['log_id'] = date('Ymdhis');
            //$this->record['created_date'] = date('Y-m-d');
            //$this->record['updated_date'] = date('Y-m-d');

        }else {
            //do something
            //example:
            //$this->record['updated_date'] = date('Y-m-d');
            //if false please throw new Exception

        }
        return true;
    }

    function job_m4l(){



    }

    function job_tiara(){



    }
    
}

/* End of file Users.php */
