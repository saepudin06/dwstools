<?php 
class pdf extends exFPDF {
	public function get_color($c){
      if($c=='fill'){
         return $this->FillColor;
      }
      elseif($c=='text'){
         return $this->TextColor;
      }
   }
}
 ?>