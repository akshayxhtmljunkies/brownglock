	$.fbuilder.typeList.push(
		{
			id:"fnumberds",
			name:"Number DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fnumberds' ]=function(){  this.init();  };
	$.extend(
		$.fbuilder.controls[ 'fnumberds' ].prototype,
		$.fbuilder.controls[ 'fnumber' ].prototype,
		{
			ftype:"fnumberds",
			init : function()
				{				
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'fnumber' ].prototype.display.call(this);
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fnumber' ].prototype.editItemEvents.call(this);
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fnumber' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'posttype', 'taxonomy', 'user' ], 'single' );
				},
			showPredefined : function()
				{
					return '';
				}
		}
	);