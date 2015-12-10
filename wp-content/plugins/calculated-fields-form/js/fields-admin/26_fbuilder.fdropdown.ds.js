	$.fbuilder.typeList.push(
		{
			id:"fdropdownds",
			name:"Dropdown DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fdropdownds' ] = function(){ this.init(); };
	$.extend(
		$.fbuilder.controls[ 'fdropdownds' ].prototype,
		$.fbuilder.controls[ 'fdropdown' ].prototype,
		{
			ftype:"fdropdownds",
			init : function()
				{		
					this.choices = [];
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'fdropdown' ].prototype.display.call(this);	
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fdropdown' ].prototype.editItemEvents.call(this);	
					this.editItemEventsDS();
				},
				
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fdropdown' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'csv', 'posttype', 'taxonomy', 'user' ], 'pair' );
				},
			showChoiceIntance: function() 
				{
					return '';
				}
		}
	);