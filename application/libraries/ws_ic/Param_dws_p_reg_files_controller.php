<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Param_dws_p_reg_files_controller
* @version 29-11-2017 02:11:12
*/
class Param_dws_p_reg_files_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','update_date');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('ws_ic/param_dws_p_reg_files');
            $table = $ci->param_dws_p_reg_files;
            $p_regulation_id = getVarClean('p_regulation_id', 'str', '');

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
            $req_param['where'] = array("p_regulation_id = $p_regulation_id");

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
        // $idreferencetype = getVarClean('p_reference_type_id','int',0);
        switch ($oper) {
            case 'add' :
                // permission_check('can-add-referencelist');
				// echo "masuk..";
                $data = $this->create();
            break;

            case 'edit' :
                // permission_check('can-edit-referencelist');
                $data = $this->update();
            break;

            case 'del' :
                // permission_check('can-del-referencelist');
                $data = $this->destroy();
            break;

            default :
                // permission_check('can-view-referencelist');
                $data = $this->read();
            break;
        }

        return $data;
    }


    function create() {

        $ci = & get_instance();
  	
        $ci->load->model('ws_ic/param_dws_p_reg_files');
        $table = $ci->param_dws_p_reg_files;
        
        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'CREATE';
        $errors = array();

      //  $table->record['p_reference_type_id'] = $idreferencetype;

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
       // $idreferencetype = getVarClean('p_reference_type_id','int',0);
        $ci->load->model('ws_ic/param_dws_p_reg_files');
        $table = $ci->param_dws_p_reg_files;
        // $table->getidreferencetype($idreferencetype);

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
        $ci->load->model('ws_ic/param_dws_p_reg_files');
        $table = $ci->param_dws_p_reg_files;

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

    function upload_files(){
        $ci = & get_instance();
        $ci->load->model('ws_ic/param_dws_p_reg_files');
        $table = $ci->param_dws_p_reg_files;
        $userinfo = $ci->session->userdata;

        $data = array("success" => false, "message" => "");

        try {
            $is_upload = $_FILES['uploadParamFile']['size'] != 0;

            if ($is_upload){
                $p_reg_files_id = getVarClean('p_reg_files_id', 'str', '');
                $p_regulation_id = getVarClean('p_regulation_id', 'str', '');
                $path = $_FILES['uploadParamFile']['name']; //blablabla.xls
                $ext  = pathinfo($path, PATHINFO_EXTENSION);
                
                $config['upload_path']   = './uploads/regulation/';
                $config['allowed_types'] = '*';
                $config['max_size']      = '90000000';
                $config['overwrite']     = TRUE;
                $config['file_name']     = "regulation_" . $p_reg_files_id . '_' . date('Ymdhis') . '.' . $ext;
                
                $ci->load->library('upload');
                $ci->upload->initialize($config);
                
                $directory = "";
                $file_name = "";
                if (!$ci->upload->do_upload("uploadParamFile")) {
                    $file_name =  = $ci->upload->display_errors();
                } else {
                    $filedata = $ci->upload->data();
                    $file_name = $config['file_name'];
                    $directory = $config['upload_path'];
                }

                $table->db->trans_begin();
                $table->db->set('file_name', $file_name);
                $table->db->set('directory', $directory);
                $table->db->where('p_reg_files_id', $p_reg_files_id)->update($table->table);
                $table->db->trans_commit();
            }
            $data['success'] = true;
        } catch (Exception $e){
            $table->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }
    }
}

/* End of file Param_dws_p_reg_files_controller.php */