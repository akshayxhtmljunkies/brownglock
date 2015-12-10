fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'datetime' ] = {
	'tutorial' : 'http://wordpress.dwbooster.com/includes/calculated-field/datetime.module.html',
	'toolbars'		: {
		'datetime' : {
			'label' : 'Date Time functions',
			'buttons' : [
							{ "value" : "DATEOBJ", 				"code" : "DATEOBJ(", 			"tip" : "<p>Get the date object from an string representation of date. <strong>DATEOBJ( date_string, format )</strong></p><p><strong>DATEOBJ(\\'2013-05-21\\', \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>date object</strong></p>" },
							{ "value" : "YEAR", 				"code" : "YEAR(", 				"tip" : "<p>Get the year from an string representation of date. <strong>YEAR( date_string, format )</strong></p><p><strong>YEAR(\\'2013-05-21\\', \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>2013</strong></p>" },
							{ "value" : "MONTH", 				"code" : "MONTH(", 				"tip" : "<p>Get the month from an string representation of date. <strong>MONTH( date_string, format )</strong></p><p><strong>MONTH(\\'2013-05-21\\', \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>5</strong></p>" },
							{ "value" : "DAY", 					"code" : "DAY(", 				"tip" : "<p>Get the days from an string representation of date. <strong>DAY( date_string, format )</strong></p><p><strong>DAY(\\'2013-05-21\\', \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>21</strong></p>" },
							{ "value" : "WEEKDAY", 				"code" : "WEEKDAY(", 			"tip" : "<p>Get the week day from an string representation of date. <strong>WEEKDAY( date_string, format )</strong></p><p><strong>WEEKDAY(\\'2013-10-27\\', \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>1</strong> Sunday is the day number one</p>" },
							{ "value" : "WEEKNUM", 				"code" : "WEEKNUM(", 			"tip" : "<p>Get the week number from an string representation of date, a year has 53 weeks. <strong>WEEKNUM( date_string, format )</strong></p><p><strong>WEEKNUM(\\'2013-10-27\\', \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>43</strong></p>" },
							{ "value" : "HOURS", 				"code" : "HOURS(", 				"tip" : "<p>Get hours from an string representation of datetime. <strong>HOURS( datetime_string, format )</strong></p><p><strong>HOURS(\\'2013-10-27 01:21\\', \\'yyyy-mm-dd hh:ii\\')</strong></p><p>Result: <strong>1</strong></p>" },
							{ "value" : "MINUTES", 				"code" : "MINUTES(", 			"tip" : "<p>Get minutes from an string representation of datetime. <strong>MINUTES( datetime_string, format )</strong></p><p><strong>MINUTES(\\'2013-10-27 01:22\\', \\'yyyy-mm-dd hh:ii\\')</strong></p><p>Result: <strong>22</strong></p>" },
							{ "value" : "SECONDS", 				"code" : "SECONDS(", 			"tip" : "<p>Get seconds from an string representation of datetime. <strong>SECONDS( datetime_string, format )</strong></p><p><strong>SECONDS(\\'2013-10-27 01:22:56\\', \\'yyyy-mm-dd hh:ii:ss\\')</strong></p><p>Result: <strong>56</strong></p>" },
							{ "value" : "NOW", 					"code" : "NOW(", 				"tip" : "<p>Get a date object with the current day-time information. <strong>NOW()</strong></p><p><strong>NOW()</strong></p><p>Result: <strong>2013-10-27 01:42:19</strong></p>" },
							{ "value" : "TODAY", 				"code" : "TODAY(", 				"tip" : "<p>Get a date object with the current day information, without the time part. <strong>TODAY()</strong></p>" },
							{ "value" : "DATEDIFF", 			"code" : "DATEDIFF(", 			"tip" : "<p>Get the difference between two dates strings representation</p><p><strong>DATEDIFF(date_one, date_two, date_format, return)</strong></p><p>The function return an object, whose value depends of argument \\'return\\'</p><p>Possible values of return argument:<br />d - return the number of days between two dates<br />m - return the number of months between two dates, and remaining days<br />y - return the number of years between two dates, remaining months, and remaining days</p><p><strong>DATEDIFF(\\'2013-10-27\\', \\'2012-06-22\\', \\'yyyy-mm-dd\\', \\'y\\')[\\'months\\']</strong><p><p>Result:<strong> 5 </strong></p>" },
							{ "value" : "DATETIMESUM", 			"code" : "DATETIMESUM(",		"tip" : "<p>Increases the date-time string representation in the number of seconds, minutes, hours, days, months, or years, passed as parameter.</p><p><strong>DATETIMESUM( date_string, format, number, to_increase )</strong></p><p>DATETIMESUM(\\'2013-10-27\\', \\'yyyy-mm-dd\\', 5, \\'d\\')</p><p>Result: <strong>The date object representation of 2013/11/01</strong></p>" },
							{ "value" : "GETDATETIMESTRING", 	"code" : "GETDATETIMESTRING(",	"tip" : "<p>Returns the string representation of a date object</p><p><strong>GETDATETIMESTRING( datetime_object, format )</strong></p><p><strong>GETDATETIMESTRING(TODAY(), \\'yyyy-mm-dd\\')</strong></p><p>Result: <strong>2013-10-27</strong></p>" }
						]
		}
	}
};