<?php
class db_driver {

    var $obj = array ( "sql_database"   => "" ,
                       "sql_user"       => "",
                       "sql_pass"       => "" ,
                       "sql_host"       => "" ,
                       "sql_port"       => "" ,
                       "sql_tbl_prefix" => "",
                       "cached_queries" => array(),
                       'debug'          => 0  ,
                     );

     var $query_id      = "";
     var $connection_id = "";
     var $query_count   = 0;
     var $record_row    = array();
     var $return_die    = 0;
     var $error         = "";
     var $failed        = 0;

    /*========================================================================*/
    // Connect to the database
    /*========================================================================*/

    function connect() {

               //$this->connection_id = mysql_connect( $this->obj['sql_host'] , $this->obj['sql_user'] , $this->obj['sql_pass'] );
				
				$this->connection_id = mysqli_connect($this->obj['sql_host'] ,
                                                              $this->obj['sql_user'] ,
                                                              $this->obj['sql_pass'], $this->obj['sql_database']);
				
               if (!$this->connection_id)
                  {
                      echo ("<center>Error: Cannot connect to database host: <b>".$this->obj['sql_host']);
                      exit();
                  }				
				  
				//mysql_set_charset('utf8',$this->connection_id);
				mysqli_set_charset($this->connection_id, "utf8");
				
				
			
            // if ( !mysql_select_db($this->obj['sql_database'], $this->connection_id) )
           //  {
           //      echo ("<center>Error: Cannot find database: <b>".$this->obj['sql_database']);
          //       exit();
           //  }
			
			  if ( !mysqli_select_db($this->connection_id, $this->obj['sql_database']) )
             {
                 echo ("<center>Error: Cannot find database: <b>".$this->obj['sql_database']);
                 exit();
             }
			 
    }



    /*========================================================================*/
    // Process a query
    /*========================================================================*/

    function query($the_query) {

        //$this->query_id = @mysql_query($the_query, $this->connection_id);
		$this->query_id = mysqli_query($this->connection_id, $the_query);
          if (! $this->query_id )
        {
            $this->fatal_error("mySQL error: $the_query");
        }
        return $this->query_id;
    }


    /*========================================================================*/
    // Fetch a row based on the last query
    /*========================================================================*/

    function fetch_row($query_id = "") {

         if ($query_id == "")
         {
              $query_id = $this->query_id;
         }

      // @ $this->record_row = mysql_fetch_array($query_id, MYSQL_ASSOC);	   
		$this->record_row = mysqli_fetch_array($query_id);

        return $this->record_row;

    }

     /*========================================================================*/
    // Fetch the number of rows affected by the last query
    /*========================================================================*/

    function get_affected_rows() {
        //return mysql_affected_rows($this->connection_id);
		return mysqli_affected_rows($this->connection_id);
    }

    /*========================================================================*/
    // Fetch the number of rows in a result set
    /*========================================================================*/

    function get_num_rows() {
        //return mysql_num_rows($this->query_id);
		return mysqli_num_rows($this->query_id);
    }


    /*========================================================================*/
    // Fetch the number of fields in a result set
    /*========================================================================*/

    function get_num_fields() {
       // return mysql_num_fields($this->query_id);
		return mysqli_num_fields($this->query_id);
    }
    
    
	/*========================================================================*/
    // Return the name of fields in a result set
    /*========================================================================*/

    function get_field_name() {
        //return mysql_field_name($this->query_id);
		return mysqli_fetch_field($this->query_id);
    }    
    
    /*========================================================================*/
    // Fetch the last insert id from an sql autoincrement
    /*========================================================================*/

    function get_insert_id() {
        //return mysql_insert_id($this->connection_id);
		return mysqli_insert_id($this->connection_id);
    }

    /*========================================================================*/
    // Return the amount of queries used
    /*========================================================================*/

    function get_query_cnt() {
        return $this->query_count;
    }

    /*========================================================================*/
    // Free the result set from mySQLs memory
    /*========================================================================*/

    function free_result($query_id="") {

             if ($query_id == "") {
              $query_id = $this->query_id;
         }

         //@mysql_free_result($query_id);
		 @mysqli_free_result($query_id);
    }

    /*========================================================================*/
    // Shut down the database
    /*========================================================================*/

    function close_db() {
        //return mysql_close($this->connection_id);
		return mysqli_close($this->connection_id);
    }

    /*========================================================================*/
    // Return an array of tables
    /*========================================================================*/

    function get_table_names() {

          //$result     = mysql_list_tables($this->obj['sql_database']);
		  $result     = mysqli_list_tables($this->obj['sql_database']);
          //$num_tables = @mysql_numrows($result);
		  $num_tables = @mysqli_numrows($result);
          for ($i = 0; $i < $num_tables; $i++)
          {
               $tables[] = mysql_tablename($result, $i);
          }

          //mysql_free_result($result);
		  mysqli_free_result($result);

          return $tables;
        }

        /*========================================================================*/
    // Return an array of fields
    /*========================================================================*/

    function get_result_fields($query_id="") {

             if ($query_id == "")
             {
              $query_id = $this->query_id;
         }

         // while ($field = mysql_fetch_field($query_id))
		  while ($field = mysqli_fetch_field($query_id))
          {
            $Fields[] = $field;
          }

          //mysql_free_result($query_id);

          return $Fields;
        }

    /*========================================================================*/
    // Basic error handler
    /*========================================================================*/

    function fatal_error($the_error) {
         global $INFO;
		 $out='';
         // Are we simply returning the error?

         if ($this->return_die == 1)
         {
              $this->error    = mysqli_error($this->connection_id);
              $this->error_no = mysqli_errno($this->connection_id);
              $this->failed   = 1;
              return;
         }

         $the_error .= "\n\nmySQL error: ".mysqli_error($this->connection_id)."\n";
         $the_error .= "mySQL error code: ".$this->error_no."\n";
         $the_error .= "Date: ".date("l dS of F Y h:i:s A");

      /*   $out = "<html><head><title>Sorry error found...</title>
                 <style>P,BODY{ font-family:arial,sans-serif; font-size:11px; }</style></head><body>
                 &nbsp;<br><br><blockquote><b>There is an error loading the page. We appreciate your inform of this problem and please accept our apology for this inconvenience</b><br>
                 <a href=\"javascript:window.location=window.location;\">Click</a> here to try again. If you still cannot access the page, please inform our <a href='mailto:{$INFO['email_webmaster']}?subject=SQL+Error'>webmaster</a>
                 <br><br><b>Error Details:</b><br>
                 <form name='mysql'><textarea rows=\"3\" cols=\"60\">".htmlspecialchars($the_error)."</textarea></form><br>We apologize in advance for any inconvenience.</blockquote></body></html>";
         */
		   $out="<html><head><title>Sorry error found...</title>
                 <style>P,BODY{ font-family:arial,sans-serif; font-size:11px; }</style></head><body>
                 <span style=\"color:#FF0000\"; style=\"height:10\" > ".htmlspecialchars($the_error)." </span>
                 </body></html>";
		   
	   //add error to table
	   $error_text = str_replace("'","",$the_error);
	   $sql_error = "INSERT INTO error_tracker(name, times) VALUES ('".$error_text."', now())";
	   //$result = mysql_query($sql_error, $this->connection_id);
	  // $result = mysqli_query($this->connection_id, $sql_error);
	   

	  // $out_header="<html><head><title>Sorry error found...$error_text</title></body></html>";
	   
		//@ mail("huy.hoang@nhanviet.com","Error on website",$the_error);
		//@ mail("than.le@vipdatabase.com","Error on website",$the_error);
		
		//echo($out_header);
        //echo($out);
        //die("");
        //return $out;
    }

    function compile_db_insert_string($data) {

         $field_names  = "";
          $field_values = "";

          foreach ($data as $k => $v)
          {
               $v = preg_replace( "/'/", "\\'", $v );
               //$v = preg_replace( "/#/", "\\#", $v );
               $field_names  .= "$k,";
               $field_values .= "'$v',";
          }

          $field_names  = preg_replace( "/,$/" , "" , $field_names  );
          $field_values = preg_replace( "/,$/" , "" , $field_values );

          return array( 'FIELD_NAMES'  => $field_names,
                           'FIELD_VALUES' => $field_values,
                         );
     }

     /*========================================================================*/
    // Create an array from a multidimensional array returning a formatted
    // string ready to use in an UPDATE query, saves having to manually format
    // the FIELD='val', FIELD='val', FIELD='val'
    /*========================================================================*/

    function compile_db_update_string($data) {

          $return_string = "";

          foreach ($data as $k => $v)
          {
               $v = preg_replace( "/'/", "\\'", $v );
               $return_string .= $k . "='".$v."',";
          }

          $return_string = preg_replace( "/,$/" , "" , $return_string );

          return $return_string;
     }

     /*========================================================================*/
    // Test to see if a field exists by forcing and trapping an error.
    // It ain't pretty, but it do the job don't it, eh?
    // Posh my ass.
    // Return 1 for exists, 0 for not exists and jello for the naked guy
    // Fun fact: The number of times I spelt 'field' as 'feild'in this part: 104
    /*========================================================================*/

    function field_exists($field, $table) {

          $this->return_die = 1;
          $this->error = "";

          $this->query("SELECT COUNT($field) as count FROM $table");

          $return = 1;

          if ( $this->failed )
          {
               $return = 0;
          }

          $this->error = "";
          $this->return_die = 0;
          $this->error_no   = 0;
          $this->failed     = 0;

          return $return;
     }

} // end class

//this class handle paging...
class Pager
  {
  /***********************************************************************************
   * int findStart (int limit)
   * Returns the start offset based on $itech->input['page'] and $limit
   ***********************************************************************************/
   var $extLink = "";

   function findStart($limit){
      global $itech;
      if ((!isset($itech->input['page'])) || ($itech->input['page'] == "1"))
      {
       $start = 0;
       $itech->input['page'] = 1;
      }
     else
      {
       $start = ($itech->input['page']-1) * $limit;
      }

     return $start;
    }
  /***********************************************************************************
   * int findPages (int count, int limit)
   * Returns the number of pages needed based on a count and a limit
   ***********************************************************************************/
   function findPages($count, $limit)
    {
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;

     return $pages;
    }
  /***********************************************************************************
   * string pageList (int curpage, int pages)
   * Returns a list of pages in the format of "« < [pages] > »"
   ***********************************************************************************/
   function pageList($curpage, $pages)
    {
     $max_pages_to_print = 5;
     $loopcounter = 0; // counter for whileloop
     $middlenumber = 2; // Number will be decreased from variable currentpage in order to get the currentpage always in the middle
     $i = 1; // variable that will print 1,2,3,etc..
     $x = 0; // variable i use to put always the current, marked page in the middle
     $page_list  = "";

     /* Print the first and previous page links if necessary */
     if ($curpage >= $max_pages_to_print)
      {
       $page_list .= "  <a href=\"".$_SERVER['PHP_SELF']."?".$this->extLink."&page=1\" title=\"Trang đầu tiên\"><img src=\"images/truoc.gif\" border=\"0\"></a> ";
      }
      if (($curpage-1) > 0)
      {
       $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$this->extLink."&page=".($curpage-1)."\" title=\"Trang trước\">Trang trước</a> | ";
      }
       //BEGIN LOOP
       while($loopcounter < $max_pages_to_print) {
          if($curpage >= $max_pages_to_print) { // If user clicks om page higher than $max_pages_to_print
               // Mark current page
               if($curpage == $i) {
                    $page_list .= "&nbsp;<b>[ ".$i." ]</b>&nbsp;";
                    $i += 1; //increase page
                    $loopcounter += 1; // increase loopcounter
               }
               // End marking
               if($i > $pages) { // if last page has been printed, exit loop
                    break;
               }
               if($x == 0) {
                    $i = $curpage - $middlenumber; // current page will always be printed in the middle
               }
               $page_list .= "&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?".$this->extLink."&page=".$i."\" title=\"Trang ".$i."\">".$i."</a>&nbsp;";// print pagenumbers
               $x = $x + 1;
               $i += 1; //increase page
               $loopcounter += 1; // increase loopcounter
          }
          else { // Else user clicks on a pagenumber lower $max_pages_to_print
               // Mark current page
               if($curpage == $i) {
                    $page_list .= "&nbsp;<b>[ ".$i." ]</b>&nbsp;"; // print pagenumbers
                    $i += 1; //increase page
                    $loopcounter += 1; // increase loopcounter
               }
               // End marking
               if($i > $pages) { // if less than $max_mages_to_print, exit loop
                    break;
               }
               $page_list .= "&nbsp;<a href=\"".$_SERVER['PHP_SELF']."?".$this->extLink."&page=".$i."\" title=\"Trang ".$i."\">".$i."</a>&nbsp;";// print pagenumbers
               $i += 1; //increase page
               $loopcounter += 1; // increase loopcounter
          } // End if
     }//end while loop
          if (($curpage+1) <= $pages)
           {
                 $page_list .= " | <a href=\"".$_SERVER['PHP_SELF']."?".$this->extLink."&page=".($curpage+1)."\" title=\"Trang tiếp theo\">Trang sau</a> ";
           }
          if(($pages > $max_pages_to_print) AND ($i <= $pages)) {
                    $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?".$this->extLink."&page=".$pages."\" title=\"Trang cuối\"><img src=\"images\sau.gif\" border=\"0\"></a> ";
               }
     return $page_list;
    }
  }

?>