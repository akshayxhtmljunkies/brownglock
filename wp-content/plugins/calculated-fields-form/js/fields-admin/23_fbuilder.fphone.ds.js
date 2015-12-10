	$.fbuilder.typeList.push(
		{
			id:"fPhoneds",
			name:"Phone DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fPhoneds' ] = function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'fPhoneds' ].prototype,
		$.fbuilder.controls[ 'fPhone' ].prototype,
		{
			ftype:"fPhoneds",
			init : function()
				{				
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'fPhone' ].prototype.display.call(this);	
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fPhone' ].prototype.editItemEvents.call(this);	
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fPhone' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database' ], 'single' );
				},
			showPredefined : function()
				{
					return '';
				}	
		}
	);