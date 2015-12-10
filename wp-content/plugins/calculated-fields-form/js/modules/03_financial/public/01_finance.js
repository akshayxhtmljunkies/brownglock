/*
* finance.js v0.1
* By: Trent Richardson [http://trentrichardson.com]
*
* Copyright 2012 Trent Richardson
* You may use this project under MIT or GPL licenses.
* http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
* http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
*
* CALCULATED FIELD PROGRAMMERS
*  We've modified the object name and methods to avoid collisions with other libraries
*/

;(function(root){

	var lib = {};

	lib.cf_finance_version = '0.1';

	/*
	*	Defaults
	*/
	lib.settings = {
			format: 'number',
			formats: {
					USD: { before: '$', after: '', precision: 2, decimal: '.', thousand: ',', group: 3, negative: '-' }, // $
					GBP: { before:'£', after: '', precision: 2, decimal: '.', thousand: ',', group: 3, negative: '-' }, // £ or &#163;
					EUR: { before:'€', after: '', precision: 2, decimal: '.', thousand: ',', group: 3, negative: '-' }, // € or &#8364;
					percent: { before: '', after: '%', precision: 0, decimal: '.', thousand: ',', group: 3, negative: '-' },
					number: { before: '', after: '', precision: null, decimal: '.', thousand: ',', group: 3, negative: '-'},
					defaults: { before: '', after: '', precision: 0, decimal: '.', thousand: ',', group: 3, negative: '-' }
				}
		};

	lib.defaults = function(object, defs) {
			var key;
			object = object || {};
			defs = defs || {};
		
			for (key in defs) {
				if (defs.hasOwnProperty(key)) {
					if (object[key] == null) object[key] = defs[key];
				}
			}
			return object;
		};
	
	
	/*
	*	Formatting
	*/
	
	// add a currency format to library
	lib.ADDFORMAT = function(key, options){			
			this.settings.formats[key] = this.defaults(options, this.settings.formats.defaults);
			return true;
		};

	// remove a currency format from library
	lib.REMOVEFORMAT = function(key){
			delete this.settings.formats[key];
			return true;
		};

	// format a number or currency
	lib.NUMBERFORMAT = function(num, settings, override){			
			num = parseFloat(num);
			
			if(settings === undefined)
				settings = this.settings.formats[this.settings.format];
			else if(typeof settings == 'string')
				settings = this.settings.formats[settings];
			else settings = settings;
			settings = this.defaults(settings, this.settings.formats.defaults);
			
			if(override !== undefined)
				settings = this.defaults(override, settings);
			
			// set precision
			var tmp = num;
			if(settings.precision != null)
			{	
				tmp = Math.abs(num);
				tmp = tmp.toFixed(settings.precision);
				num = num.toFixed(settings.precision);
				
			}	

			var isNeg = num < 0,
				numParts = tmp.toString().split('.'),
				baseLen = numParts[0].length;

			// add thousands and group
			numParts[0] = numParts[0].replace(/(\d)/g, function(str, m1, offset, s){
					return (offset > 0 && (baseLen-offset) % settings.group == 0)? settings.thousand + m1 : m1;
				});
				
			// add decimal
			num = numParts.join(settings.decimal);

			// add negative if applicable
			if(isNeg && settings.negative){
				num = settings.negative[0] + num;
				if(settings.negative.length > 1)
					num += settings.negative[1];
			}
			
			return  settings.before + num + settings.after;
		};



	/*
	*	Financing
	*/
	
	// present value, calculate the present value of investment
	lib.PRESENTVALUE = function( rate, nper, pmt ){
			return pmt / rate * (1 - Math.pow(1 + rate, -1 * nper));
		};
		
	// future value, calculate the future value of an investment 
	// based on an interest rate and a constant payment schedule
	lib.FUTUREVALUE = function( rate, nper, pmt, pv, type ){
			if( typeof pv == 'undefined' ) pv = 0;
			if( typeof type == 'undefined' ) type = 0;
			
			rate = rate/100;
			
			var pow = Math.pow(1 + rate, nper);
			var fv = 0;

			if (rate) {
				fv = (pmt * (1 + rate * type) * (1 - pow) / rate) - pv * pow;
			} else {
				fv = -1 * (pv + pmt * nper);
			}

			return fv.toFixed( 2 );
		};
	
	//	calculate total of principle + interest (yearly) for x months
	lib.CALCULATEACCRUEDINTEREST = function(principle, months, rate){
			var i = rate/1200;
			return (principle * Math.pow(1+i,months)) - principle;
		};

	//	determine the amount financed
	lib.CALCULATEAMOUNT = function(finMonths, finInterest, finPayment){
			var result = 0;
				
			if(finInterest == 0){
				result = finPayment * finMonths;
			}
			else{ 
				var i = ((finInterest/100) / 12),
					i_to_m = Math.pow((i + 1), finMonths),		
					a = finPayment / ((i * i_to_m) / (i_to_m - 1));
				result = Math.round(a * 100) / 100;
			}

			return result;
		};

	//	determine the months financed
	lib.CALCULATEMONTHS = function(finAmount, finInterest, finPayment){
			var result = 0;

			if(finInterest == 0){
				result = Math.ceil(finAmount / finPayment);
			}
			else{ 
				result = Math.round(( (-1/12) * (Math.log(1-(finAmount/finPayment)*((finInterest/100)/12))) / Math.log(1+((finInterest/100)/12)) )*12);
			}
	
			return result;
		};

	//	determine the interest rate financed http://www.hughchou.org/calc/formula.html
	lib.CALCULATEINTEREST = function(finAmount, finMonths, finPayment){
			var result = 0;
	
			var min_rate = 0, max_rate = 100;
			while(min_rate < max_rate-0.0001){
				var mid_rate = (min_rate + max_rate)/2,
					j = mid_rate / 1200,
					guessed_pmt = finAmount * ( j / (1-Math.pow(1+j, finMonths*-1)));
			
				if(guessed_pmt > finPayment){
					max_rate = mid_rate;
				}
				else{
					min_rate = mid_rate;
				}
			}
			return mid_rate.toFixed(2);
		};

	//	determine the payment
	lib.CALCULATEPAYMENT = function(finAmount, finMonths, finInterest){
			var result = 0;
			if(finInterest == 0){
				result = finAmount / finMonths;
			}
			else{
				var i = ((finInterest/100) / 12),
					i_to_m = Math.pow((i + 1), finMonths),		
					p = finAmount * ((i * i_to_m) / (i_to_m - 1));
				result = Math.round(p * 100) / 100;
			}

			return result;
		};

	// get an amortization schedule [ { principle: 0, interest: 0, payment: 0, paymentToPrinciple: 0, paymentToInterest: 0}, {}, {}...]
	lib.CALCULATEAMORTIZATION = function(finAmount, finMonths, finInterest, finDate){
			var payment = this.CALCULATEPAYMENT(finAmount, finMonths, finInterest),
				balance = finAmount,
				interest = 0.0,
				totalInterest = 0.0,
				schedule = [],
				currInterest = null,
				currPrinciple = null,
				currDate = (finDate !== undefined && finDate.constructor === Date)? finDate : (new Date());

			for(var i=0; i<finMonths; i++){
				currInterest = balance * finInterest/1200;
				totalInterest += currInterest;
				currPrinciple = payment - currInterest;
				balance -= currPrinciple;

				schedule.push({
						principle: balance,
						interest: totalInterest,
						payment: payment,
						paymentToPrinciple: currPrinciple,
						paymentToInterest: currInterest,
						date: new Date(currDate.getTime())
					});
					
				currDate.setMonth(currDate.getMonth()+1);
			}
			return schedule;
		};

	lib.PMT = function(rate, nper, pv, fv, type) {
			if (!fv) fv = 0;
			if (!type) type = 0;
			
			rate = rate/100;
			if (rate == 0) return -(pv + fv)/nper;
			
			var pvif = Math.pow(1 + rate, nper);
			var pmt = rate / (pvif - 1) * -(pv * pvif + fv);

			if (type == 1) {
				pmt /= (1 + rate);
			};

			return pmt;
		};
		
	root.CF_FINANCE = lib;
	
})(this);
