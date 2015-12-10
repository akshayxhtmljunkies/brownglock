<?php
define( 'DATABASE_HOST',  '' );
define( 'DATABASE_USER',  '' );
define( 'DATABASE_PASS',  '' );
define( 'DATABASE_NAME', '' );
define( 'DATABASE_TABLE', '' );

if( DATABASE_HOST !== '' && DATABASE_USER !== '' && DATABASE_NAME !== '' && DATABASE_TABLE !== '' )
{
	try
	{
		$db_link = mysqli_connect( DATABASE_HOST, DATABASE_USER, DATABASE_PASS, DATABASE_NAME );
		if( $db_link !== false )
		{
			$field1 = mysqli_escape_string( $db_link, $params[ 'fieldname%' ] );
			$field2 = mysqli_escape_string( $db_link, $params[ 'fieldname%' ] );
			$field3 = mysqli_escape_string( $db_link, $params[ 'fieldname%' ] );
			
			mysqli_query( $db_link, "INSERT INTO `".DATABASE_TABLE."` (field1, field2, field3) VALUES ('$field1', '$field2', '$field3');" );
			mysqli_close($db_link);
		}
	}
	catch( Exception $e )
	{
	}	
}

?>