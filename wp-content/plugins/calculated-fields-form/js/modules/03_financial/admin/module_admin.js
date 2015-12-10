fbuilderjQuery = (typeof fbuilderjQuery != 'undefined' ) ? fbuilderjQuery : jQuery;
fbuilderjQuery[ 'fbuilder' ] = fbuilderjQuery[ 'fbuilder' ] || {};
fbuilderjQuery[ 'fbuilder' ][ 'modules' ] = fbuilderjQuery[ 'fbuilder' ][ 'modules' ] || {};

fbuilderjQuery[ 'fbuilder' ][ 'modules' ][ 'financial' ] = {
	'tutorial' : 'http://wordpress.dwbooster.com/includes/calculated-field/financial.module.html',
	'toolbars'		: {
		'finance' : {
			'label' : 'Financial functions',
			'buttons' : [
							{	
								"value" : "CALCULATEPAYMENT", 		
								"code"  : "CALCULATEPAYMENT(", 		
								"tip"   : "<p><strong>Calculate the Financed Payment Amount</strong></p> \
										  <p>Three parameters: amount, months, interest rate (percent)</p> \
								          <p>Ex: <strong>CALCULATEPAYMENT(25000, 60, 5.25)</strong><br /> \
										  Result: <strong>474.65</strong></p>" 
							},
							
							{	
								"value" : "CALCULATEAMOUNT", 		
								"code"  : "CALCULATEAMOUNT(", 		
								"tip"   : "<p><strong>Calculate the Financed Amount</strong></p> \
										  <p>Three parameters: months, interest rate (percent), payment </p> \
								          <p>Ex: <strong>CALCULATEAMOUNT(60, 5.25, 474.65)</strong><br /> \
										  Result: <strong>25000.02</strong></p>" 
							},
							
							{	
								"value" : "CALCULATEMONTHS", 		
								"code"  : "CALCULATEMONTHS(", 		
								"tip"   : "<p><strong>Calculate the Months Financed</strong></p>\
										  <p>Three parameters: amount, interest rate (percent), payment</p> \
								          <p>Ex: <strong>CALCULATEMONTHS(25000, 5.25, 474.65)</strong><br /> \
										  Result: <strong>60</strong></p>" 
							},
							
							{	
								"value" : "CALCULATEINTEREST", 		
								"code"  : "CALCULATEINTEREST(", 		
								"tip"   : "<p><strong>Calculate the Financed Interest Rate</strong></p>\
										  <p>Three parameters: amount, months, payment</p>\
								          <p>Ex: <strong>CALCULATEINTEREST(25000, 60, 474.65)</strong><br /> \
										  Result: <strong>5.25</strong></p>" 
							},
							
							{	
								"value" : "CALCULATEACCRUEDINTEREST", 		
								"code"  : "CALCULATEACCRUEDINTEREST(", 		
								"tip"   : "<p><strong>Calculate the Accrued Interest</strong></p>\
										  <p>If your money is in a bank account accruing interest, how much does it earn over x months? Three parameters: principle amount, months, interest rate (percent)</p>\
								          <p>Ex: <strong>CALCULATEACCRUEDINTEREST(25000, 60, 5.25)</strong><br /> \
										  Result: <strong>7485.806648756854</strong></p>" 
							},
							
							{	
								"value" : "CALCULATEAMORTIZATION", 		
								"code"  : "CALCULATEAMORTIZATION(", 	
								"tip"   : "<p><strong>Create Amortization Schedule</strong></p>\
										  <p>Create an amortization schedule. The result should be an array the length the number of months. Each entry is an object. Four parameters: principle amount, months, interest rate (percent), start date (optional Date object)</p>\
								          <p>Ex: <strong>CALCULATEAMORTIZATION(25000, 60, 5.25, new Date(2011,11,20) )</strong><br /> \
										  Result: <br /> \
										  <strong> \
											[ <br /> \
												&nbsp;&nbsp;{ <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;principle: 24634.725 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;interest: 109.375 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;payment: 474.65 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;paymentToPrinciple: 365.275 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;paymentToInterest: 109.375 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;date: Tue Dec 20 2011 00:00:00 GMT+0100 (Romance Daylight Time) <br /> \
												&nbsp;&nbsp;}, <br /> \
												&nbsp;&nbsp;{ <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;principle: 24267.851921874997 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;interest: 217.151921875 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;payment: 474.65 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;paymentToPrinciple: 366.873078125 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;paymentToInterest: 107.776921875 <br /> \
													&nbsp;&nbsp;&nbsp;&nbsp;date: Fri Jan 20 2012 00:00:00 GMT+0100 (Romance Daylight Time) <br /> \
												&nbsp;&nbsp;}, <br /> \
												... <br /> \
											] \
										  </strong></p>" 
							},
							
							{	
								"value" : "PRESENTVALUE", 		
								"code"  : "PRESENTVALUE(", 	
								"tip"   : "<p><strong>Returns the present value of an investment</strong></p>\
										  <p>The present value is the total amount that a series of future payments is worth now. Three parameters:  The interest rate per period, the total number of payment periods in an annuity, the payment made each period and cannot change over the life of the annuity</p>\
								          <p>Ex: <strong>PRESENTVALUE(0.08,5,100)</strong><br /> \
										  Result: <strong>399.27</strong></p>" 
							},
							
							{	
								"value" : "FUTUREVALUE", 		
								"code"  : "FUTUREVALUE(", 	
								"tip"   : "<p><strong>Returns the future value of an investment based on an interest rate and a constant payment schedule</strong></p>\
										  <p>The future value of an investment based on an interest rate and a constant payment schedule. Five parameters:  The interest rate for the investment, the number of payments for the annuity, the  amount of the payment made each period, the present value of the payments (if this parameter is omitted, it assumes to be 0),  parameter that indicates when the payments are due (if this parameter is omitted, it assumes to be 0. The possible values are: 0 - Payments are due at the end of the period, 1 - Payments are due at the beginning of the period)</p>\
								          <p>Ex: <strong>FUTUREVALUE(7.5/12,24,-250,-5000,1)</strong></p>" 
							},
							
							{	
								"value" : "PMT", 		
								"code"  : "PMT(", 	
								"tip"   : "<p><strong>Returns the periodic payment for an annuity with constant interest rates</strong></p><p><strong>Rate</strong> is the periodic interest rate.<br><strong>NPer</strong> is the number of periods in which annuity is paid.<br><strong>PV</strong> is the present value (cash value) in a sequence of payments.<br><strong>FV</strong> (optional) is the desired value (future value) to be reached at the end of the periodic payments.<br><strong>Type</strong> (optional) is the due date for the periodic payments. Type=1 is payment at the beginning and Type=0 is payment at the end of each period.<br>In the LibreOffice Calc functions, parameters marked as optional can be left out only when no parameter follows. For example, in a function with four parameters, where the last two parameters are marked as optional, you can leave out parameter 4 or parameters 3 and 4, but you cannot leave out parameter 3 alone.</p><p>Ex: <strong>PMT(1.99/12,36,25000)</strong><br>Result: <strong>-715.96</strong></p>" 
							},
							
							{	
								"value" : "NUMBERFORMAT", 		
								"code"  : "NUMBERFORMAT(", 		
								"tip"   : "<p><strong>Format a Number</strong></p>\
										  <p>One parameters: number</p>\
										  <p>Ex: <strong>NUMBERFORMAT(-2530023420269.123456)</strong><br /> \
										  Result: <strong>-2,530,023,420,269</strong></p>\
										  <p>Ex: <strong>NUMBERFORMAT(25000.123456, {precision:2})</strong><br /> \
										  Result: <strong>25,000.12</strong></p>\
										  <p><strong>Format Currency</strong></p>\
										  <p>Format a number to a certain currency. Two parameters: number, settings (optional). If settings option is a string it is treated as a currency name. If it is an object it is used as currency settings.</p>\
										  <p>Ex: <strong>NUMBERFORMAT(25000.123456, \\'USD\\')</strong><br />\
										  Result: <strong>$25,000.12</strong></p>\
										  <p>Settings can be format, and then override with options.</p>\
										  <p>Ex: <strong>NUMBERFORMAT(-25000.123456, \\'GBP\\', { negative: \\'()\\', precision: 3, thousand: \\'\\' })</strong><br />\
										  Result: <strong>£(25000.123)</strong></p>\
										  <p><strong>Format a Percent</strong></p>\
										  <p>Format a number with a certain precision. Two parameters: number, settings (&quot;percent&quot; is a format)</p>\
										  <p>Ex: <strong>NUMBERFORMAT(25000.123456, \\'percent\\')</strong><br /> \
										  Result: <strong>25,000%</strong></p>" 
							},
							
							{	
								"value" : "ADDFORMAT", 		
								"code"  : "ADDFORMAT(", 		
								"tip"   : "<p><strong>Create a Currency</strong></p>\
										  <p>You may create a currency. The library comes with &quot;USD&quot;, &quot;GBP&quot;, and &quot;EUR&quot; currency formats and &quot;number&quot; and &quot;percent&quot; numeric formats. Two parameters: key, settings</p>\
										  <p>Ex: <strong>ADDFORMAT(\\'Dollars\\', { before: \\'\\', after: \\' Dollars\\', precision: 0, thousand: \\',\\', group: 3, decimal: \\'.\\', negative: \\'-\\' })</strong><br /> \
										  Result: <strong>true</strong></p>\
										  <p>Ex: <strong>NUMBERFORMAT(25000.123456, \\'Dollars\\')</strong><br /> \
										  Result: <strong>25,000 Dollars</strong></p>" 
							},
							
							{	
								"value" : "REMOVEFORMAT", 		
								"code"  : "REMOVEFORMAT(", 		
								"tip"   : "<p><strong>Remove a Currency</strong></p>\
										  <p>To remove a currency. One parameter: key</p>\
										  <p>Ex: <strong>REMOVEFORMAT(\\'Dollars\\')</strong><br /> \
										  Result: <strong>true</strong></p>" 
							}
						]
		}
	}
};