	$.fbuilder.typeList.push(
		{
			id:"ftextareads",
			name:"Text Area DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'ftextareads' ] = function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'ftextareads' ].prototype,
		$.fbuilder.controls[ 'ftextarea' ].prototype,
		{
			ftype:"ftextareads",
			init : function()
				{				
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'ftextarea' ].prototype.display.call(this);
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'ftextarea' ].prototype.editItemEvents.call(this);
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'ftextarea' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'posttype' ], 'single' );
				},
			showPredefined : function()
				{
					return '';
				}		
		}
	);