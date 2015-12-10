	$.fbuilder.typeList.push(
		{
			id:"ftextds",
			name:"Line Text DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'ftextds' ]=function(){  this.init();  };
	$.extend(
		$.fbuilder.controls[ 'ftextds' ].prototype,
		$.fbuilder.controls[ 'ftext' ].prototype,
		{
			ftype:"ftextds",
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
					$.fbuilder.controls[ 'ftext' ].prototype.editItemEvents.call(this);
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'ftext' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'posttype', 'taxonomy', 'user' ], 'single' );
				},
			showPredefined : function()
				{
					return '';
				}
		}
	);