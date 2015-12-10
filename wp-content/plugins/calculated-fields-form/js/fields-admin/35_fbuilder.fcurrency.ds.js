	$.fbuilder.typeList.push(
		{
			id:"fcurrencyds",
			name:"Currency DS",
			control_category:20
		}
	);
	$.fbuilder.controls[ 'fcurrencyds' ]=function(){  this.init();  };
	$.extend(
		$.fbuilder.controls[ 'fcurrencyds' ].prototype,
		$.fbuilder.controls[ 'fcurrency' ].prototype,
		{
			ftype:"fcurrencyds",
			init : function()
				{				
					$.extend(true, this, new $.fbuilder.controls[ 'datasource' ]() );
				},
			display:function()
				{
					return $.fbuilder.controls[ 'fcurrency' ].prototype.display.call(this);
				},
			editItemEvents:function()
				{
					$.fbuilder.controls[ 'fcurrency' ].prototype.editItemEvents.call(this);
					this.editItemEventsDS();
				},
			showAllSettings:function()
				{
					return $.fbuilder.controls[ 'fcurrency' ].prototype.showAllSettings.call(this)+this.showDataSource( [ 'database', 'posttype', 'taxonomy', 'user' ], 'single' );
				},
			showPredefined : function()
				{
					return '';
				}
		}
	);