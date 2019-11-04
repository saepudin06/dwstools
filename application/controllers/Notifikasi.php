<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifikasi extends CI_Controller
{

    function __construct() {

        parent::__construct();
        $this->load->helper(array('url', 'language'));
        $this->load->model('notif/Notif','notif');

    }

    function sendEmail($data){
    	
    	try{
    		
    		$this->notif->sendEmail();
    		$return = 'OK';

    	}catch(Exception $e){

    		$return = $e->getMessage();

    	}

    	echo $return;

    }

    function sendEmailSchema($schemaId){
    	
    	try{
    		$this->notif->sendEmailSchema($schemaId);
    		$return = 'OK';

    	}catch(Exception $e){

    		$return = $e->getMessage();

    	}

    	echo $return;

    }
    
    function sendEmailValidate($schemaId){
        
        try{
            $this->notif->sendEmailValidate($schemaId);
            $return = 'OK';

        }catch(Exception $e){

            $return = $e->getMessage();

        }

        echo $return;

    }

    function sendTelegram(){
    	try{
    		
    		$this->notif->sendEmail();
    		$return = 'OK';

    	}catch(Exception $e){

    		$return = $e->getMessage();
    		
    	}

    	echo $return;
    }

    function getNotif(){
    	try{
    		
    		$this->notif->sendEmail();
    		$return = 'OK';

    	}catch(Exception $e){

    		$return = $e->getMessage();
    		
    	}

    	echo $return;
    }

    function sendNotif(){
    	try{
    		
    		$this->notif->sendEmail();
    		$return = 'OK';

    	}catch(Exception $e){

    		$return = $e->getMessage();
    		
    	}

    	echo $return;
    }

    function populateNotif(){
    	try{
    		
    		$this->notif->sendEmail();
    		$return = 'OK';

    	}catch(Exception $e){

    		$return = $e->getMessage();
    		
    	}

    	echo $return;
    }

}