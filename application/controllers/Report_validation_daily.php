<?php defined('BASEPATH') OR exit('No direct script access allowed');
require('fpdf/fpdf.php');
require('fpdf/exfpdf.php');
require('fpdf/easyTable.php');
require('fpdf/pdf.php');


class Report_validation_daily extends CI_Controller{
	var $fontSize = 10;
    var $fontFam = 'Arial';
    var $yearId = 0;
    var $yearCode="";
    var $paperWSize = 410;
    var $paperHSize = 255;
    var $height = 5;
    var $currX;
    var $currY;
    var $widths;
    var $aligns;
    var $lengthCell;
    // Rect(float x, float y, float w, float h [, string style]);

    function __construct() {
        parent::__construct();
        //$this->formCetak();
        $pdf = new FPDF();
        $this->startY = $pdf->GetY();
        $this->startX = $this->paperWSize-12;
        $this->lengthCell = $this->paperHSize+10;
    }

    public function newLine($pdf, $height){
        $pdf->Cell($pdf->GetPageWidth(), $height, "", "", 0, 'L');
        $pdf->Ln();
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

    function dev($data, $is_print = true){
        echo "<pre>";
        if ($is_print){
            print_r($data);
        } else {
            var_dump($data);
        }
        echo "</pre>";
        exit;
    }

    function get_spell_month_ind($month){
        return spellMonthInd($month);
    }

    function get_data($poti, $period){
        $ci = & get_instance();
        $ci->load->model('ws_ic/report_daily_validation');
        $table = $ci->report_daily_validation;

        $res = $table->db->where("poti = '$poti' and period = '$period'")->get($table->fromClause)->result_array();
        return $res;
    }

    function get_data_ttd($period){
        $ci = & get_instance();
        $ci->load->model('ws_ic/report_daily_validation');
        $table = $ci->report_daily_validation;
        $res = array();
        $i = 0;

        $sql = "select *
            from ic_dws.f_vw_dws_signature_active_ic('REVIEWER' , '$period' )";

        $temp_res = $table->db->query($sql)->row_array();

        if (!empty($temp_res)){
            $res[$i] = $temp_res;
            $i++;
        }

        $sql = "select *
            from ic_dws.f_vw_dws_signature_active_ic('VERIFICATOR' , '$period' )";

        $temp_res = $table->db->query($sql)->row_array();

        if (!empty($temp_res)){
            $res[$i] = $temp_res;
        }

        return $res;
    }

    function pageCetak(){
		$data_ret = array('success' => false, 'message' => '');
        $param = getVarClean('data','str','');
        $data_param = $this->get_param($param);

        try {
            $data = $this->get_data($data_param['poti'], $data_param['period']);
            $data_ttd = $this->get_data_ttd($data_param['period']);
            $bulan = (int)substr($data_param['period'], 4, 2);
            $bulan = $bulan < 9 ? "0".$bulan : "".$bulan;
            $spell_Month = $this->get_spell_month_ind($bulan);
            $tahun = substr($data_param['period'], 0, 4);
            $pdf = new pdf();
            $width = $pdf->GetPageWidth() - 20; 
            $height = 5;

            $pdf->AliasNbPages();
            $pdf->AddPage("P", 'A4');
            $pdf->SetFont("Arial", "", 8);
            $style = "font-family:Arial;font-size:8;valign:M;";

            $list_length = "{";
            $list_length .= ($width * 7/100); //period
            $list_length .= ",". ($width * 10/100); //poti
            $list_length .= ",". ($width * 8/100); //tanggal
            $list_length .= ",". ($width * 8/100); //day
            $list_length .= ",". ($width * 6/100); //jam
            $list_length .= ",". ($width * 9/100); //duration
            $list_length .= ",". ($width * 15/100); //avg
            $list_length .= ",". ($width * 15/100); //dev
            $list_length .= ",". ($width * 14/100); //dev(%)
            $list_length .= ",". ($width * 10/100); //hasil validasi

            $list_length .= "}";

            $table = new easyTable($pdf, $list_length, 'border:1;width:100%;');
            $table->rowStyle($style."bgcolor:#E1E1E1;");
            $table->easyCell("Period", "align:L;");
            $table->easyCell("POTI", "align:L;");
            $table->easyCell("Date", "align:L;");
            $table->easyCell("Day", "align:L;");
            $table->easyCell("Clock", "align:L;");
            $table->easyCell("Duration", "align:L;");
            $table->easyCell("AVG", "align:L;");
            $table->easyCell("DEV", "align:L;");
            $table->easyCell("DEV(%)", "align:L;");
            $table->easyCell("Validation Result", "align:L;");
            $table->printRow();
            foreach ($data as $val) {
                $table->rowStyle($style);
                $table->easyCell($val['period'], "align:C");
                $table->easyCell($val['poti'], "align:C");
                $table->easyCell($val['tanggal'], "align:L");
                $table->easyCell($val['day_category'], "align:C");
                $table->easyCell($val['jam'], "align:C");
                $table->easyCell($val['duration'], "align:R");
                $table->easyCell(number_format($val['avg_duration'],2,",","."), "align:R");
                $table->easyCell(number_format($val['dev_dur'],2,",","."), "align:R");
                $table->easyCell(number_format($val['dev_prctg'],2,",",".").'%', "align:R");
                $table->easyCell($val['validation_rslt'], "align:C");
                $table->printRow();
            }
            $table->endTable();

            $this->newLine($pdf, 5);
            if ($pdf->GetY() > 220){
                $pdf->AliasNbPages();
                $pdf->AddPage("P", 'A4');
            }
            $pdf->Cell($width, $height-2, "Jakarta, 3 " . $spell_Month . " " . $tahun, '', 1, 'C');
            $this->newLine($pdf, 5);

            $count_ttd = count($data_ttd);
            $td_width = '0%';
            $temp_width = $width;
            if ($count_ttd == 1){
                $td_width = (1/4)*$temp_width;
            } else if ($count_ttd == 2) {
                $td_width = (1/2)*$temp_width;
            }

            for ($j = 0; $j < $count_ttd; $j++) { 
                $pdf->Cell($td_width, $height, $data_ttd[$j]['position_name'], '', 0, 'C');
            }
            $this->newLine($pdf, $height);
            $var_y = $pdf->GetY()+1;
            for ($j = 0; $j < $count_ttd; $j++) { 
                $var_x = $pdf->GetX();

                if ($count_ttd == 2){
                    $var_x = $var_x + $td_width/3;
                } else {
                    $var_x = $var_x + $td_width/3-4;
                }

                $msg = "";
                if (empty($data_ttd[$j]['signature_img'])){
                    $pdf->Cell($td_width, $height, "Signature Image is empty", '', 0, 'C');
                } else if (!file_exists($data_ttd[$j]['signature_img'])){
                    $pdf->Cell($td_width, $height, "Image is not exists: " . $data_ttd[$j]['signature_img'], '', 0, 'C');
                } else {
                    $pdf->Cell($td_width, $height, 
                        $pdf->image($data_ttd[$j]['signature_img'], $var_x, $var_y, 30, 25), 
                    '', 0, 'C');
                }
                
            }

            $this->newLine($pdf, 30);
            $pdf->SetFont("Arial", "U", 8);
            for ($j = 0; $j < $count_ttd; $j++) { 
                $pdf->Cell($td_width, $height, $data_ttd[$j]['user_full_name'], '', 0, 'C');
            }

            $pdf->SetFont('Arial','', 8);
            $this->newLine($pdf, $height-2);
            for ($j = 0; $j < $count_ttd; $j++) { 
                $pdf->Cell($td_width, $height, "NIK " . $data_ttd[$j]['user_name'], '', 0, 'C');
            }

            $pdf->Output('D', 'REPORT_'.$data_param['poti'].'_'.$data_param['period'].'.pdf');
        } catch (Exception $e){
            echo "----ERROR----";
            $this->dev($e->getMessage);
        }

		
	}
}