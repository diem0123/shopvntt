<?php

	//main
	$main = & new Template('403page');      	
	$main->set('main1', ""); 

	echo $main->fetch('403page');
?>