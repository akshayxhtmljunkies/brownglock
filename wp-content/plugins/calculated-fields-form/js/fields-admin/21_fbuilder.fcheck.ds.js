	$.fbuilder.typeList.push(
		{
			id:"fcheckds",
			name:"Checkboxes DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fcheckds' ] = function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'fcheckds' ].prototype,
		$.fbuilder.controls[ 'fcheck' ].prototype,
		{
			ftype:"fcheckds",
			init:function()
				{
					$.fbuilder.controls[ 'fcheck' ].prototype.init.call(this);
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'fcheck' ].prototype.display.call(this);	
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fcheck' ].prototype.editItemEvents.call(this);	
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fcheck' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'csv', 'posttype', 'taxonomy', 'user' ], 'pair' );
				},
			showChoiceIntance: function() 
				{
					return '';
				}
	});