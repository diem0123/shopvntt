<?php

class useraut_2
{

    

    function check_extent_file( $file_name, $extent_file )
    {
        $extent_file = $extent_file;
        if ( !preg_match( "/\\.(".$extent_file.")$/", $file_name ) )
        {
            return false;
        }
        return true;
    }

    function copy_and_change_filename( $file_input_tmp, $file_input_name, $dir_upload, $prefix )
    {
        $source = $file_input_tmp;
        $part = explode( ".", $file_input_name );
        $file_name = $prefix.".".$part[1];
        $dest = $dir_upload.$file_name;
        copy( $source, $dest );
        return $file_name;
    }

    function delete_list_images( $list_image, $dirimage )
    {
        $picture = explode( ";", $list_image );
        $numpic = count( $picture );
        if ( 0 < $numpic )
        {
            
            for ($i = 0; $i < $numpic; ++$i )
            {
                if ( $picture[$i] != "" )
                {
                    unlink( $dirimage.$picture[$i] );
                }
            }
        }
    }    

    function delete_common( $condition, $table, $dirimage )
    {
        global $DB;
        $sql = "SELECT * FROM ".$table." WHERE ".$condition;
        $query = $DB->query( $sql );
        if ( $result = $DB->fetch_row( $query ) )
        {
            $picture = explode( ";", $result['sPicture'] );
            $numpic = count( $picture );
            if ( 0 < $numpic )
            {
               
                for (  $i = 0; $i < $numpic; ++$i )
                {
                    if ( $picture[$i] != "" )
                    {
                        unlink( $dirimage.$picture[$i] );
                    }
                }
            }
            $sql1 = "DELETE FROM ".$table." WHERE ".$condition;
            $DB->query( $sql1 );
        }
    }

}

?>
