<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Report_daily_validation_controller
* @version 29-11-2017 02:11:12
*/
class Report_daily_validation_controller {

    function read() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('ws_ic/report_daily_validation');
            $table = $ci->report_daily_validation;

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

            $poti = getVarClean('poti', 'str', '');
            $period = getVarClean('period', 'str', '');

            // Filter Table
            $req_param['where'] = array("poti = '$poti' and period = '$period'");

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
            default :
                $data = $this->read();
            break;
        }

        return $data;
    }

    function read_lov_poti() {

        $page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','');
        $sord = getVarClean('sord','str','desc');

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $i_search = getVarClean('i_search', 'str', '');

        try {

            $ci = & get_instance();
            $ci->load->model('ws_ic/report_daily_validation');
            $table = $ci->report_daily_validation;
            $table->fromClause = "ic_dws.p_trunk";

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
                $table->setCriteria("upper(code) like upper('%".$i_search."%')");
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

    // mengambil parameter data yg ter enkripsi untuk di dekripsi
    public function get_param(){
        $data = trim(getVarClean('data','str',''));
        // $this->dev($data);
        $res = array();

        if (!empty($data)){
            $data = base64_decode($data);
            $arr_data = explode(":;", $data);

            foreach ($arr_data as $val) {
                $temp = explode("=",$val);
                $res[$temp[0]] = $temp[1];
            }
        } else {
            $res = "Tidak ada parameter yg ter enkripsi";
        }

        return $res;
    }

    function save_to_excel() {
        $ci = & get_instance();
        $ci->load->model('ws_ic/report_daily_validation');
        $table = $ci->report_daily_validation;

        $data_param = getVarClean('data', 'str', '');
        $param = $this->get_param($data_param);
        $bulan = (int)substr($param['period'], 4, 2);
        $bulan = $bulan < 9 ? "0".$bulan : "".$bulan;
        $spell_Month = spellMonthInd($bulan);
        $tahun = substr($param['period'], 0, 4);

        $res_data = $table->db->where("poti = '".$param['poti']."' and period = '".$param['period']."'")->get($table->fromClause)->result_array();
        $res_ttd = array();
        $i = 0;

        $sql = "select *
            from ic_dws.f_vw_dws_signature_active_ic('REVIEWER' , '$period' )";

        $temp_res = $table->db->query($sql)->row_array();

        if (!empty($temp_res)){
            $res_ttd[$i] = $temp_res;
            $i++;
        }

        $sql = "select *
            from ic_dws.f_vw_dws_signature_active_ic('VERIFICATOR' , '$period' )";

        $temp_res = $table->db->query($sql)->row_array();

        if (!empty($temp_res)){
            $res_ttd[$i] = $temp_res;
        }


        $fileName = 'REPORT_'.$param['poti'].'_'.$param['period'];
        header("Content-type: application/x-msexcel");
        header("Content-Disposition: attachment; filename=".$fileName.".xls"); 
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<body style=mso-number-format:'\@'>";
        echo "
            <table border=1>
                <tr>
                    <th>Period</th>
                    <th>POTI</th>
                    <th>Tanggal</th>
                    <th>Day</th>
                    <th>Jam</th>
                    <th>Duration</th>
                    <th>AVG</th>
                    <th>DEV</th>
                    <th>DEV(%)</th>
                    <th>Hasil Validasi</th>
                </tr>";

        foreach ($res_data as $val) {
            echo "<tr>";
            echo "<td align='center'>".$val['period']."</td>";
            echo "<td align='center'>".$val['poti']."</td>";
            echo "<td>".$val['tanggal']."</td>";
            echo "<td align='center'>".$val['day_category']."</td>";
            echo "<td align='center'>".$val['jam']."</td>";
            echo "<td align='right'>".$val['duration']."</td>";
            echo "<td align='right'>".number_format($val['avg_duration'],2,",",".")."</td>";
            echo "<td align='right'>".number_format($val['dev_dur'],2,",",".")."</td>";
            echo "<td align='right'>".number_format($val['dev_prctg'],2,",",".").'%'."</td>";
            echo "<td align='center'>".$val['validation_rslt']."</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "<br/><br/>";
        $count_ttd = count($res_ttd);
        $colspan = $count_ttd > 1 ? 5 : 10;

        echo "<table>
            <tr>
                <td colspan='10' align='center'>Jakarta, 3 " . $spell_Month . " " . $tahun."<br/></td>
            </tr>";

        echo "<tr>";
        for ($j = 0; $j < $count_ttd; $j++) { 
            echo "<td align='center' colspan='$colspan'>" . $res_ttd[$j]['position_name'] . "</td>";
        }
        echo "</tr>";

        echo "<tr>";
        for ($j = 0; $j < $count_ttd; $j++) { 
            $msg = "";
            if (empty($res_ttd[$j]['signature_img'])){
                $msg = "Signature Image is empty";
            } else if (!file_exists($res_ttd[$j]['signature_img'])){
                $msg = "Image is not exists: " . $res_ttd[$j]['signature_img'];
            } else {
                $msg = "<img src='".base_Url().$res_ttd[$j]['signature_img']."' style='width:120px !important;height:115px !important;'>";
            }
            if ($colspan == 10){
                echo "<td colspan='4'></td>";
                echo "<td colspan='2' style='width:120px !important;height:120px !important; text-align:center'>" . $msg . "</td>";
                echo "<td colspan='4'></td>";
            } else {
                echo "<td colspan='2'></td>";
                echo "<td style='width:120px !important;height:120px !important; text-align:center'>" . $msg . "</td>";
                echo "<td colspan='2'></td>";
            }
        }
        echo "</tr>";

        echo "<tr>";
        for ($j = 0; $j < $count_ttd; $j++) { 
            echo "<td colspan='$colspan' align='center'><u>" . $res_ttd[$j]['user_full_name'] . "</u></td>";
        }
        echo "</tr>";

        echo "<tr>";
        for ($j = 0; $j < $count_ttd; $j++) { 
            echo "<td colspan='$colspan' align='center'>" . "NIK " . $res_ttd[$j]['user_name'] . "</td>";
        }
        echo "</tr>";

        echo   "</table>";

        echo "</body>";
        exit();
    }
}

/* End of file Report_daily_validation_controller.php */