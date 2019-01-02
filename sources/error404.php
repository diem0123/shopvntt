<?php

	//main
	$main = & new Template('404page');      	
	$main->set('main1', ""); 

	echo $main->fetch('404page');
?>