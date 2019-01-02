<?php

class template
{

    var $vars;
    var $file;

    function template( $file = null )
    {
        $this->file = $file;
    }

    function set( $name, $value )
    {
        $this->vars[$name] = is_object( $value ) ? $value->fetch( ) : $value;
    }

    function fetch( $file = null )
    {
        global $INFO;
        global $lang;
        if ( !$file )
        {
            $file = $this->file;
        }
        extract( $this->vars );
        ob_start( );
        include( $INFO['templateFolder'].$file.$INFO['templateType'] );
        $contents = ob_get_contents( );
        ob_end_clean( );
        return $contents;
    }

}

?>
