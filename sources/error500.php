<?php

	//main
	$main = & new Template('500page');      	
	$main->set('mailto', $itech->vars['email_webmaster']); 

	echo $main->fetch('500page');
?>