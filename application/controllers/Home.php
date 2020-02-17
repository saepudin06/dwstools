<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct() {
        parent::__construct();
    }

    function index() {
        check_login();
        $this->load->view('home/index');
    }

    function load_content($id) {
        try {
            $file_exist = true;
            check_login();
            $id = str_replace('.php','',$id);
            $file = explode(".", $id);
            $url_file = "";
            if(count($file) > 1) {
                $lastindex = count($file)-1;
                if(strtolower(substr($file[$lastindex],-4)) != ".php")
                    $file[$lastindex] .= ".php";
                if(file_exists(APPPATH."views/".implode("/", $file))) {
                    $this->load->view(implode("/", $file));
                }else {
                    $file_exist = false;
                }

                $url_file = APPPATH."views/".implode("/", $file);
            }else {
                if(strtolower(substr($id,-4)) != ".php")
                    $id .= ".php";

                if(file_exists(APPPATH."views/".$id)) {
                    $this->load->view($id);
                }else {
                    $file_exist = false;
                }

                $url_file = APPPATH."views/".$id;
            }

            if(!$file_exist) {
                $this->load->view("error_404.php");
            }

        }catch(Exception $e) {
            echo "
                <script>
                    Swal.fire({title: 'Session Timeout', text: '', html: '".$e->getMessage()."', type: 'error'});
                </script>
            ";
            exit;
        }
    }

}