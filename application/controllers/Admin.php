<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'language'));
    }

    function index() {
        check_login();
    }

    public function menuTree()
    {
        $data['group_id'] = $this->input->post('group_id');
        $this->load->view('administration/menu_tree', $data);
    }

    public function getMenuTreeJson()
    {
        $ci = & get_instance();
        $ci->load->model('administration/menus');
        $table = $ci->menus;
        $result = $table->getAllMenu();
        //print_r($result);

        $i = 0;
        $data = array();
        foreach ($result as $menu) {

            $tmp = array(
                'id' => $menu['menu_id'],
                'parentid' => $menu['menu_parent'],
                'text' => $menu['menu_name'],
                'value' => $menu['menu_id'],
                'expanded' => true

            );



            //Cek count di tabel menu profile untuk menu_id , jika >0 maka checked true
            $ci->load->model('administration/groups');
            $tmpCount = $ci->groups->getMenuGroup($menu['menu_id'], $this->uri->segment(3));

            $countMenu = count($tmpCount);

            if ($countMenu > 0) {
                $tmp = array_merge($tmp, array('checked' => true));
                $tmp = array_merge($tmp, array('app_menu_group_id' => $tmpCount['app_menu_group_id']));
            } else {
                $tmp = array_merge($tmp, array('app_menu_group_id' => ''));
            }

            $data[$i] = $tmp;
            $i = $i + 1;

        }
        echo json_encode($data);
    }

    public function updateProfile()
    {
        $ci = & get_instance();
        $ci->load->model('administration/groups');
        $ci->groups->insMenuProf();
        // $this->M_admin->insMenuProf();
        $data['group_id'] = $this->input->post('group_id');
        $this->load->view('administration/menu_tree', $data);
    }

    public function getMonitoring($id, $search, $tmp){
        $result = array();
        $this->db = $this->load->database('sit', TRUE);
        $sql = $this->db->query("SELECT * FROM TABLE(F_MONITOR_TIPRO(".$id.", '".$search."')) WHERE WF_MONITOR LIKE '".$tmp."%' ");
        if($sql->num_rows() > 0)
            $result = $sql->result();
        return $result;
    }

    public function processMonitoring(){

        $p_workflow_id = $this->input->post('p_workflow_id');
        $skeyword = $this->input->post('skeyword');

        $result = $this->getMonitoring($p_workflow_id, $skeyword,'H');
        foreach ($result as $rowH) {
            $exp = explode('|', $rowH->wf_monitor);
            if($exp[0] == 'H'){
                $data['header'] = $exp;
            }

        }

        $data['p_workflow_id'] = $p_workflow_id;
        $data['skeyword'] = $skeyword;

        $this->load->view('workflow/monitoring_grid',$data);

    }

     public function getMonProcess(){
        $page = intval($this->input->post('current')) ;
        $limit = $this->input->post('rowCount');
        $sort = $this->input->post('sort');
        $dir = $this->input->post('dir');

        $p_workflow_id = $this->input->post('p_workflow_id');
        $skeyword = $this->input->post('skeyword');

        $result = $this->getMonitoring($p_workflow_id, $skeyword,'D');

        $data = array();
        $hasil = array();
        $no = 1;
        foreach ($result as $row) {
            $exp = explode('|', $row->wf_monitor);
            if($exp[0] == 'D'){
                $tmp = array();

                for($i=0; $i<count($exp); $i++){
                    if($i==0){
                        $tmp = array("urutan" => $no);
                    }
                    $tmp = array_merge($tmp, array("data".$i => $exp[$i]));
                }

                if ($page == 0) {
                    $hasil['current'] = 1;
                } else {
                    $hasil['current'] = $page;
                }

                $jmlCount[] = $tmp;

                if($hasil['current'] == 1){
                    $start = $hasil['current'];
                    $end = $limit;
                }else{
                    $end = ($limit * $hasil['current']);
                    $start = $end - ($limit - 1);
                }
                // print_r($start);
                // exit;
                if(($tmp['urutan'] >= $start) && ($tmp['urutan'] <= $end)){
                    $data[] = $tmp;
                }

                $hasil['total'] = count($jmlCount);
                $hasil['rowCount'] = $limit;
                $hasil['success'] = true;
                $hasil['message'] = 'Berhasil';
                $hasil['rows'] = $data;

            }

            $no++;
        }

        if(count($hasil) == 0) {
            $hasil['current'] = 1;
            $hasil['total'] = 1;
            $hasil['rowCount'] = 1;
            $hasil['success'] = true;
            $hasil['message'] = 'Berhasil';
            $hasil['rows'] = array();
        }

        echo(json_encode($hasil));
        exit;
    }
}