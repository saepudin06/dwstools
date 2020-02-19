<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Process_validation_process_controller
* @version 07/05/2015 12:18:00
*/
class Process_validation_process_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','job_control_id');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $i_search = getVarClean('i_search', 'str', '');

        $input_data_control_id = getVarClean('input_data_control_id', 'int', 0);

        try {

            $ci = & get_instance();
            $ci->load->model('ws_idd_olo/process_validation_process');
            $table = $ci->process_validation_process;

            $req_param = array(
                "sort_by" => $sidx,
                "sord" => $sord,
                "limit" => null,
                "field" => null,
                "where" => null,
                "where_in" => null,
                "where_not_in" => null,
                "search" => $_REQUEST['_search'],
                "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
                "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
                "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null
            );

            // Filter Table
            $req_param['where'] = array("input_data_control_id = $input_data_control_id");

            $table->setJQGridParam($req_param);
            $count = $table->countAll();

            if ($count > 0) $total_pages = ceil($count / $limit);
            else $total_pages = 1;

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - ($limit); // do not put $limit*($page - 1)

            $req_param['limit'] = array(
                'start' => $start,
                'end' => $limit
            );

            $table->setJQGridParam($req_param);

            if ($page == 0) $data['page'] = 1;
            else $data['page'] = $page;

            $data['total'] = $total_pages;
            $data['records'] = $count;

            $data['rows'] = $table->getAll();
            $data['success'] = true;
            
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function crud() {

        $data = array();
        $oper = getVarClean('oper', 'str', '');
        switch ($oper) {
            // case 'add' :
            //     $data = $this->create();
            // break;

            // case 'edit' :
            //     $data = $this->update();
            // break;

            // case 'del' :
            //     $data = $this->destroy();
            // break;

            default :
                $data = $this->read();
            break;
        }

        return $data;
    }

    function read_log() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','counter_no');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $i_search = getVarClean('i_search', 'str', '');

        $job_control_id = getVarClean('job_control_id', 'int', 0);

        try {

            $ci = & get_instance();
            $ci->load->model('ws_idd_olo/process_validation_process');
            $table = $ci->process_validation_process;
            $table->setFromClause("log_background_job");

            $req_param = array(
                "sort_by" => $sidx,
                "sord" => $sord,
                "limit" => null,
                "field" => null,
                "where" => null,
                "where_in" => null,
                "where_not_in" => null,
                "search" => $_REQUEST['_search'],
                "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
                "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
                "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null
            );

            // Filter Table
            $req_param['where'] = array("job_control_id = $job_control_id");

            $table->setJQGridParam($req_param);
            $count = $table->countAll();

            if ($count > 0) $total_pages = ceil($count / $limit);
            else $total_pages = 1;

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - ($limit); // do not put $limit*($page - 1)

            $req_param['limit'] = array(
                'start' => $start,
                'end' => $limit
            );

            $table->setJQGridParam($req_param);

            if ($page == 0) $data['page'] = 1;
            else $data['page'] = $page;

            $data['total'] = $total_pages;
            $data['records'] = $count;

            $data['rows'] = $table->getAll();
            $data['success'] = true;
            
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }

    function submit_job() {
        $ci = & get_instance();
        $ci->load->model('ws_idd_olo/process_validation_process');
        $table = $ci->process_validation_process;
        $userdata = $ci->session->userdata;

        $data = array("success" => false, "message" => "");
        $data_type = getVarClean("data_type", "str", "");
        $data_control_id = getVarClean("input_data_control_id", "int", 0);

        try {
            $sql = "select f_submit_job ('".$data_type."', ".$data_control_id.", '".$userdata['user_name']."') as hasil";
            // $sql = "select nvl(0,1) as hasil";
            $res = $table->db->query($sql)->row_array();
            $data['message'] = $res['hasil'];
            $data['success'] = true;
        } catch (Exception $e){
            $data["message"] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

    function cancel_all_job() {
        $ci = & get_instance();
        $ci->load->model('ws_idd_olo/process_validation_process');
        $table = $ci->process_validation_process;
        $userdata = $ci->session->userdata;

        $data = array("success" => false, "message" => "");
        $data_control_id = getVarClean("input_data_control_id", "int", 0);

        try {
            $sql = "select public.f_cancel_all_job( ".$data_control_id.", '".$userdata['user_name']."') as hasil";
            // $sql = "select nvl(0,1) as hasil";
            $res = $table->db->query($sql)->row_array();
            $data['message'] = $res['hasil'];
            $data['success'] = true;
        } catch (Exception $e){
            $data["message"] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

    function cancel_last_job() {
        $ci = & get_instance();
        $ci->load->model('ws_idd_olo/process_validation_process');
        $table = $ci->process_validation_process;
        $userdata = $ci->session->userdata;

        $data = array("success" => false, "message" => "");
        $data_control_id = getVarClean("input_data_control_id", "int", 0);

        try {
            $sql = "select public.f_cancel_last_job( ".$data_control_id.", '".$userdata['user_name']."') as hasil";
            // $sql = "select nvl(0,1) as hasil";
            $res = $table->db->query($sql)->row_array();
            $data['message'] = $res['hasil'];
            $data['success'] = true;
        } catch (Exception $e){
            $data["message"] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

}

/* End of file Process_validation_process_controller.php */