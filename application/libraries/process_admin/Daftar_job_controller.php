<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Daftar_job_controller
* @version 29-11-2017 02:10:48
*/
class Daftar_job_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','');
        $sord = getVarClean('sord','str','desc');
        $module_id = getVarClean('module_id', 'int', 0);
        $p_job_type_id = getVarClean('p_job_type_id', 'int', 0);
        $parent_id = getVarClean('parent_id', 'str', '0');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('process_admin/daftar_job_model');
            $table = $ci->daftar_job_model;

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
            $whereClause = $parent_id == '0' ? " and parent_id is null" : " and parent_id = " . $parent_id;
            $req_param['where'] = array("module_id = " . $module_id . " and p_job_type_id = " . $p_job_type_id . $whereClause);

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
              //  permission_check('can-add-referencetype');
                $data = $this->create();
            break;

            case 'edit' :
               // permission_check('can-edit-referencetype');
                $data = $this->update();
            break;

            case 'del' :
              //  permission_check('can-del-referencetype');
                $data = $this->destroy();
            break;

            default :
               // permission_check('can-view-referencetype');
                $data = $this->read();
            break;
        }

        return $data;
    }


    function create() {

        $ci = & get_instance();
        $ci->load->model('process_admin/daftar_job_model');
        $table = $ci->daftar_job_model;

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
        $ci->load->model('process_admin/daftar_job_model');
        $table = $ci->daftar_job_model;

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
        $ci->load->model('process_admin/daftar_job_model');
        $table = $ci->daftar_job_model;

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

    public function tree_job() {

        $ci = & get_instance();
        $ci->load->model('process_admin/daftar_job_model');
        $table = $ci->daftar_job_model;

        $p_job_type_id = getVarClean('p_job_type_id', 'int', 0);

        $sql = "select * from p_job_type where p_job_type_id = " . $p_job_type_id;
        $res = $table->db->query($sql)->row_array();

        $sql = "
            with recursive subjob as (
               select p_job_id, nvl (parent_id, 0) parent_id, code, procedure_name, description
               from p_job
               where p_job_type_id = " . $p_job_type_id . "
               union
                  select e.p_job_id, e.parent_id, e.code, e.procedure_name, e.description
                  from p_job e
                  inner join subjob s on s.p_job_id = e.parent_id
            ) 
            select *
            from subjob
            order by parent_id asc nulls first, p_job_id;";

        $items = $table->db->query($sql)->result_array();
        $data = array();
        $data[] = array('id' => 0,
                  'parentid' => -1,
                  'text' => $res['code'],
                  'expanded' => true,
                  'selected' => true,
                  'icon' => base_url('images/home.png'));

        foreach($items as $item) {

            if( $this->empty_children($items, $item['p_job_id']) ) {
                $data[] = array(
                            'id' => $item['p_job_id'],
                            'parentid' => empty($item['parent_id']) ? 0 : $item['parent_id'],
                            'text' => $item['code'],
                            'expanded' => false,
                            'selected' => false,
                            'icon' => base_url('images/file-icon.png')
                          );
            } else {
                $data[] = array(
                            'id' => $item['p_job_id'],
                            'parentid' => empty($item['parent_id']) ? 0 : $item['parent_id'],
                            'text' => $item['code'],
                            'expanded' => false,
                            'selected' => false,
                            'icon' => base_url('images/folder-close.png')
                          );
            }
        }

        echo json_encode($data);
        exit;
    }

    function empty_children($items, $p_job_id){
      $items_child = [];

      foreach ($items as $val) {
        if ($val['parent_id'] == $p_job_id){
          array_push($items_child, $val);
        }
      }

      return empty($items_child);
    }
}

/* End of file Daftar_job_controller.php */