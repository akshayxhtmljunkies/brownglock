	$.fbuilder.typeList.push(
		{
			id:"fradiods",
			name:"Radio Btns DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fradiods' ] = function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'fradiods' ].prototype,
		$.fbuilder.controls[ 'fradio' ].prototype,
		{
			ftype:"fradiods",
			init:function()
				{
					$.fbuilder.controls[ 'fradio' ].prototype.init.call(this);
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'fradio' ].prototype.display.call(this);	
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fradio' ].prototype.editItemEvents.call(this);	
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fradio' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'csv', 'posttype', 'taxonomy', 'user' ], 'pair' );
				},
			showChoiceIntance: function() 
				{
					return '';
				}	
		}
	);