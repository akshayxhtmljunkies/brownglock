/*
* datetime.js v0.1
* By: CALCULATED FIELD PROGRAMMERS
* The script allows operations with date and time
* Copyright 2013 CODEPEOPLE
* You may use this project under MIT or GPL licenses.
*/

;(function(root){
	var lib 		   = {},
		default_format = ( typeof window.DATETIMEFORMAT != 'undefined' ) ? window.DATETIMEFORMAT : 'yyyy-mm-dd hh:ii:ss a',
		regExp		   = ''; 
	

	Date.prototype.valid = function() {
		 return isFinite(this);
	};
	
	/*** PRIVATE FUNCTIONS ***/
	function _getDateObj( date, format ){
		var d = new Date();
		
		format = format || default_format;
		
		if( typeof date != 'undefined' ){
			if( typeof date == 'number' ){
				d = new Date( date*86400000 );
			}else if( typeof date == 'string' ){
				var p;
				if( null != ( p = /(\d{4})[\/\-\.](\d{2})[\/\-\.](\d{2})/.exec( date ) ) ){
					if( /y{4}[\/\-\.]m{2}[\/\-\.]d{2}/i.test( format ) ){
						d = new Date( p[ 1 ], ( p[ 2 ] - 1 ), p[ 3 ] );
					}else{
						d = new Date( p[ 1 ], ( p[ 3 ] - 1 ), p[ 2 ] );
					}
				}
				
				if( null != ( p = /(\d{2})[\/\-\.](\d{2})[\/\-\.](\d{4})/.exec( date ) ) ){
					if( /d{2}[\/\-\.]m{2}[\/\-\.]y{4}/i.test( format ) ){
						d = new Date( p[ 3 ], ( p[ 2 ] - 1 ), p[ 1 ] );
					}else{
						d = new Date( p[ 3 ], ( p[ 1 ] - 1 ), p[ 2 ] );
					}
				}
				
				if( null != ( p = /(\d{1,2})[:\.](\d{1,2})([:\.](\d{1,2}))?\s*([ap]m)?/i.exec( date ) ) ){
					if(/h+/i.test( format ) ){ 
						if( typeof p[ 5 ] != 'undefined' && /pm/i.test( p[ 5 ] ) ) p[ 1 ] = ( p[ 1 ]*1 + 12 ) % 24;
						d.setHours( p[ 1 ] ); 
					}
					
					if(/i+/i.test( format ) ) d.setMinutes( p[ 2 ] );
					if(/s+/i.test( format ) && (typeof p[ 4 ] != 'undefined') ) d.setSeconds( p[ 4 ] );
				}
				
			}else{
				d = new Date( date );
			}
		}
		return d;
	};
	
	/*** PUBLIC FUNCTIONS ***/
	
	lib.cf_datetime_version = '0.1';
	
	// DATEOBJ( date_string, date_format_string )
	lib.DATEOBJ = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d;
		return false;
	};
	
	// YEAR( date_string, date_format_string )
	lib.YEAR = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getFullYear();
		return false;
	};
	
	// MONTH( date_string, date_format_string )
	lib.MONTH = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getMonth()+1;
		return false;
	};
	
	// DAY( date_string, date_format_string )
	lib.DAY = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getDate();
		return false;
	};
	
	// WEEKDAY( date_string, date_format_string )
	lib.WEEKDAY = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getDay()+1;
		return false;
	};
	
	// WEEKDAY( date_string, date_format_string )
	lib.WEEKNUM	= function( date, format ){
		var d   = _getDateObj( date, format ),
			tmp = _getDateObj( date, format );
			
		if( d.valid() ){
			// ISO week date weeks start on monday
			var dayNr   = ( d.getDay() + 6 ) % 7;
			
			// ISO 8601 states that week 1 is the week with the first thursday of that year.
			tmp.setDate( d.getDate() - dayNr + 3 );
			
			// Store the millisecond value of the tmp date
			var firstThursday = tmp.valueOf();
			
			// Set the tmp to the first thursday of the year
			// First set the tmp to january first
			tmp.setMonth(0, 1);
			
			// Not a thursday? Correct the date to the next thursday
			if (tmp.getDay() != 4) {
				tmp.setMonth(0, 1 + ((4 - tmp.getDay()) + 7) % 7);
			}
			
			// The weeknumber is the number of weeks between the 
			// first thursday of the year and the thursday in the tmp week
			return 1 + Math.ceil((firstThursday - tmp) / 604800000); // 604800000 = 7 * 24 * 3600 * 1000

		}
		return false;
	};
	
	// HOURS( datetime_string, datetime_format_string )
	lib.HOURS = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getHours();
		return false;
	};
	
	// MINUTES( datetime_string, datetime_format_string )
	lib.MINUTES = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getMinutes();
		return false;
	};
	
	// SECONDS( datetime_string, datetime_format_string )
	lib.SECONDS = function( date, format ){
		var d = _getDateObj( date, format );
		if( d.valid() ) return d.getSeconds();
		return false;
	};
	
	// NOW() Return a datetime object
	lib.NOW = function(){
		return _getDateObj();
	};
	
	// TODAY() Return a datetime object limited to date only
	lib.TODAY = function(){
		var d = _getDateObj();
		d.setHours( 0 );
		d.setMinutes( 0 );
		d.setSeconds( 0 );
		return d;
	};
	
	
	/*
	* DATEDIFF( datetime_string, datetime_string, return_format) 	
	*
	* return_format: 
	* d  - number of days, and remaining hours, minutes and seconds 
	* m  - number of months, and remaining days, hours, minutes and seconds 
	* y  - number of years, and remaining months, days, hours, minutes and seconds 
	*
	* the function return an object with attributes: years, months and days depending of return_format argument
	*/
	lib.DATEDIFF = function( date_one, date_two, date_format, return_format ){
		var d1 = _getDateObj( date_one,  date_format ),
			d2 = _getDateObj( date_two, date_format ),
			diff,
			r  = {
				'years' 	: -1,
				'months'	: -1,
				'days'  	: -1,
				'hours' 	: -1,
				'minutes' 	: -1,
				'seconds'	: -1
			};

		if( d1.valid() && d2.valid() ){
			if( d1.valueOf() > d2.valueOf() ){
				d2 = _getDateObj( date_one, date_format );
				d1 = _getDateObj( date_two, date_format );
			}
			
			diff = d2.valueOf() - d1.valueOf();
			
			if( typeof return_format == 'undefined' || return_format == 'd' ){
				r.days = Math.floor( diff/86400000 );
			}else{
				var months,
					days,
					tmp;
				months = (d2.getFullYear() - d1.getFullYear()) * 12;
				months -= d1.getMonth() + 1;
				months += d2.getMonth() + 1;
				days = d2.getDate() - d1.getDate();
				if( days < 0 ){
					months--;
					tmp = new Date( d1.getFullYear(), d1.getMonth()+1 );
					days = ( tmp.valueOf() - d1.valueOf() )/86400000 + d2.getDate() - 1;
				}
				
				r.months = months;
				r.days = days;
				
				if( /y/i.test( return_format ) ){
					r.years = Math.floor( months/12 );
					r.months = months % 12;
				}
			}
			r.hours	  = Math.floor( diff%86400000/3600000 );
			r.minutes = Math.floor( diff%86400000%3600000/60000 );
			r.seconds = Math.floor( diff%86400000%3600000%60000 );
		}
		return r;
	};
	
	/*
	* DATETIMESUM( datetime_string, format, number, to_increase ) 	
	* to_increase: 
	* s  - seconds
    * i  - minutes	
	* h  - hours
	* d  - add the number of days, 
	* m  - add the number of months, 
	* y  - add the number of years
	*
	*/
	lib.DATETIMESUM = function( date, format, number, to_increase){
		var d = _getDateObj( date, format );
		if( d.valid() ){
			if( typeof number != 'number' || isNaN( parseInt( number ) ) ) number = 0;
			else number = parseInt( number );
			
			if( typeof to_increase == 'undefined' ) to_increase = 'd';
			
			
			if( /y+/i.test( to_increase ) ) 	 d.setFullYear( d.getFullYear() + number );
			else if( /d+/i.test( to_increase ) ) d.setDate( d.getDate() + number );
			else if( /m+/i.test( to_increase ) ) d.setMonth( d.getMonth() + number );
			else if( /h+/i.test( to_increase ) ) d.setHours( d.getHours() + number );
			else if( /i+/i.test( to_increase ) ) d.setMinutes( d.getMinutes() + number );
			else d.setSeconds( d.getSeconds() + number );

			return d;
		}
		return false;
	};
	
	// GETDATETIMESTRING( date_object, return_format ) Return the date object as a string representation determined by the return_format argument
	lib.GETDATETIMESTRING = function( date, format ){
	  if( typeof format == 'undefined' ) format = default_format;
	  
	  if( date.valid() ){
		var m = date.getMonth() + 1,
			d = date.getDate(),
			h = date.getHours(),
			i = date.getMinutes(),
			s = date.getSeconds(),
			a = ( h >= 12 ) ? 'pm' : 'am';
		
		m = ( m < 10 ) ? '0'+m : m;
		d = ( d < 10 ) ? '0'+d : d;
		if( /a+/.test( format ) ){
			h = h % 12;
			h = ( h ) ? h : 12; 
		}
		h = ( h < 10 ) ? '0'+h : h;
		i = ( i < 10 ) ? '0'+i : i;
		s = ( s < 10 ) ? '0'+s : s;
		
		return format.replace( /y+/i, date.getFullYear()  )
					 .replace( /m+/i, m )
					 .replace( /d+/i, d )
					 .replace( /h+/i, h )
					 .replace( /i+/i, i )
					 .replace( /s+/i, s )
					 .replace( /a+/i, a );
	  }
	  return date;
	};
	
	root.CF_DATETIME = lib;
	
})(this);