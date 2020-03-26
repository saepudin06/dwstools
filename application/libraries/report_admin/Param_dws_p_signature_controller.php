<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Param_dws_p_signature_controller
* @version 29-11-2017 02:11:12
*/
class Param_dws_p_signature_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','p_signature_id');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('report_admin/param_dws_p_signature');
            $table = $ci->param_dws_p_signature;

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
            $req_param['where'] = array("");

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
  	
        $ci->load->model('report_admin/param_dws_p_signature');
        $table = $ci->param_dws_p_signature;
        
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
                $data['id'] = ((int)$table->generate_id($table->table, $table->pkey))-1;

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
        $ci->load->model('report_admin/param_dws_p_signature');
        $table = $ci->param_dws_p_signature;
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
                $data['id'] = $items[$table->pkey];
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
        $ci->load->model('report_admin/param_dws_p_signature');
        $table = $ci->param_dws_p_signature;

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
        $ci->load->model('report_admin/param_dws_p_signature');
        $table = $ci->param_dws_p_signature;
        $userinfo = $ci->session->userdata;

        $data = array("success" => false, "message" => "");

        try {
            $is_upload = $_FILES['uploadParamFile']['size'] != 0;

            if ($is_upload){
                $p_signature_id = getVarClean('p_signature_id', 'str', '');
                $directory = "";
                $file_name = "";

                $path = $_FILES['uploadParamFile']['name']; //blablabla.xls
                $ext  = pathinfo($path, PATHINFO_EXTENSION);
                
                $config['upload_path']   = './uploads/signature_img/';

                // membuat direktori
                if( is_dir($config['upload_path']) === false ){
                    if (!mkdir($config['upload_path'], 0777, true)){
                        $file_name = "Gagal Membuat direktori: " . $config['upload_path'];
                    }
                }

                $config['allowed_types'] = 'jpg|png';
                $config['max_size']      = '90000000';
                $config['overwrite']     = TRUE;
                $config['file_name']     = "signature_" . $p_signature_id . '_' . date('Ymdhis') . '.' . $ext;
                
                $ci->load->library('upload');
                $ci->upload->initialize($config);

                if (!$ci->upload->do_upload("uploadParamFile")) {
                    $file_name .= $ci->upload->display_errors();
                } else {
                    $filedata = $ci->upload->data();
                    $path = $config['upload_path'];
                    $filename = $filedata['file_name'];
                    $dir_file_name = $config['upload_path'].$filedata['file_name'];

                    $input_file = $filedata['full_path'];
                    $img_type = $filedata['image_type'];
                    $output_file = $filedata['file_path'].$filedata['raw_name'].'.png';

                    if ($img_type == 'jpeg' || $img_type == 'jpg'){
                        $input = @imagecreatefromjpeg($input_file);
                    } else if ($img_type == 'png'){
                        $input = @imagecreatefrompng($input_file);
                    }
                    list($width_img, $height_img) = getimagesize($input_file);
                    $output = imagecreatetruecolor($width_img, $height_img);
                    $white = imagecolorallocate($output,  255, 255, 255);
                    $black = imagecolorallocate($output, 0, 0, 0);
                    imagecolortransparent($output, $black);
                    imagecopy($output, $input, 0, 0, 0, 0, $width_img, $height_img);
                    imagepng($output, $output_file);

                    $file_name = $filedata['raw_name'].'.png';
                }

                $table->db->trans_begin();
                $table->db->set('signature_img', $path.$file_name);
                $table->db->where('p_signature_id', $p_signature_id)->update($table->table);
                $table->db->trans_commit();

                $data['file_name'] = $file_name;
            }
            $data['success'] = true;
        } catch (Exception $e){
            $table->db->trans_rollback();
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
        exit;
    }

    function read_lov_user() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','user_id');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $i_search = getVarClean('i_search', 'str', '');

        try {

            $ci = & get_instance();
            $ci->load->model('report_admin/param_dws_p_signature');
            $table = $ci->param_dws_p_signature;
            $table->fromClause = "users";

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
            $req_param['where'] = array();

            if(!empty($i_search)) {
                $table->setCriteria("upper(user_name) like upper('%".$i_search."%') or upper(user_full_name) like upper('%".$i_search."%')");
            }

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

    function read_lov_role_signature() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $i_search = getVarClean('i_search', 'str', '');

        try {

            $ci = & get_instance();
            $ci->load->model('report_admin/param_dws_p_signature');
            $table = $ci->param_dws_p_signature;
            $table->fromClause = "vw_role_signature_ref";

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
            $req_param['where'] = array();

            if(!empty($i_search)) {
                $table->setCriteria("upper(code) like upper('%".$i_search."%') or upper(code_type_ref) like upper('%".$i_search."%')");
            }

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
}

/* End of file Param_dws_p_signature_controller.php */