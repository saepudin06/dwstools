<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Process_validation_batch_controller
* @version 07/05/2015 12:18:00
*/
class Process_validation_batch_unbill_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','input_data_control_id');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $i_search = getVarClean('i_search', 'str', '');

        try {

            $ci = & get_instance();
            $ci->load->model('ws_ic/process_validation_batch_unbill');
            $table = $ci->process_validation_batch_unbill;

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
            $req_param['where'] = array("input_data_class_id = 1");

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
            case 'add' :
                $data = $this->create();
            break;

            case 'edit' :
                $data = $this->update();
            break;

            case 'del' :
                $data = $this->destroy();
            break;

            default :
                $data = $this->read();
            break;
        }

        return $data;
    }


    function create() {

        $ci = & get_instance();
        $ci->load->model('ws_ic/process_validation_batch_unbill');
        $table = $ci->process_validation_batch_unbill;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'CREATE';
        $errors = array();

        if (isset($items[0])){
            $numItems = count($items);
            for($i=0; $i < $numItems; $i++){
                try{

                    $table->db->trans_begin(); //Begin Trans

                        $table->setRecord($items[$i]);
                        $table->create();

                    $table->db->trans_commit(); //Commit Trans

                }catch(Exception $e){

                    $table->db->trans_rollback(); //Rollback Trans
                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
            }else{
                $data['success'] = true;
                $data['message'] = 'Data added successfully';
            }
            $data['rows'] =$items;
        }else {

            try{
                $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items);
                    $table->create();

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data added successfully';
                

            }catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function update() {

        $ci = & get_instance();
        $ci->load->model('ws_ic/process_validation_batch_unbill');
        $table = $ci->process_validation_batch_unbill;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'UPDATE';

        if (isset($items[0])){
            $errors = array();
            $numItems = count($items);
            for($i=0; $i < $numItems; $i++){
                try{
                    $table->db->trans_begin(); //Begin Trans

                        $table->setRecord($items[$i]);
                        $table->update();

                    $table->db->trans_commit(); //Commit Trans

                    $items[$i] = $table->get($items[$i][$table->pkey]);
                }catch(Exception $e){
                    $table->db->trans_rollback(); //Rollback Trans

                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
            }else{
                $data['success'] = true;
                $data['message'] = 'Data update successfully';
            }
            $data['rows'] =$items;
        }else {

            try{
                $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items);
                    $table->update();

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data update successfully';
                
                $data['rows'] = $table->get($items[$table->pkey]);
            }catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function destroy() {
        $ci = & get_instance();
        $ci->load->model('ws_ic/process_validation_batch_unbill');
        $table = $ci->process_validation_batch_unbill;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        try{
            $table->db->trans_begin(); //Begin Trans

            $total = 0;
            if (is_array($items)){
                foreach ($items as $key => $value){
                    if (empty($value)) throw new Exception('Empty parameter');
					$table->remove($value);
                    $data['rows'][] = array($table->pkey => $value);
                    $total++;
                }
            }else{
                $items = (int) $items;
                if (empty($items)){
                    throw new Exception('Empty parameter');
                }
				$table->remove($items);
                $data['rows'][] = array($table->pkey => $items);
                $data['total'] = $total = 1;
            }

            $data['success'] = true;
            $data['message'] = $total.' Data deleted successfully';
            
            $table->db->trans_commit(); //Commit Trans

        }catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
            $data['total'] = 0;
        }
        return $data;
    }

    function html_select_options_reference_type() {
        try {
            $ci = & get_instance();
            $ci->load->model('ws_ic/process_validation_batch_unbill');
            $table = $ci->process_validation_batch_unbill;

            $user_info = $ci->session->userdata;

            $res = $table->db->where('p_reference_type_id', 26)->get('p_reference_list')->result_array();
        
            echo "<select>";
            foreach ($res as $item) {
                echo '<option value="'.$item['p_reference_list_id'].'" title="'.$item['code'].'">'.$item['code'].'</option>';
            }
            echo "</select>";
            exit;
        }catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    function html_select_options_finance_periode() {

        $data = array('success' => false, 'message' => '');

        try {
            $ci = & get_instance();
            $ci->load->model('ws_ic/process_validation_batch_unbill');
            $table = $ci->process_validation_batch_unbill;
            $user_info = $ci->session->userdata;

            $p_year_period_id = getVarClean('p_year_period_id', 'str', '');
            $p_finance_period_id = getVarClean('selected', 'str', '');

            $res = $table->db->where("p_year_period_id", $p_year_period_id)->get('vw_list_open_period')->result_array();
            $select = "";
        
            $select .= '<select  role="select" class="FormElement form-control" style="width: 250px;" id="p_finance_period_id" name="p_finance_period_id" rowid="_empty">';
            foreach ($res as $item) {

                $selected = $p_finance_period_id == $item['p_finance_period_id'] ? "selected" : "";
                $select .= '<option value="'.$item['p_finance_period_id'].'" title="'.$item['finance_period_code'].'" '. $selected.' >'.$item['finance_period_code'].'</option>';
            }
            $select .= "</select>";


            $data['select'] = $select;
            $data['default_value'] = $default_value;
            $data['success'] = true;
            $data['message'] = '';
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

    function html_select_options_year_periode() {

        $data = array('success' => false, 'message' => '');

        try {
            $ci = & get_instance();
            $ci->load->model('ws_ic/process_validation_batch_unbill');
            $table = $ci->process_validation_batch_unbill;

            $user_info = $ci->session->userdata;

            $res = $table->db->get('vw_list_open_year_per')->result_array();
            $select = "";
            $default_value = "";
        
            $select .= "<select id='year_period_id'>";
            $i = 0;
            foreach ($res as $item) {
                $select .= '<option value="'.$item['p_year_period_id'].'" title="'.$item['code'].'">'.$item['code'].'</option>';
                $default_value = $i == 0 ? $item['p_year_period_id'] : $default_value;
                $i++;
            }
            $select .= "</select>";


            $data['select'] = $select;
            $data['default_value'] = $default_value;
            $data['success'] = true;
            $data['message'] = '';
        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }
}

/* End of file process_validation_batch_unbill_controller.php */