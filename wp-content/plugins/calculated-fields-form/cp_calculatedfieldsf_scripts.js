function cp_calculatedfieldsf_insertForm( result ) {
	if( typeof result != 'undefined' && result )
	{
		send_to_editor('[CP_CALCULATED_FIELDS_RESULT]');
	}
	else
	{
		send_to_editor('[CP_CALCULATED_FIELDS]');
	}	
}

function cp_calculatedfieldsf_insertVar() {
    send_to_editor('[CP_CALCULATED_FIELDS_VAR name=""]');
}