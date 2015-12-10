	$.fbuilder.typeList.push(
		{
			id:"femailds",
			name:"Email DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'femailds'] = function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'femailds' ].prototype,
		$.fbuilder.controls[ 'femail' ].prototype,
		{
			ftype:"femailds",
			init : function()
				{				
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'femail' ].prototype.display.call(this);
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'femail' ].prototype.editItemEvents.call(this);
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'femail' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'user' ], 'single' );
				},
			showPredefined : function()
				{
					return '';
				}
	});