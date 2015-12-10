	$.fbuilder.typeList.push(
		{
			id:"fhiddends",
			name:"Hidden DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fhiddends' ]=function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'fhiddends' ].prototype,
		$.fbuilder.controls[ 'fhidden' ].prototype,
		{
			ftype:"fhiddends",
			init : function()
				{				
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'ftext' ].prototype.display.call(this);
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fhidden' ].prototype.editItemEvents.call(this);
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fhidden' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'posttype', 'taxonomy', 'user' ], 'single' );
				},
			showPredefined: function() 
				{
					return '';
				}
		}
	);