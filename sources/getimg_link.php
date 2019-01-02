<?php
		$idp = $itech->input['idp'];
		// getlist images link
		$condit_link = "ID_PRODUCT = '".$idp."'";
		// get menu selected
		$idmnselected_linnk = '';
		$field_name_other_link = array('ID_IMG','NAME_IMG','ID_PRODUCT','ID_CATEGORY','NAME');
		$list_images_link = $print_2->list_row_column_multifield("images_link", $condit_link, "ID_IMG", "NAME_IMG", $field_name_other_link, " ORDER BY IORDER", "adm_images_link", 3, 0, 0, 0, '', $pre );
		echo $list_images_link;
		exit;
					
?>