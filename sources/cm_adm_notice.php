<?php
	
	 $note=$itech->input['notice'];
     //main
     $main = & new Template('cm_adm_notice');
	 $main->set('note',$note);
	
     //all
     $tpl = & new Template();
     $tpl->set('main', $main);

     echo $tpl->fetch('cm_homeprint');
?>
