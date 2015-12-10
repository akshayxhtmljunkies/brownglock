fbuilderjQuery=(typeof fbuilderjQuery!='undefined')?fbuilderjQuery:jQuery;fbuilderjQuery(window).bind('pageshow',function(event){if(typeof event.originalEvent['persisted']!='undefined'&&event.originalEvent['persisted'])location.reload();});fbuilderjQuery(function(){(function($){$.fbuilder=$.fbuilder||{};$.fbuilder['objName']='fbuilderjQuery';;(function(root){var lib={};lib.cf_logical_version='0.1';lib.IF=function(_if,_then,_else){if(_if){return(typeof _then==='undefined')?true:_then;}else{return(typeof _else==='undefined')?false:_else;}};lib.AND=function(){for(var i=0,h=arguments.length;i<h;i++){if(!arguments[i]){return false;}}
return true;};lib.OR=function(){for(var i=0,h=arguments.length;i<h;i++){if(arguments[i]){return true;}}
return false;};lib.NOT=function(_term){return(typeof _term=='undefined')?true:!_term;};lib.IN=function(_term,_values){function _reduce(str){return String(str).replace(/^\s+/,'').replace(/\s+$/,'').replace(/\s+/,' ').toLowerCase();};_term=_reduce(_term);if(typeof _values=='string')return _reduce(_values).indexOf(_term)!=-1;else if(typeof _values=='object'&&_values.length){for(var i=0,h=_values.length;i<h;i++)if(_reduce(_values[i]).indexOf(_term)!=-1)return true;}
return false;};root.CF_LOGICAL=lib;})(this);fbuilderjQuery=(typeof fbuilderjQuery!='undefined')?fbuilderjQuery:jQuery;fbuilderjQuery['fbuilder']=fbuilderjQuery['fbuilder']||{};fbuilderjQuery['fbuilder']['modules']=fbuilderjQuery['fbuilder']['modules']||{};fbuilderjQuery['fbuilder']['modules']['default']={'prefix':'','callback':function()
{if(window.PREC==undefined)
{window.PREC=window.prec=function(num,pr)
{if(/^\d+$/.test(pr)&&/^[+-]?\d+(\.\d+)?$/.test(num))
{result=num.toFixed(pr);return result;}
return num;};}
if(window.CDATE==undefined)
{window.CDATE=window.cdate=function(num,format)
{format=(typeof format!='undefined')?format:((typeof window.DATETIMEFORMAT!='undefined')?window.DATETIMEFORMAT:'dd/mm/yyyy');if(isFinite(num*1))
{num=Math.round(Math.abs(num)*86400000);var date=new Date(num),d=date.getDate(),m=date.getMonth()+1,y=date.getFullYear(),h=date.getHours(),i=date.getMinutes(),s=date.getSeconds(),a='';m=(m<10)?'0'+m:m;d=(d<10)?'0'+d:d;if(/a/.test(format))
{a=(h>=12)?'pm':'am';h=h%12;h=(h==0)?12:h;}
h=(h<10)?'0'+h:h;i=(i<10)?'0'+i:i;s=(s<10)?'0'+s:s;return format.replace(/y+/i,y).replace(/m+/i,m).replace(/d+/i,d).replace(/h+/i,h).replace(/i+/i,i).replace(/s+/i,s).replace(/a+/i,a);}
return num;};}
var math_prop=["LN10","PI","E","LOG10E","SQRT2","LOG2E","SQRT1_2","LN2","cos","pow","log","tan","sqrt","ceil","asin","abs","max","exp","atan2","random","round","floor","acos","atan","min","sin"];for(var i=0,h=math_prop.length;i<h;i++)
{if(!window[math_prop[i]])
{window[math_prop[i]]=window[math_prop[i].toUpperCase()]=Math[math_prop[i]];}}
fbuilderjQuery['fbuilder']['extend_window'](fbuilderjQuery['fbuilder']['modules']['default']['prefix'],CF_LOGICAL);},'validator':function(v)
{return(typeof v=='number')?isFinite(v):(typeof v!='undefined');}};;(function(root){var lib={},default_format=(typeof window.DATETIMEFORMAT!='undefined')?window.DATETIMEFORMAT:'yyyy-mm-dd hh:ii:ss a',regExp='';Date.prototype.valid=function(){return isFinite(this);};function _getDateObj(date,format){var d=new Date();format=format||default_format;if(typeof date!='undefined'){if(typeof date=='number'){d=new Date(date*86400000);}else if(typeof date=='string'){var p;if(null!=(p=/(\d{4})[\/\-\.](\d{2})[\/\-\.](\d{2})/.exec(date))){if(/y{4}[\/\-\.]m{2}[\/\-\.]d{2}/i.test(format)){d=new Date(p[1],(p[2]-1),p[3]);}else{d=new Date(p[1],(p[3]-1),p[2]);}}
if(null!=(p=/(\d{2})[\/\-\.](\d{2})[\/\-\.](\d{4})/.exec(date))){if(/d{2}[\/\-\.]m{2}[\/\-\.]y{4}/i.test(format)){d=new Date(p[3],(p[2]-1),p[1]);}else{d=new Date(p[3],(p[1]-1),p[2]);}}
if(null!=(p=/(\d{1,2})[:\.](\d{1,2})([:\.](\d{1,2}))?\s*([ap]m)?/i.exec(date))){if(/h+/i.test(format)){if(typeof p[5]!='undefined'&&/pm/i.test(p[5]))p[1]=(p[1]*1+12)%24;d.setHours(p[1]);}
if(/i+/i.test(format))d.setMinutes(p[2]);if(/s+/i.test(format)&&(typeof p[4]!='undefined'))d.setSeconds(p[4]);}}else{d=new Date(date);}}
return d;};lib.cf_datetime_version='0.1';lib.DATEOBJ=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d;return false;};lib.YEAR=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getFullYear();return false;};lib.MONTH=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getMonth()+1;return false;};lib.DAY=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getDate();return false;};lib.WEEKDAY=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getDay()+1;return false;};lib.WEEKNUM=function(date,format){var d=_getDateObj(date,format),tmp=_getDateObj(date,format);if(d.valid()){var dayNr=(d.getDay()+6)%7;tmp.setDate(d.getDate()-dayNr+3);var firstThursday=tmp.valueOf();tmp.setMonth(0,1);if(tmp.getDay()!=4){tmp.setMonth(0,1+((4-tmp.getDay())+7)%7);}
return 1+Math.ceil((firstThursday-tmp)/604800000);}
return false;};lib.HOURS=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getHours();return false;};lib.MINUTES=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getMinutes();return false;};lib.SECONDS=function(date,format){var d=_getDateObj(date,format);if(d.valid())return d.getSeconds();return false;};lib.NOW=function(){return _getDateObj();};lib.TODAY=function(){var d=_getDateObj();d.setHours(0);d.setMinutes(0);d.setSeconds(0);return d;};lib.DATEDIFF=function(date_one,date_two,date_format,return_format){var d1=_getDateObj(date_one,date_format),d2=_getDateObj(date_two,date_format),diff,r={'years':-1,'months':-1,'days':-1,'hours':-1,'minutes':-1,'seconds':-1};if(d1.valid()&&d2.valid()){if(d1.valueOf()>d2.valueOf()){d2=_getDateObj(date_one,date_format);d1=_getDateObj(date_two,date_format);}
diff=d2.valueOf()-d1.valueOf();if(typeof return_format=='undefined'||return_format=='d'){r.days=Math.floor(diff/86400000);}else{var months,days,tmp;months=(d2.getFullYear()-d1.getFullYear())*12;months-=d1.getMonth()+1;months+=d2.getMonth()+1;days=d2.getDate()-d1.getDate();if(days<0){months--;tmp=new Date(d1.getFullYear(),d1.getMonth()+1);days=(tmp.valueOf()-d1.valueOf())/86400000+d2.getDate()-1;}
r.months=months;r.days=days;if(/y/i.test(return_format)){r.years=Math.floor(months/12);r.months=months%12;}}
r.hours=Math.floor(diff%86400000/3600000);r.minutes=Math.floor(diff%86400000%3600000/60000);r.seconds=Math.floor(diff%86400000%3600000%60000);}
return r;};lib.DATETIMESUM=function(date,format,number,to_increase){var d=_getDateObj(date,format);if(d.valid()){if(typeof number!='number'||isNaN(parseInt(number)))number=0;else number=parseInt(number);if(typeof to_increase=='undefined')to_increase='d';if(/y+/i.test(to_increase))d.setFullYear(d.getFullYear()+number);else if(/d+/i.test(to_increase))d.setDate(d.getDate()+number);else if(/m+/i.test(to_increase))d.setMonth(d.getMonth()+number);else if(/h+/i.test(to_increase))d.setHours(d.getHours()+number);else if(/i+/i.test(to_increase))d.setMinutes(d.getMinutes()+number);else d.setSeconds(d.getSeconds()+number);return d;}
return false;};lib.GETDATETIMESTRING=function(date,format){if(typeof format=='undefined')format=default_format;if(date.valid()){var m=date.getMonth()+1,d=date.getDate(),h=date.getHours(),i=date.getMinutes(),s=date.getSeconds(),a=(h>=12)?'pm':'am';m=(m<10)?'0'+m:m;d=(d<10)?'0'+d:d;if(/a+/.test(format)){h=h%12;h=(h)?h:12;}
h=(h<10)?'0'+h:h;i=(i<10)?'0'+i:i;s=(s<10)?'0'+s:s;return format.replace(/y+/i,date.getFullYear()).replace(/m+/i,m).replace(/d+/i,d).replace(/h+/i,h).replace(/i+/i,i).replace(/s+/i,s).replace(/a+/i,a);}
return date;};root.CF_DATETIME=lib;})(this);fbuilderjQuery=(typeof fbuilderjQuery!='undefined')?fbuilderjQuery:jQuery;fbuilderjQuery['fbuilder']=fbuilderjQuery['fbuilder']||{};fbuilderjQuery['fbuilder']['modules']=fbuilderjQuery['fbuilder']['modules']||{};fbuilderjQuery['fbuilder']['modules']['datetime']={'prefix':'','callback':function()
{fbuilderjQuery['fbuilder']['extend_window'](fbuilderjQuery['fbuilder']['modules']['datetime']['prefix'],CF_DATETIME);},'validator':function(v)
{if(/^\s*((\d{4}[\/\-\.]\d{1,2}[\/\-\.]\d{1,2})|(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4}))?\s*(\d{1,2}\s*:\s*\d{1,2}(\s*:\s*\d{1,2})?(\s*[ap]m)?)?\s*$/i.test(v))
{return true;}
return false;}};;(function(root){var lib={};lib.cf_finance_version='0.1';lib.settings={format:'number',formats:{USD:{before:'$',after:'',precision:2,decimal:'.',thousand:',',group:3,negative:'-'},GBP:{before:'£',after:'',precision:2,decimal:'.',thousand:',',group:3,negative:'-'},EUR:{before:'€',after:'',precision:2,decimal:'.',thousand:',',group:3,negative:'-'},percent:{before:'',after:'%',precision:0,decimal:'.',thousand:',',group:3,negative:'-'},number:{before:'',after:'',precision:null,decimal:'.',thousand:',',group:3,negative:'-'},defaults:{before:'',after:'',precision:0,decimal:'.',thousand:',',group:3,negative:'-'}}};lib.defaults=function(object,defs){var key;object=object||{};defs=defs||{};for(key in defs){if(defs.hasOwnProperty(key)){if(object[key]==null)object[key]=defs[key];}}
return object;};lib.ADDFORMAT=function(key,options){this.settings.formats[key]=this.defaults(options,this.settings.formats.defaults);return true;};lib.REMOVEFORMAT=function(key){delete this.settings.formats[key];return true;};lib.NUMBERFORMAT=function(num,settings,override){num=parseFloat(num);if(settings===undefined)
settings=this.settings.formats[this.settings.format];else if(typeof settings=='string')
settings=this.settings.formats[settings];else settings=settings;settings=this.defaults(settings,this.settings.formats.defaults);if(override!==undefined)
settings=this.defaults(override,settings);var tmp=num;if(settings.precision!=null)
{tmp=Math.abs(num);tmp=tmp.toFixed(settings.precision);num=num.toFixed(settings.precision);}
var isNeg=num<0,numParts=tmp.toString().split('.'),baseLen=numParts[0].length;numParts[0]=numParts[0].replace(/(\d)/g,function(str,m1,offset,s){return(offset>0&&(baseLen-offset)%settings.group==0)?settings.thousand+m1:m1;});num=numParts.join(settings.decimal);if(isNeg&&settings.negative){num=settings.negative[0]+num;if(settings.negative.length>1)
num+=settings.negative[1];}
return settings.before+num+settings.after;};lib.PRESENTVALUE=function(rate,nper,pmt){return pmt/rate*(1-Math.pow(1+rate,-1*nper));};lib.FUTUREVALUE=function(rate,nper,pmt,pv,type){if(typeof pv=='undefined')pv=0;if(typeof type=='undefined')type=0;rate=rate/100;var pow=Math.pow(1+rate,nper);var fv=0;if(rate){fv=(pmt*(1+rate*type)*(1-pow)/rate)-pv*pow;}else{fv=-1*(pv+pmt*nper);}
return fv.toFixed(2);};lib.CALCULATEACCRUEDINTEREST=function(principle,months,rate){var i=rate/1200;return(principle*Math.pow(1+i,months))-principle;};lib.CALCULATEAMOUNT=function(finMonths,finInterest,finPayment){var result=0;if(finInterest==0){result=finPayment*finMonths;}
else{var i=((finInterest/100)/12),i_to_m=Math.pow((i+1),finMonths),a=finPayment/((i*i_to_m)/(i_to_m-1));result=Math.round(a*100)/100;}
return result;};lib.CALCULATEMONTHS=function(finAmount,finInterest,finPayment){var result=0;if(finInterest==0){result=Math.ceil(finAmount/finPayment);}
else{result=Math.round(((-1/12)*(Math.log(1-(finAmount/finPayment)*((finInterest/100)/12)))/Math.log(1+((finInterest/100)/12)))*12);}
return result;};lib.CALCULATEINTEREST=function(finAmount,finMonths,finPayment){var result=0;var min_rate=0,max_rate=100;while(min_rate<max_rate-0.0001){var mid_rate=(min_rate+max_rate)/2,j=mid_rate/1200,guessed_pmt=finAmount*(j/(1-Math.pow(1+j,finMonths*-1)));if(guessed_pmt>finPayment){max_rate=mid_rate;}
else{min_rate=mid_rate;}}
return mid_rate.toFixed(2);};lib.CALCULATEPAYMENT=function(finAmount,finMonths,finInterest){var result=0;if(finInterest==0){result=finAmount/finMonths;}
else{var i=((finInterest/100)/12),i_to_m=Math.pow((i+1),finMonths),p=finAmount*((i*i_to_m)/(i_to_m-1));result=Math.round(p*100)/100;}
return result;};lib.CALCULATEAMORTIZATION=function(finAmount,finMonths,finInterest,finDate){var payment=this.CALCULATEPAYMENT(finAmount,finMonths,finInterest),balance=finAmount,interest=0.0,totalInterest=0.0,schedule=[],currInterest=null,currPrinciple=null,currDate=(finDate!==undefined&&finDate.constructor===Date)?finDate:(new Date());for(var i=0;i<finMonths;i++){currInterest=balance*finInterest/1200;totalInterest+=currInterest;currPrinciple=payment-currInterest;balance-=currPrinciple;schedule.push({principle:balance,interest:totalInterest,payment:payment,paymentToPrinciple:currPrinciple,paymentToInterest:currInterest,date:new Date(currDate.getTime())});currDate.setMonth(currDate.getMonth()+1);}
return schedule;};lib.PMT=function(rate,nper,pv,fv,type){if(!fv)fv=0;if(!type)type=0;rate=rate/100;if(rate==0)return-(pv+fv)/nper;var pvif=Math.pow(1+rate,nper);var pmt=rate/(pvif-1)*-(pv*pvif+fv);if(type==1){pmt/=(1+rate);};return pmt;};root.CF_FINANCE=lib;})(this);fbuilderjQuery=(typeof fbuilderjQuery!='undefined')?fbuilderjQuery:jQuery;fbuilderjQuery['fbuilder']=fbuilderjQuery['fbuilder']||{};fbuilderjQuery['fbuilder']['modules']=fbuilderjQuery['fbuilder']['modules']||{};fbuilderjQuery['fbuilder']['modules']['financial']={'prefix':'','callback':function()
{fbuilderjQuery['fbuilder']['extend_window'](fbuilderjQuery['fbuilder']['modules']['financial']['prefix'],CF_FINANCE);},'validator':function(v)
{return(typeof v=='number')?isFinite(v):(typeof v!='undefined');}};;(function(root){var lib={},loadingFlag=false,distanceArr=[],callbacks=[];function _runCallbacks()
{var h=callbacks.length;if(h)
{for(var i=0;i<h;i++)
{callbacks[i]();}}
callbacks=[];};function _createScriptTags()
{if(!loadingFlag)
{loadingFlag=true;var script=document.createElement('script');script.type="text/javascript";script.src=((typeof window.location.protocol!='undefined')?window.location.protocol:'http:')+'//maps.google.com/maps/api/js?sensor=false&callback=CPCFF_DISTANCE_MODULE_RUNCALLBACKS';document.body.appendChild(script);}};lib.cf_distance_version='0.1';lib.DISTANCE=function(address_a,address_b,unit_system,travel_mode,form_id){var us,tm,r=0;if(typeof address_a!='undefined'&&typeof address_b!='undefined')
{address_a=(new String(address_a)).replace(/^\s+/,'').replace(/\s+$/,'');address_b=(new String(address_b)).replace(/^\s+/,'').replace(/\s+$/,'');if(address_a.length>2&&address_b.length>2)
{unit_system=(typeof unit_system!='undefined')?unit_system.toLowerCase():'km';travel_mode=(typeof travel_mode!='undefined')?travel_mode.toUpperCase():'driving';form_id=(typeof form_id!='undefined')?form_id:((typeof fbuilderjQuery.fbuilder.calculator.form_id!='undefined')?fbuilderjQuery.fbuilder.calculator.form_id:'');for(var i in distanceArr)
{if(distanceArr[i]['a']==address_a&&distanceArr[i]['b'])return distanceArr[i]['distance'];}
if(typeof google=='undefined'||google['maps']==null)
{callbacks.push((function(address_a,address_b,unit_system,travel_mode,form_id)
{return function(){DISTANCE(address_a,address_b,unit_system,travel_mode,form_id)};})(address_a,address_b,unit_system,travel_mode,form_id));_createScriptTags();return;}
us=(unit_system=='mi')?google.maps.UnitSystem.IMPERIAL:google.maps.UnitSystem.METRIC;switch(travel_mode.toUpperCase())
{case'BICYCLING':tm=google.maps.TravelMode.BICYCLING;break;case'TRANSIT':tm=google.maps.TravelMode.TRANSIT;break;case'WALKING':tm=google.maps.TravelMode.WALKING;break;default:tm=google.maps.TravelMode.DRIVING;break;}
var directionsService=new google.maps.DirectionsService(),request={origin:address_a,destination:address_b,travelMode:tm,unitSystem:us};directionsService.route(request,(function(form_id)
{return function(response,status)
{if(status==google.maps.DirectionsStatus.OK)
{r=response.routes[0].legs[0].distance.text;distanceArr.push({'a':response['request']['origin'],'b':response['request']['destination'],'distance':r});fbuilderjQuery.fbuilder.calculator.defaultCalc('#'+form_id,false);}};})(form_id));}}
return r;};lib.CPCFF_DISTANCE_MODULE_RUNCALLBACKS=function(){_runCallbacks();};root.CF_DISTANCE=lib;})(this);fbuilderjQuery=(typeof fbuilderjQuery!='undefined')?fbuilderjQuery:jQuery;fbuilderjQuery['fbuilder']=fbuilderjQuery['fbuilder']||{};fbuilderjQuery['fbuilder']['modules']=fbuilderjQuery['fbuilder']['modules']||{};fbuilderjQuery['fbuilder']['modules']['distance']={'prefix':'','callback':function()
{fbuilderjQuery['fbuilder']['extend_window'](fbuilderjQuery['fbuilder']['modules']['distance']['prefix'],CF_DISTANCE);}};$.fbuilder['controls']=(typeof $.fbuilder['controls']!='undefined')?$.fbuilder['controls']:{};$.fbuilder['forms']=(typeof $.fbuilder['forms']!='undefined')?$.fbuilder['forms']:{};$.fbuilder['htmlEncode']=function(value)
{value=$('<div/>').text(value).html()
value=value.replace(/&/g,'&amp;').replace(/"/g,"&quot;");return value;};$.fbuilder['escape_symbol']=function(value)
{return value.replace(/([\^\$\-\.\,\[\]\(\)\/\\\*\?\+\!\{\}])/g,"\\$1");};$.fbuilder['parseValStr']=function(value)
{if(typeof value=='undefined'||value==null)value='';value=$.trim(value.replace(/'/g,"\\'").replace(/\$/g,'\\$'));return(value==parseFloat(value))?value:'"'+value+'"';};$.fbuilder['parseVal']=function(value,thousandSeparator,decimalSymbol)
{if(typeof value=='undefined'||value==null||value=='')return 0;value+='';thousandSeparator=$.fbuilder.escape_symbol((typeof thousandSeparator=='undefined')?',':thousandSeparator);decimalSymbol=(typeof decimalSymbol=='undefined'||/^\s*$/.test(decimalSymbol))?'.':decimalSymbol;var correction=new RegExp(((/^\s*$/.test(thousandSeparator))?',':thousandSeparator)+('\(\\d{1,2}\)$')),correctionReplacement=decimalSymbol+'$1';thousandSeparator=new RegExp(thousandSeparator,'g');decimalSymbol=new RegExp($.fbuilder.escape_symbol(decimalSymbol),'g');var t=value.replace(correction,correctionReplacement).replace(thousandSeparator,'').replace(decimalSymbol,'.').replace(/\s/g,''),p=/[+\-]?((\d+(\.\d+)?)|(\.\d+))(?:[eE][+\-]?\d+)?/.exec(t);return(p)?p[0]*1:$.fbuilder['parseValStr'](value);};$.fn.fbuilder=function(options){var opt=$.extend({},{pub:false,identifier:"",title:""},options,true);opt.messages=$.extend({previous:"Previous",next:"Next",pageof:"Page {0} of {0}",required:"This field is required.",email:"Please enter a valid email address.",datemmddyyyy:"Please enter a valid date with this format(mm/dd/yyyy)",dateddmmyyyy:"Please enter a valid date with this format(dd/mm/yyyy)",number:"Please enter a valid number.",digits:"Please enter only digits.",maxlength:$.validator.format("Please enter no more than {0} characters"),minlength:$.validator.format("Please enter at least {0} characters."),equalTo:"Please enter the same value again.",max:$.validator.format("Please enter a value less than or equal to {0}."),min:$.validator.format("Please enter a value greater than or equal to {0}.")},opt.messages);opt.messages.max=$.validator.format(opt.messages.max);opt.messages.min=$.validator.format(opt.messages.min);$.extend($.validator.messages,opt.messages);var items=[],reloadItemsPublic=function()
{var form_tag=$("#fieldlist"+opt.identifier).closest('form');form_tag.addClass(theForm.formtemplate);if(!opt.cached)
{$("#fieldlist"+opt.identifier).html("").addClass(theForm.formlayout);$("#formheader"+opt.identifier).html(theForm.show());var page=0;$("#fieldlist"+opt.identifier).append('<div class="pb'+page+' pbreak" page="'+page+'"></div>');for(var i=0;i<items.length;i++)
{items[i].index=i;if(items[i].ftype=="fPageBreak")
{page++;$("#fieldlist"+opt.identifier).append('<div class="pb'+page+' pbreak" page="'+page+'"></div>');}
else
{$("#fieldlist"+opt.identifier+" .pb"+page).append(items[i].show());if(items[i].predefinedClick)
{$("#fieldlist"+opt.identifier+" .pb"+page).find("#"+items[i].name).attr("placeholder",items[i].predefined);$("#fieldlist"+opt.identifier+" .pb"+page).find("#"+items[i].name).attr("value","");}
if(items[i].userhelpTooltip)
{var uh=$("#fieldlist"+opt.identifier+" .pb"+page).find("#"+items[i].name).closest(".dfield");if(uh.length==0)
{uh=$("#fieldlist"+opt.identifier+" .pb"+page).find("#"+items[i].name).closest(".fields");}
uh.find(".uh").css("display","none");if(uh.find(".uh").text()!="")
{uh.attr("uh",uh.find(".uh").text());}}}}}
else
{var page=form_tag.find('.pbreak').length,i=items.length;}
if(page>0)
{if(!opt.cached)
{$("#fieldlist"+opt.identifier+" .pb"+page).addClass("pbEnd");$("#fieldlist"+opt.identifier+" .pbreak").each(function(index){var code=$(this).html();var bSubmit='';if(index==page)
{if($("#cpcaptchalayer"+opt.identifier).length&&!/^\s*$/.test($("#cpcaptchalayer"+opt.identifier).html()))
{code+='<div class="captcha">'+$("#cpcaptchalayer"+opt.identifier).html()+'</div><div class="clearer"></div>';$("#cpcaptchalayer"+opt.identifier).html("");}
if($("#cp_subbtn"+opt.identifier).html())
{bSubmit='<div class="pbSubmit">'+$("#cp_subbtn"+opt.identifier).html()+'</div>';}}
$(this).html('<fieldset><legend>'+opt.messages.pageof.replace(/\{\s*\d+\s*\}/,(index+1)).replace(/\{\s*\d+\s*\}/,(page+1))+'</legend>'+code+'<div class="pbPrevious">'+opt.messages.previous+'</div><div class="pbNext">'+opt.messages.next+'</div>'+bSubmit+'<div class="clearer"></div></fieldset>');});}
$('#fieldlist'+opt.identifier).find(".pbPrevious,.pbNext").bind("click",{'identifier':opt.identifier},function(evt){var identifier=evt.data.identifier;if(($(this).hasClass("pbPrevious"))||(($(this).hasClass("pbNext"))&&$(this).closest("form").valid()))
{var page=parseInt($(this).parents(".pbreak").attr("page"));(($(this).hasClass("pbPrevious"))?page--:page++);$("#fieldlist"+identifier+" .pbreak").css("display","none");$("#fieldlist"+identifier+" .pbreak").find(".field").addClass("ignorepb");$("#fieldlist"+identifier+" .pb"+page).css("display","block");$("#fieldlist"+identifier+" .pb"+page).find(".field").removeClass("ignorepb");if($("#fieldlist"+identifier+" .pb"+page).find(".field").length>0)
{try
{$("#fieldlist"+identifier+" .pb"+page).find(".field")[0].focus();}
catch(e){}}}
else
{$(this).closest("form").validate().focusInvalid();}
return false;});}
else
{if(!opt.cached)
{if($("#cpcaptchalayer"+opt.identifier).length&&!/^\s*$/.test($("#cpcaptchalayer"+opt.identifier).html()))
{$("#fieldlist"+opt.identifier+" .pb"+page).append('<div class="captcha">'+$("#cpcaptchalayer"+opt.identifier).html()+'</div>');$("#cpcaptchalayer"+opt.identifier).html("");}
if($("#cp_subbtn"+opt.identifier).html())
{$("#fieldlist"+opt.identifier+" .pb"+page).append('<div class="pbSubmit">'+$("#cp_subbtn"+opt.identifier).html()+'</div>');}}}
if(!opt.cached&&opt.setCache)
{var url=document.location.href,data={'cffaction':'cff_cache','cache':form_tag.html().replace(/\n+/g,''),'form':form_tag.find('[name="cp_calculatedfieldsf_id"]').val()};$.post(url,data,function(data){if(typeof console!='undefined')console.log(data);});}
$(document).on('click','#fbuilder .captcha img',function(){var e=$(this);e.attr('src',e.attr('src').replace(/&\d+$/,'')+'&'+Math.floor(Math.random()*1000));});$(form_tag).find('.captcha img').click();$('#fieldlist'+opt.identifier).find(".pbSubmit").bind("click",{'identifier':opt.identifier},function(evt)
{$(this).closest("form").submit();});if(i>0)
{theForm.after_show(opt.identifier);for(var i=0;i<items.length;i++)
{items[i].after_show();}
$.fbuilder.showHideDep({'formIdentifier':opt.identifier,'throwEvent':true});$('#fieldlist'+opt.identifier).find(".depItemSel,.depItem").bind("change",{'identifier':opt.identifier},function(evt)
{$.fbuilder.showHideDep({'formIdentifier':evt.data.identifier,'throwEvent':true});});try
{$("#fbuilder"+opt.identifier).tooltip({show:false,hide:false,tooltipClass:"uh-tooltip",position:{my:"left top",at:"left bottom+5",collision:"none"},items:"[uh]",content:function(){return $(this).attr("uh");}});}catch(e){}}
$("#fieldlist"+opt.identifier+" .pbreak:not(.pb0)").find(".field").addClass("ignorepb");};var fform=function(){};$.extend(fform.prototype,{title:"Untitled Form",description:"This is my form. Please fill it out. It's awesome!",formlayout:"top_aligned",formtemplate:"",evalequations:1,autocomplete:1,show:function(){return'<div class="fform" id="field"><h1>'+this.title+'</h1><span>'+this.description+'</span></div>';},after_show:function(id){$('#cp_calculatedfieldsf_pform'+id).attr('data-evalequations',this.evalequations).attr('autocomplete',((this.autocomplete)?'on':'off'));$('#cp_calculatedfieldsf_pform'+id).find('input,select').blur(function(){try{$(this).valid();}catch(e){};});}});var theForm,ffunct={getItem:function(name)
{for(var i in items)
{if(items[i].name==name)
{return items[i];}}
return false;},getItems:function()
{return items;},loadData:function(f)
{var d=window[f];if(typeof d!='undefined')
{if(typeof d=='object'&&(typeof d.nodeType!=='undefined'||d instanceof jQuery)){d=jQuery.parseJSON(jQuery(d).val());}
else if(typeof d=='string'){d=jQuery.parseJSON(d);}
if(d.length==2)
{this.formId=d[1]['formid'];items=[];for(var i=0;i<d[0].length;i++)
{var obj=eval("new $.fbuilder.controls['"+d[0][i].ftype+"']();");obj=$.extend(true,{},obj,d[0][i]);obj.name=obj.name+opt.identifier;obj.form_identifier=opt.identifier;obj.init();items[items.length]=obj;}
theForm=new fform();theForm=$.extend(theForm,d[1][0]);opt.cached=(typeof d[1]['cached']!='undefined'&&d[1]['cached'])?true:false;opt.setCache=(!this.cached&&typeof d[1]['setCache']!='undefined'&&d[1]['setCache'])?true:false;reloadItemsPublic();}
$.fbuilder.cpcff_load_defaults(opt);}}};$.fbuilder['forms'][opt.identifier]=ffunct;this.fBuild=ffunct;return this;};$.fbuilder['showSettings']={formlayoutList:[{id:"top_aligned",name:"Top Aligned"},{id:"left_aligned",name:"Left Aligned"},{id:"right_aligned",name:"Right Aligned"}]};$.fbuilder.controls['ffields']=function(){};$.extend($.fbuilder.controls['ffields'].prototype,{form_identifier:"",name:"",shortlabel:"",index:-1,ftype:"",userhelp:"",userhelpTooltip:false,csslayout:"",init:function(){},show:function()
{return'Not available yet';},after_show:function(){},val:function(){var e=$("[id='"+this.name+"']:not(.ignore)");if(e.length)
{return $.fbuilder.parseVal($.trim(e.val()));}
return 0;},setVal:function(v)
{$("[id='"+this.name+"']").val(v);}});$.fbuilder['showHideDep']=function(configObj)
{if(typeof configObj['formIdentifier']!=='undefined')
{var identifier=configObj['formIdentifier'];if(typeof $.fbuilder['forms'][identifier]!='undefined')
{var toShow=[],toHide=[],items=$.fbuilder['forms'][identifier].getItems();for(var i=0,h=items.length;i<h;i++)
{if(typeof items[i]['showHideDep']!='undefined')
{items[i]['showHideDep'](toShow,toHide);}}
if(typeof configObj['throwEvent']=='undefined'||configObj['throwEvent'])
{$(document).trigger('showHideDepEvent',$.fbuilder['forms'][identifier]['formId']);}}}};$.fbuilder['cpcff_load_defaults']=function(o)
{var $=fbuilderjQuery,id,item,form_data,form_obj;if(typeof cpcff_default!='undefined')
{id=o.identifier.replace(/[^\d]/g,'');if(typeof cpcff_default[id]!='undefined')
{form_data=cpcff_default[id];id='_'+id;form_obj=$.fbuilder['forms'][id];for(var field_id in form_data)
{item=form_obj.getItem(field_id+id);if(typeof item['setVal']!='undefined')item.setVal(form_data[field_id]);}
$.fbuilder.showHideDep({'formIdentifier':o.identifier,'throwEvent':true});}}};$.fbuilder.controls['ftext']=function(){};$.extend($.fbuilder.controls['ftext'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Untitled",ftype:"ftext",predefined:"",predefinedClick:false,required:false,size:"medium",minlength:"",maxlength:"",equalTo:"",show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" minlength="'+(this.minlength)+'" maxlength="'+$.fbuilder.htmlEncode(this.maxlength)+'" '+((this.equalTo!="")?"equalTo=\"#"+$.fbuilder.htmlEncode(this.equalTo+this.form_identifier)+"\"":"")+' class="field '+this.size+((this.required)?" required":"")+'" type="text" value="'+$.fbuilder.htmlEncode(this.predefined)+'"/><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)'),r=0;if(e.length)r=$.fbuilder.parseValStr(e.val());return(r!='""')?r:0;}});$.fbuilder.controls['fcurrency']=function(){};$.extend($.fbuilder.controls['fcurrency'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Currency",ftype:"fcurrency",predefined:"",predefinedClick:false,required:false,size:"small",readonly:false,currencyText:"USD",thousandSeparator:",",centSeparator:".",min:"",max:"",formatDynamically:false,getFormattedValue:function(value)
{this.centSeparator=$.trim(this.centSeparator);if(/^\s*$/.test(this.centSeparator))
{this.centSeparator='.';}
var v=$.trim(value);v=v.replace(new RegExp($.fbuilder['escape_symbol'](this.currencySymbol),'g'),'').replace(new RegExp($.fbuilder['escape_symbol'](this.currencyText),'g'),'');v=$.fbuilder.parseVal(v,this.thousandSeparator,this.centSeparator);if(!isNaN(v))
{v=v.toString();var parts=v.toString().split("."),counter=0,str='';if(!/^\s*$/.test(this.thousandSeparator))
{for(var i=parts[0].length-1;i>=0;i--){counter++;str=parts[0][i]+str;if(counter%3==0&&i!=0)str=this.thousandSeparator+str;}
parts[0]=str;}
if(typeof parts[1]!='undefined'&&parts[1].length==1)
{parts[1]+='0';}
if(/^\s*$/.test(this.centSeparator))
{this.centSeparator='.';}
return this.currencySymbol+parts.join(this.centSeparator)+this.currencyText;}
else
{return value;}},show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input '+((this.readonly)?'READONLY':'')+' id="'+this.name+'" name="'+this.name+'" class="field '+this.dformat+' '+this.size+((this.required)?" required":"")+'" type="text" value="'+$.fbuilder.htmlEncode(this.getFormattedValue(this.predefined))+'" '+((!/^\s*$/.test(this.min))?'min="'+$.fbuilder.parseVal(this.min,this.thousandSeparator,this.centSeparator)+'" ':'')+((!/^\s*$/.test(this.max))?' max="'+$.fbuilder.parseVal(this.max,this.thousandSeparator,this.centSeparator)+'" ':'')+' /><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},after_show:function()
{if(this.formatDynamically)
{var me=this;$(document).on('change','[name="'+me.name+'"]',function(){this.value=me.getFormattedValue(this.value);});}
if(typeof $['validator']!='undefined')
{$.validator.addMethod('min',function(value,element,param)
{var e=element;if(element.id.match(/_\d+$/))
{e=$.fbuilder['forms'][element.id.match(/_\d+$/)[0]].getItem(element.name)}
else if(typeof $.fbuilder['forms']!='undefined'&&typeof $.fbuilder['forms']['']!='undefined')
{e=$.fbuilder['forms'][''].getItem(element.name)}
var thousandSeparator=(typeof e.thousandSeparator!='undefined')?e.thousandSeparator:'',centSymbol=(typeof e.centSeparator!='undefined'&&$.trim(e.centSeparator))?e.centSeparator:'.';return this.optional(element)||$.fbuilder.parseVal(value,thousandSeparator,centSymbol)>=param;});$.validator.addMethod('max',function(value,element,param)
{var e=element;if(element.id.match(/_\d+$/))
{e=$.fbuilder['forms'][element.id.match(/_\d+$/)[0]].getItem(element.name)}
else if(typeof $.fbuilder['forms']!='undefined'&&typeof $.fbuilder['forms']['']!='undefined')
{e=$.fbuilder['forms'][''].getItem(element.name)}
var thousandSeparator=(typeof e.thousandSeparator!='undefined')?e.thousandSeparator:'',centSymbol=(typeof e.centSeparator!='undefined'&&$.trim(e.centSeparator))?e.centSeparator:'.';return this.optional(element)||$.fbuilder.parseVal(value,thousandSeparator,centSymbol)<=param;});}},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)
{var v=$.trim(e.val());v=v.replace(new RegExp($.fbuilder['escape_symbol'](this.currencySymbol),'g'),'').replace(new RegExp($.fbuilder['escape_symbol'](this.currencyText),'g'),'');return $.fbuilder.parseVal(v,this.thousandSeparator,this.centSeparator);}
return 0;}});$.fbuilder.controls['fnumber']=function(){};$.extend($.fbuilder.controls['fnumber'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Number",ftype:"fnumber",predefined:"",predefinedClick:false,required:false,size:"small",thousandSeparator:"",decimalSymbol:".",min:"",max:"",dformat:"digits",formats:new Array("digits","number"),show:function()
{var _type=(this.dformat=='digits'||(/^\s*$/.test(this.thousandSeparator)&&/^\s*\.\s*$/.test(this.decimalSymbol)))?'number':'text';return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" '+((!/^\s*$/.test(this.min))?'min="'+$.fbuilder.parseVal(this.min,this.thousandSeparator,this.decimalSymbol)+'" ':'')+((!/^\s*$/.test(this.max))?' max="'+$.fbuilder.parseVal(this.max,this.thousandSeparator,this.decimalSymbol)+'" ':'')+' class="field '+this.dformat+' '+this.size+((this.required)?" required":"")+'" type="'+_type+'" value="'+$.fbuilder.htmlEncode(this.predefined)+'"/><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},after_show:function()
{if(typeof $['validator']!='undefined')
{$.validator.addMethod('number',function(value,element)
{var e=element;if(element.id.match(/_\d+$/))
{e=$.fbuilder['forms'][element.id.match(/_\d+$/)[0]].getItem(element.name)}
else if(typeof $.fbuilder['forms']!='undefined'&&typeof $.fbuilder['forms']['']!='undefined')
{e=$.fbuilder['forms'][''].getItem(element.name)}
var thousandSeparator=(typeof e.thousandSeparator!='undefined')?e.thousandSeparator:'',decimalSymbol=(typeof e.decimalSymbol!='undefined'&&$.trim(e.decimalSymbol))?e.decimalSymbol:'.';var regExp=new RegExp('^-?(?:\\d+|\\d{1,3}(?:'+$.fbuilder.escape_symbol(thousandSeparator)+'\\d{3})+)?(?:'+$.fbuilder.escape_symbol(decimalSymbol)+'\\d+)?$');return this.optional(element)||regExp.test(value);});$.validator.addMethod('min',function(value,element,param)
{var e=element;if(element.id.match(/_\d+$/))
{e=$.fbuilder['forms'][element.id.match(/_\d+$/)[0]].getItem(element.name)}
else if(typeof $.fbuilder['forms']!='undefined'&&typeof $.fbuilder['forms']['']!='undefined')
{e=$.fbuilder['forms'][''].getItem(element.name)}
var thousandSeparator=(typeof e.thousandSeparator!='undefined')?e.thousandSeparator:'',decimalSymbol=(typeof e.decimalSymbol!='undefined'&&$.trim(e.decimalSymbol))?e.decimalSymbol:'.';return this.optional(element)||$.fbuilder.parseVal(value,thousandSeparator,decimalSymbol)>=param;});$.validator.addMethod('max',function(value,element,param)
{var e=element;if(element.id.match(/_\d+$/))
{e=$.fbuilder['forms'][element.id.match(/_\d+$/)[0]].getItem(element.name)}
else if(typeof $.fbuilder['forms']!='undefined'&&typeof $.fbuilder['forms']['']!='undefined')
{e=$.fbuilder['forms'][''].getItem(element.name)}
var thousandSeparator=(typeof e.thousandSeparator!='undefined')?e.thousandSeparator:'',decimalSymbol=(typeof e.decimalSymbol!='undefined'&&$.trim(e.decimalSymbol))?e.decimalSymbol:'.';return this.optional(element)||$.fbuilder.parseVal(value,thousandSeparator,decimalSymbol)<=param;});}},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)
{var v=$.trim(e.val());return $.fbuilder.parseVal(v,this.thousandSeparator,this.decimalSymbol);}
return 0;}});$.fbuilder.controls['fslider']=function(){};$.extend($.fbuilder.controls['fslider'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Slider",ftype:"fslider",predefined:"",predefinedMin:"",predefinedMax:"",predefinedClick:false,size:"small",thousandSeparator:",",centSeparator:".",min:0,max:100,step:1,range:false,caption:"{0}",init:function()
{this.min=(/^\s*$/.test(this.min))?0:parseFloat($.trim(this.min));this.max=(/^\s*$/.test(this.max))?100:parseFloat($.trim(this.max));this.step=(/^\s*$/.test(this.step))?1:parseFloat($.trim(this.step));this.centSeparator=(/^\s*$/.test(this.centSeparator))?'.':$.trim(this.centSeparator);this.thousandSeparator=$.trim(this.thousandSeparator);this.predefinedMin=(/^\s*$/.test(this.predefinedMin))?this.min:Math.min(Math.max(parseFloat($.trim(this.predefinedMin)),this.min),this.max);this.predefinedMax=(/^\s*$/.test(this.predefinedMax))?this.max:Math.min(Math.max(parseFloat($.trim(this.predefinedMax)),this.min),this.max);this.predefined=(/^\s*$/.test(this.predefined))?this.min:parseFloat($.trim(this.predefined));},show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+'</label><div class="dfield slider-container"><input id="'+this.name+'" name="'+this.name+'" class="field" type="hidden" value="'+$.fbuilder.htmlEncode($.trim(this.predefined))+'"/><div id="'+this.name+'_slider" class="slider '+this.size+'"></div><div id="'+this.name+'_caption"></div><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},set_values:function()
{var me=this;function setThousandsSeparator(v)
{v=$.fbuilder.parseVal(v,me.thousandSeparator,me.centSeparator);if(!isNaN(v))
{v=v.toString();var parts=v.toString().split("."),counter=0,str='';if(!/^\s*$/.test(me.thousandSeparator))
{for(var i=parts[0].length-1;i>=0;i--)
{counter++;str=parts[0][i]+str;if(counter%3==0&&i!=0)str=me.thousandSeparator+str;}
parts[0]=str;}
if(typeof parts[1]!='undefined'&&parts[1].length==1)
{parts[1]+='0';}
return parts.join(me.centSeparator);}
else
{return v;}};if(me.range)
{var values=$('#'+me.name+'_slider').slider('values');$('#'+me.name).val('['+values[0]+','+values[1]+']');$('#'+me.name+'_caption').html(me.caption.replace(/\{\s*0\s*\}/,setThousandsSeparator(values[0])).replace(/\{\s*0\s*\}/,setThousandsSeparator(values[1])));}
else
{var value=$('#'+me.name+'_slider').slider('value');$('#'+me.name).val(value);$('#'+me.name+'_caption').html(me.caption.replace(/\{\s*0\s*\}/,setThousandsSeparator(value)));}
$('#'+me.name).change();},after_show:function()
{var me=this,opt={range:me.range,min:me.min,max:me.max,step:me.step};if(me.range)opt['values']=[me.predefinedMin,me.predefinedMax];else opt['value']=me.predefined;opt['slide']=opt['stop']=(function(e){return function(event,ui)
{if(typeof ui.value!='undefined')$(this).slider('value',ui.value);if(typeof ui.values!='undefined')$(this).slider('values',ui.values);e.set_values();}})(me);$('#'+this.name+'_slider').slider(opt);me.set_values();$('#cp_calculatedfieldsf_pform'+me.form_identifier).bind('reset',function(){$('#'+me.name+'_slider').slider(opt);me.set_values();});},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');return(e.length)?e.val():0;},setVal:function(v)
{try{v=eval(v);$('[name="'+this.name+'"]').val(v);if($.isArray(v))
{this.predefinedMin=v[0];this.predefinedMax=v[1];}
else
{this.predefined=v;}
this.after_show();}catch(err){}}});$.fbuilder.controls['femail']=function(){};$.extend($.fbuilder.controls['femail'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Email",ftype:"femail",predefined:"",predefinedClick:false,required:false,size:"medium",equalTo:"",show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" '+((this.equalTo!="")?"equalTo=\"#"+$.fbuilder.htmlEncode(this.equalTo+this.form_identifier)+"\"":"")+' class="field email '+this.size+((this.required)?" required":"")+'" type="text" value="'+$.fbuilder.htmlEncode(this.predefined)+'"/><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)return $.fbuilder.parseValStr(e.val());return'';}});$.fbuilder.controls['fdate']=function(){};$.extend($.fbuilder.controls['fdate'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Date",ftype:"fdate",predefined:"",predefinedClick:false,size:"medium",required:false,dformat:"mm/dd/yyyy",tformat:"24",showDropdown:false,dropdownRange:"-10:+10",minDate:"",maxDate:"",invalidDates:"",minHour:0,maxHour:23,minMinute:0,maxMinute:59,stepHour:1,stepMinute:1,showTimepicker:false,defaultDate:"",defaultTime:"",working_dates:[true,true,true,true,true,true,true],formats:new Array("mm/dd/yyyy","dd/mm/yyyy"),init:function()
{function checkValue(v,min,max)
{v=parseInt(v);if(isNaN(v))v=max;else if(v<min)v=min;else if(v>max)v=max;return v;}
this.minHour=checkValue(this.minHour,0,23);this.maxHour=checkValue(this.maxHour,0,23);this.minMinute=checkValue(this.minMinute,0,59);this.maxMinute=checkValue(this.maxMinute,0,59);this.stepHour=checkValue(this.stepHour,1,Math.max(1,this.maxHour-this.minHour));this.stepMinute=checkValue(this.stepMinute,1,Math.max(1,this.maxMinute-this.minMinute));this.invalidDates=this.invalidDates.replace(/\s+/g,'').match(/\d{1,2}\/\d{1,2}\/\d{4}/g);if(this.invalidDates!==null)
{for(var i=0,h=this.invalidDates.length;i<h;i++)
this.invalidDates[i]=new Date(this.invalidDates[i]);}},get_hours:function()
{var str='',i=0,h,from=(this.tformat==12)?1:this.minHour,to=(this.tformat==12)?12:this.maxHour;while((h=from+this.stepHour*i)<=to)
{if(h<10)h='0'+''+h;str+='<option value="'+h+'">'+h+'</option>';i++;}
return'<select id="'+this.name+'_hours" name="'+this.name+'_hours">'+str+'</select>:';},get_minutes:function()
{var str='',i=0,m;while((m=this.minMinute+this.stepMinute*i)<=this.maxMinute)
{if(m<10)
{m='0'+''+m;}
str+='<option value="'+m+'">'+m+'</option>';i++;}
return'<select id="'+this.name+'_minutes" name="'+this.name+'_minutes">'+str+'</select>';},get_ampm:function()
{var str='';if(this.tformat==12)
{return'<select id="'+this.name+'_ampm"><option value="am">am</option><option value="pm">pm</option></select>';}
return str;},set_date_time:function()
{var str=$('#'+this.name+'_date').val();if(this.showTimepicker)
{var h=$('#'+this.name+'_hours').val();str+=' '+((this.tformat==12&&$('#'+this.name+'_ampm').val()=='pm')?(h*1+12)%24:h)+':'+$('#'+this.name+'_minutes').val();}
$('#'+this.name).val(str).change();},show:function()
{var attr='value';if(this.predefinedClick)
{attr='placeholder';}
return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+' <span class="dformat">('+this.dformat+((this.showTimepicker)?' HH:mm':'')+')</span></label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" type="hidden" value="'+$.fbuilder.htmlEncode(this.predefined)+'"/><input id="'+this.name+'_date" name="'+this.name+'_date" class="field date'+this.dformat.replace(/\//g,"")+' '+this.size+((this.required)?" required":"")+'" type="text" '+attr+'="'+$.fbuilder.htmlEncode(this.predefined)+'"/>'+((this.showTimepicker)?' '+this.get_hours()+this.get_minutes()+' '+this.get_ampm():'')+'<span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},setDefaultDate:function()
{var me=this,p={dateFormat:me.dformat.replace(/yyyy/g,"yy"),minDate:me.minDate,maxDate:me.maxDate},dp=$("#"+me.name+"_date"),dd=(me.defaultDate!="")?me.defaultDate:((me.predefined!="")?me.predefined:new Date());dp.click(function(){$(document).click();$(this).focus();});if(me.showDropdown)
{if(me.dropdownRange.indexOf(':')==-1)me.dropdownRange='-10:+10';p=$.extend(p,{changeMonth:true,changeYear:true,yearRange:me.dropdownRange});}
p=$.extend(p,{beforeShowDay:(function(w,i){return function(d){return me.validateDate(d,w,i);};})(me.working_dates,me.invalidDates)});dp.datepicker(p);if(!me.predefinedClick)dp.datepicker("setDate",dd);if(!me.validateDate(dp.datepicker("getDate"),me.working_dates,me.invalidDates)[0])
{dp.datepicker("setDate",'');}},setDefaultTime:function()
{function setValue(f,v,m)
{v=Math.min(v*1,m*1);v=(v<10)?0+''+v:v;$('#'+f+' [value="'+v+'"]').attr('selected',true);};if(this.showTimepicker)
{var parts,time={},tmp=0;if((parts=/(\d{1,2}):(\d{1,2})/g.exec(this.defaultTime))!=null)
{time['hour']=parts[1];time['minute']=parts[2];}
else
{var d=new Date();time['hour']=d.getHours();time['minute']=d.getMinutes();}
setValue(this.name+'_hours',(this.tformat==12)?((time['hour']>12)?time['hour']-12:((time['hour']==0)?12:time['hour'])):time['hour'],(this.tformat==12)?12:this.maxHour);setValue(this.name+'_minutes',time['minute'],this.maxMinute);$('#'+this.name+'_ampm'+' [value="'+((time['hour']<12)?'am':'pm')+'"]').attr('selected',true);}},setEvents:function()
{var me=this;$(document).on('change','#'+this.name+'_date',function(){me.set_date_time();});$(document).on('change','#'+this.name+'_hours',function(){me.set_date_time();});$(document).on('change','#'+this.name+'_minutes',function(){me.set_date_time();});$(document).on('change','#'+this.name+'_ampm',function(){me.set_date_time();});$('#cp_calculatedfieldsf_pform'+me.form_identifier).bind('reset',function(){setTimeout(function(){me.setDefaultDate();me.setDefaultTime();me.set_date_time();},500);});},validateDate:function(d,w,i)
{try{if(d===null)return[false,""];if(!w[d.getDay()])return[false,""];if(i!==null)
{for(var j=0,h=i.length;j<h;j++)
{if(d.getDate()==i[j].getDate()&&d.getMonth()==i[j].getMonth()&&d.getFullYear()==i[j].getFullYear())return[false,""];}}}
catch(_err){}
return[true,""];},validateTime:function(e,i)
{if(i.showTimepicker)
{var base=e.name.replace('_date',''),h=$('#'+base+'_hours').val(),m=$('#'+base+'_minutes').val();if(i.tformat==12)
{if($('#'+base+'_ampm').val()=='pm'&&h!=12)h=h*1+12;if($('#'+base+'_ampm').val()=='am'&&h==12)h=0;}
if(h<i.minHour||h>i.maxHour)return false;}
return true;},after_show:function()
{var me=this;me.setEvents();me.setDefaultDate();me.setDefaultTime();$('#'+this.name+'_date').change();validator=function(v,e)
{try
{var p=e.name.replace('_date','').split('_'),_index=(p.length>1)?'_'+p[1]:'',item=(typeof $.fbuilder['forms']!='undefined'&&typeof $.fbuilder['forms'][_index]!='undefined')?$.fbuilder['forms'][_index].getItem(p[0]+'_'+p[1]):null,inst=$.datepicker._getInst(e),minDate=$.datepicker._determineDate(inst,$.datepicker._get(inst,'minDate'),null),maxDate=$.datepicker._determineDate(inst,$.datepicker._get(inst,'maxDate'),null),dateFormat=$.datepicker._get(inst,'dateFormat'),date=$.datepicker.parseDate(dateFormat,v,$.datepicker._getFormatConfig(inst));if(item!=null)
{return this.optional(e)||((minDate==null||date>=minDate)&&(maxDate==null||date<=maxDate)&&me.validateDate($(e).datepicker('getDate'),item.working_dates,item.invalidDates)[0]&&me.validateTime(e,item));}
return true;}
catch(er)
{return false;}};$.validator.addMethod("dateddmmyyyy",validator);$.validator.addMethod("datemmddyyyy",validator);},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)
{var v=$.trim(e.val()),d=/(\d{1,2})\/(\d{1,2})\/(\d{4})(\s(\d{1,2}):(\d{1,2}))?/.exec(v),h=0,m=0;if(d)
{if(typeof d[5]!='undefined')h=d[5];if(typeof d[6]!='undefined')m=d[6];var date=(this.dformat=='mm/dd/yyyy')?new Date(d[3],(d[1]*1-1),d[2],h,m,0,0):new Date(d[3],(d[2]*1-1),d[1],h,m,0,0);if(this.showTimepicker)
{return date.valueOf()/86400000;}
else
{return Math.ceil(date.valueOf()/86400000);}}}
return 0;},setVal:function(v)
{try
{$('[name="'+this.name+'"]').val(v);if(v.length)
{v=v.replace(/\s+/g,' ').split(' ');this.defaultDate=v[0];this.setDefaultDate();if(v.length==2)
{this.defaultTime=v[1];this.setDefaultTime();}}}catch(err){}}});$.fbuilder.controls['ftextarea']=function(){};$.extend($.fbuilder.controls['ftextarea'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Untitled",ftype:"ftextarea",predefined:"",predefinedClick:false,required:false,size:"medium",minlength:"",maxlength:"",rows:4,show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><textarea '+((!/^\s*$/.test(this.rows))?'rows='+this.rows:'')+' id="'+this.name+'" name="'+this.name+'" minlength="'+(this.minlength)+'" maxlength="'+$.fbuilder.htmlEncode(this.maxlength)+'" class="field '+this.size+((this.required)?" required":"")+'">'+this.predefined+'</textarea><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');return(e.length)?e.val().replace(/[\n\r]+/g,' '):'';}});$.fbuilder.controls['fcheck']=function(){};$.extend($.fbuilder.controls['fcheck'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Check All That Apply",ftype:"fcheck",layout:"one_column",required:false,showDep:false,show:function()
{this.choicesVal=((typeof(this.choicesVal)!="undefined"&&this.choicesVal!==null)?this.choicesVal:this.choices.slice(0));var str="";if(!(typeof(this.choicesDep)!="undefined"&&this.choicesDep!==null))
{this.choicesDep=new Array();for(var i=0;i<this.choices.length;i++)
{this.choicesDep[i]=new Array();}}
for(var i=0;i<this.choices.length;i++)
{var classDep="",attrDep="",separator="",d=this.choicesDep[i];for(var j=0;j<d.length;j++)
{if(!/^\s*$/.test(d[j]))
{classDep="depItem";attrDep+=separator+d[j];separator=",";}}
str+='<div class="'+this.layout+'"><label><input name="'+this.name+'[]" '+((classDep!="")?"dep=\""+attrDep+"\"":"")+' id="'+this.name+'" class="field '+classDep+' group '+((this.required)?" required":"")+'" value="'+$.fbuilder.htmlEncode(this.choicesVal[i])+'" vt="'+$.fbuilder.htmlEncode(this.choices[i])+'" type="checkbox" '+((this.choiceSelected[i])?"checked":"")+'/> '+this.choices[i]+'</label></div>';}
return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label>'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield">'+str+'<span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},showHideDep:function(toShow,toHide)
{var item=$('#'+this.name+'.depItem'),form_identifier=this.form_identifier;try
{if(item.length)
{var parent=item.closest('.fields');parent.find('.field').each(function()
{var item=$(this);if(item.attr('dep')&&item.attr('dep')!='')
{var d=item.attr('dep').split(',');for(i=0;i<d.length;i++)
{if(d[i]!="")
{d[i]=d[i]+form_identifier;if($.inArray(d[i],toShow)==-1)
{try
{if(item.is(':checked')&&$.inArray(item.attr('id'),toHide)==-1)
{$('#'+d[i]).closest('.fields').css('display','');$('#'+d[i]).closest('.fields').find('.field').each(function(){$(this).removeClass('ignore');});if($.inArray(d[i],toShow)==-1)
{toShow[toShow.length]=d[i];}
var index=$.inArray(d[i],toHide);if(index!=-1)
{toHide.splice(index,1);}}
else
{$('#'+d[i]).closest('.fields').css('display','none');$('#'+d[i]).closest('.fields').find('.field').each(function()
{$(this).addClass('ignore');});if($.inArray(d[i],toHide)==-1)
{toHide[toHide.length]=d[i];}}}catch(e){}}}}}});}}
catch(e){}},val:function()
{var e=$('[id="'+this.name+'"]:checked:not(.ignore)'),v=0,me=this;if(e.length)
{e.each(function(){v+=$.fbuilder.parseVal(this.value);});}
return v;},setVal:function(v)
{if(!$.isArray(v))v=[v];for(var i in v)$('[id="'+this.name+'"][vt="'+v[i]+'"]').attr('CHECKED',true);}});$.fbuilder.controls['fradio']=function(){};$.extend($.fbuilder.controls['fradio'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Select a Choice",ftype:"fradio",layout:"one_column",required:false,choiceSelected:"",showDep:false,show:function()
{this.choicesVal=((typeof(this.choicesVal)!="undefined"&&this.choicesVal!==null)?this.choicesVal:this.choices.slice(0));var str="";if(!(typeof(this.choicesDep)!="undefined"&&this.choicesDep!==null))
{this.choicesDep=new Array();for(var i=0;i<this.choices.length;i++)
{this.choicesDep[i]=new Array();}}
var classDep="";for(var i=0,h=this.choicesDep.length;i<h;i++)
{if(this.choicesDep[i].length)
{classDep="depItem";break;}}
for(var i=0;i<this.choices.length;i++)
{var attrDep="",separator="",d=this.choicesDep[i];for(var j=0;j<d.length;j++)
{if(!/^\s*$/.test(d[j]))
{attrDep+=separator+d[j];separator=",";}}
str+='<div class="'+this.layout+'"><label><input name="'+this.name+'" id="'+this.name+'" '+((attrDep!="")?"dep=\""+attrDep+"\"":"")+' class="field '+classDep+' group '+((this.required)?" required":"")+'" value="'+$.fbuilder.htmlEncode(this.choicesVal[i])+'" vt="'+$.fbuilder.htmlEncode(this.choices[i])+'" type="radio" i="'+i+'"  '+((this.choices[i]+' - '+this.choicesVal[i]==this.choiceSelected)?"checked":"")+'/> '+this.choices[i]+'</label></div>';}
return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label>'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield">'+str+'<span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},showHideDep:function(toShow,toHide)
{var item=$('#'+this.name+'.depItem'),form_identifier=this.form_identifier;try
{if(item.length)
{var parent=item.closest('.fields');parent.find('.field').each(function()
{var item=$(this);if(item.attr('dep')&&item.attr('dep')!='')
{var d=item.attr('dep').split(',');for(i=0;i<d.length;i++)
{if(d[i]!="")
{d[i]=d[i]+form_identifier;if($.inArray(d[i],toShow)==-1)
{try
{if(item.is(':checked')&&$.inArray(item.attr('id'),toHide)==-1)
{$('#'+d[i]).closest('.fields').css('display','');$('#'+d[i]).closest('.fields').find('.field').each(function(){$(this).removeClass('ignore');});if($.inArray(d[i],toShow)==-1)
{toShow[toShow.length]=d[i];}
var index=$.inArray(d[i],toHide);if(index!=-1)
{toHide.splice(index,1);}}
else
{$('#'+d[i]).closest('.fields').css('display','none');$('#'+d[i]).closest('.fields').find('.field').each(function()
{$(this).addClass("ignore");});if($.inArray(d[i],toHide)==-1)
{toHide[toHide.length]=d[i];}}}catch(e){}}}}}});}}
catch(e){}},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore):checked'),r=0;if(e.length)r=$.fbuilder.parseValStr(e.val());return(r!='""')?r:0;},setVal:function(v)
{$('[id="'+this.name+'"][vt="'+v+'"]').attr('CHECKED',true);}});$.fbuilder.controls['fdropdown']=function(){};$.extend($.fbuilder.controls['fdropdown'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Select a Choice",ftype:"fdropdown",size:"medium",required:false,choiceSelected:"",showDep:false,show:function()
{this.choicesVal=((typeof(this.choicesVal)!="undefined"&&this.choicesVal!==null)?this.choicesVal:this.choices.slice(0))
var lv=this.choicesVal,l=this.choices,str="";if(!(typeof(this.choicesDep)!="undefined"&&this.choicesDep!==null))
{this.choicesDep=new Array();for(var i=0;i<l.length;i++)
{this.choicesDep[i]=new Array();}}
var classDep="";for(var i=0,h=this.choicesDep.length;i<h;i++)
{if(this.choicesDep[i].length)
{classDep="depItem";break;}}
for(var i=0;i<l.length;i++)
{var attrDep="",separator="",d=this.choicesDep[i];for(var j=0;j<d.length;j++)
{if(!/^\s*$/.test(d[j]))
{attrDep+=separator+d[j];separator=",";}}
str+='<option '+((attrDep!="")?"dep=\""+attrDep+"\"":"")+' '+((this.choiceSelected==l[i]+' - '+lv[i])?"selected":"")+' '+((classDep!='')?'class="'+classDep+'"':'')+' value="'+$.fbuilder.htmlEncode(lv[i])+'" vt="'+$.fbuilder.htmlEncode(l[i])+'" >'+l[i]+'</option>';}
return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><select id="'+this.name+'" name="'+this.name+'" class="field '+((classDep!='')?' depItemSel ':'')+this.size+((this.required)?" required":"")+'" >'+str+'</select><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div><div class="clearer"></div></div>';},showHideDep:function(toShow,toHide)
{var item=$('#'+this.name),form_identifier=this.form_identifier;try
{if(item.find('.depItem').length)
{var id=item.attr('id');item.find('.depItem').each(function()
{var item=$(this);if(item.attr('dep')&&item.attr('dep')!='')
{var d=item.attr('dep').split(',');for(i=0;i<d.length;i++)
{if(d[i]!="")
{d[i]=d[i]+form_identifier;if($.inArray(d[i],toShow)==-1)
{try
{if(item.is(':selected')&&$.inArray(id,toHide)==-1)
{$('#'+d[i]).closest('.fields').css('display','');$('#'+d[i]).closest('.fields').find('.field').each(function(){$(this).removeClass('ignore');});if($.inArray(d[i],toShow)==-1)
{toShow[toShow.length]=d[i];}
var index=$.inArray(d[i],toHide);if(index!=-1)
{toHide.splice(index,1);}}
else
{$('#'+d[i]).closest('.fields').css('display','none');$('#'+d[i]).closest('.fields').find('.field').each(function()
{$(this).addClass("ignore");});if($.inArray(d[i],toHide)==-1)
{toHide[toHide.length]=d[i];}}}catch(e){}}}}}});}}
catch(e){}},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)'),r=0;if(e.length)r=$.fbuilder.parseValStr(e.val());return(r!='""')?r:0;},setVal:function(v)
{$('[id="'+this.name+'"] OPTION[vt="'+v+'"]').attr('SELECTED',true);}});$.fbuilder.controls['ffile']=function(){};$.extend($.fbuilder.controls['ffile'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Untitled",ftype:"ffile",required:false,size:"medium",accept:"",upload_size:"",multiple:false,show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input type="file" id="'+this.name+'" name="'+this.name+'[]" accept="'+this.accept+'" upload_size="'+this.upload_size+'" class="field '+this.size+((this.required)?" required":"")+'" '+((this.multiple)?'multiple':'')+' /><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},after_show:function()
{$.validator.addMethod("upload_size",function(value,element,params)
{return this.optional(element)||(element.files[0].size/1024<params);});}});$.fbuilder.controls['fpassword']=function(){};$.extend($.fbuilder.controls['fpassword'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Untitled",ftype:"fpassword",predefined:"",predefinedClick:false,required:false,size:"medium",minlength:"",maxlength:"",equalTo:"",show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" minlength="'+(this.minlength)+'" maxlength="'+$.fbuilder.htmlEncode(this.maxlength)+'" '+((this.equalTo!="")?"equalTo=\"#"+$.fbuilder.htmlEncode(this.equalTo+this.form_identifier)+"\"":"")+' class="field '+this.size+((this.required)?" required":"")+'" type="password" value="'+$.fbuilder.htmlEncode(this.predefined)+'"/><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)return $.fbuilder.parseValStr(e.val());return'';}});$.fbuilder.controls['fPhone']=function(){};$.extend($.fbuilder.controls['fPhone'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Phone",ftype:"fPhone",required:false,dformat:"### ### ####",predefined:"888 888 8888",show:function()
{var me=this,str="",tmp=this.dformat.split(' '),tmpv=this.predefined.split(' '),attr=(typeof this.predefinedClick!='undefined'&&this.predefinedClick)?'placeholder':'value';for(var i=0;i<tmpv.length;i++)
{if($.trim(tmpv[i])=="")
{tmpv.splice(i,1);}}
for(var i=0;i<tmp.length;i++)
{if($.trim(tmp[i])!="")
{str+='<div class="uh_phone" ><input type="text" id="'+this.name+'_'+i+'" name="'+this.name+'_'+i+'" class="field digits '+((this.required)?" required":"")+'" style="width:'+(15*$.trim(tmp[i]).length)+'px" '+attr+'="'+((tmpv[i])?tmpv[i]:"")+'" maxlength="'+$.trim(tmp[i]).length+'" minlength="'+$.trim(tmp[i]).length+'"/><div class="l">'+$.trim(tmp[i])+'</div></div>';}}
return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label for="'+this.name+'">'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><input type="hidden" id="'+this.name+'" name="'+this.name+'" class="field " />'+str+'<span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},after_show:function()
{var me=this,tmp=me.dformat.split(' ');for(var i=0,h=tmp.length;i<h;i++)
{$('#'+me.name+'_'+i).bind('change',function(){var v='';for(var i=0;i<tmp.length;i++)
{v+=$('#'+me.name+'_'+i).val();}
$('#'+me.name).val(v).change();});if(i+1<h)
{$('#'+me.name+'_'+i).bind('keyup',{'next':i+1},function(evt){var e=$(this);if(e.val().length==e.attr('maxlength'))
{e.change();$('#'+me.name+'_'+evt.data.next).focus();}});}}},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)return $.fbuilder.parseValStr(e.val());return'';},setVal:function(v)
{$('[name="'+this.name+'"]').val(v);v=$.trim(v).replace(/[^\d]/g,' ').split(' ');for(var i in v)$('[id="'+this.name+'_'+i+'"]').val(v[i]);}});$.fbuilder.controls['fCommentArea']=function(){};$.extend($.fbuilder.controls['fCommentArea'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Comments here",ftype:"fCommentArea",userhelp:"A description of the section goes here.",show:function()
{return'<div class="fields '+this.csslayout+' comment_area" id="field'+this.form_identifier+'-'+this.index+'"><label id="'+this.name+'">'+this.title+'</label><span class="uh">'+this.userhelp+'</span><div class="clearer"></div></div>';}});$.fbuilder.controls['fhidden']=function(){};$.extend($.fbuilder.controls['fhidden'].prototype,$.fbuilder.controls['ffields'].prototype,{ftype:"fhidden",title:"",predefined:"",show:function()
{return'<div class="fields" id="field'+this.form_identifier+'-'+this.index+'" style="padding:0;margin:0;border:0;width:0;height:0;overflow:hidden;"><label for="'+this.name+'">'+this.title+'</label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" type="hidden" value="'+$.fbuilder.htmlEncode(this.predefined)+'" class="field" /></div></div>';}});$.fbuilder.controls['fSectionBreak']=function(){};$.extend($.fbuilder.controls['fSectionBreak'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Section Break",ftype:"fSectionBreak",userhelp:"A description of the section goes here.",show:function()
{return'<div class="fields '+this.csslayout+' section_breaks" id="field'+this.form_identifier+'-'+this.index+'"><div class="section_break" id="'+this.name+'" ></div><label>'+this.title+'</label><span class="uh">'+this.userhelp+'</span><div class="clearer"></div></div>';}});$.fbuilder.controls['fPageBreak']=function(){};$.extend($.fbuilder.controls['fPageBreak'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Page Break",ftype:"fPageBreak",show:function()
{return'<div class="fields '+this.csslayout+' section_breaks" id="field'+this.form_identifier+'-'+this.index+'"><div class="section_break" id="'+this.name+'" ></div><label>'+this.title+'</label><span class="uh">'+this.userhelp+'</span><div class="clearer"></div></div>';}});$.fbuilder.controls['fsummary']=function(){};$.extend($.fbuilder.controls['fsummary'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Summary",ftype:"fsummary",fields:"",titleClassname:"summary-field-title",valueClassname:"summary-field-value",fieldsArray:[],show:function()
{var me=this,p=$.trim(me.fields.replace(/\,+/g,',')).split(','),l=p.length;if(l)
{var str='<div class="fields '+me.csslayout+'" id="field'+me.form_identifier+'-'+me.index+'">'+((!/^\s*$/.test(me.title))?'<h2>'+me.title+'</h2>':'')+'<div id="'+me.name+'">';for(var i=0;i<l;i++)
{if(!/^\s*$/.test(p[i]))
{p[i]=$.trim(p[i]);str+='<div ref="'+p[i]+me.form_identifier+'" class="cff-summary-item"><span class="'+me.titleClassname+' cff-summary-title"></span><span class="'+me.valueClassname+' cff-summary-value"></span></div>';}}
str+='</div></div>';return str;}},after_show:function(){var me=this,p=$.trim(me.fields.replace(/\,+/g,',')).split(','),l=p.length;if(l)
{for(var i=0;i<l;i++)
{if(!/^\s*$/.test(p[i]))
{p[i]=$.trim(p[i]);me.fieldsArray.push(p[i]+me.form_identifier);$(document).on('change','#'+p[i]+me.form_identifier,function(){me.update();});}}
$(document).on('showHideDepEvent',function(evt,form_identifier)
{me.update();});$('#cp_calculatedfieldsf_pform'+me.form_identifier).bind('reset',function(){setTimeout(function(){me.update();},10);});}},update:function()
{for(var j=0,k=this.fieldsArray.length;j<k;j++)
{var i=this.fieldsArray[j],e=$('[id="'+i+'"]'),tt=$('[ref="'+i+'"]');if(e.length&&tt.length)
{var t=$('#'+i).closest('.fields').find('label:first').text(),v=[];e.each(function(){var e=$(this);if(/(checkbox|radio)/i.test(e.attr('type'))&&!e.is(':checked'))
{return;}
else if(e[0].tagName=='SELECT')
{v.push($(e[0].options[e[0].selectedIndex]).attr('vt'));}
else
{if(e.attr('vt'))
{v.push(e.attr('vt'));}
else
{var c=$('[id="'+i+'_caption"]');v.push((c.length&&!/^\s*$/.test(c.html()))?c.html():e.val());}}});tt.find('.cff-summary-title').html((/^\s*$/.test(t))?'':t);tt.find('.cff-summary-value').html(v.join(', '));if(e.hasClass('ignore'))
{tt.hide();}
else
{tt.show();}}}}});$.fbuilder.controls['datasource']=function(){};$.fbuilder.controls['datasource'].prototype={isDataSource:true,active:'',list:{'database':{cffaction:'get_data_from_database'},'posttype':{cffaction:'get_posts'},'taxonomy':{cffaction:'get_taxonomies'},'user':{cffaction:'get_users'},'csv':{csvData:{text:0,value:0,fields:[],rows:[]},getData:function(callback)
{var obj={data:[]};for(var i in this.csvData.rows)
{var text=this.csvData.text,value=this.csvData.value;if(typeof this.csvData.rows[i].length=='undefined')
{text=this.csvData.fields[text];value=this.csvData.fields[value];}
obj.data.push({text:this.csvData.rows[i][text],value:this.csvData.rows[i][value]});}
callback(obj);}}},getData:function(callback)
{var me=this,obj=me.list[me.active];if(typeof me.list[me.active]['getData']!='undefined')
{obj.getData(callback);if($('[id="'+me.name+'"]').closest('.pbreak:hidden').length)
{$('[id="'+me.name+'"]').addClass('ignorepb');}}
else
{var url=document.location.href,data={cffaction:obj.cffaction,form:obj.form,field:me.name.replace(me.form_identifier,''),vars:{}};if(typeof obj.vars!='undefined')
{if(!me.replaceVariables(obj.vars,data['vars']))return;}
if(typeof me.ajaxConnect!='undefined')me.ajaxConnect.abort();me.ajaxConnect=$.ajax({dataType:'json',url:url,cache:false,data:data,success:(function(me){return function(data){callback(data);if($('[id="'+me.name+'"]').closest('.pbreak:hidden').length)
{$('[id="'+me.name+'"]').addClass('ignorepb');}};})(me)});}},replaceVariables:function(vars,_rtn)
{var me=this,field,formId=me.form_identifier,id,isValid=true,tmpArr=[],val;for(var i=0,h=vars.length;i<h;i++)
{id=vars[i]+formId;field=$.fbuilder['forms'][formId].getItem(id);if(typeof field!='undefined'&&field!=false)
{val=field.val();if($('#'+id).val()=='')isValid=false;if((typeof me.hasBeenPutRelationHandles=='undefined'||!me.hasBeenPutRelationHandles)&&$.inArray(id,tmpArr)==-1)
{$(document).on('change','#'+id,function(){me.after_show();});}}
else
{val=(typeof window[vars[i]]!='undefined')?window[vars[i]]:'';}
_rtn[vars[i]]=(val+'').replace(/^['"]+/,'').replace(/['"]+$/,'');}
me.hasBeenPutRelationHandles=true;return isValid;}};$.fbuilder.controls['ftextds']=function(){};$.extend($.fbuilder.controls['ftextds'].prototype,$.fbuilder.controls['ftext'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"ftextds",show:function()
{return $.fbuilder.controls['ftext'].prototype.show.call(this);},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var v='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{v=data.data[0]['value'];}}
$('#'+me.name).val(v).change();});}});$.fbuilder.controls['femailds']=function(){};$.extend($.fbuilder.controls['femailds'].prototype,$.fbuilder.controls['femail'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"femailds",show:function()
{return $.fbuilder.controls['femail'].prototype.show.call(this);},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var v='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{v=data.data[0]['value'];}}
$('#'+me.name).val(v).change();});}});$.fbuilder.controls['ftextareads']=function(){};$.extend($.fbuilder.controls['ftextareads'].prototype,$.fbuilder.controls['ftextarea'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"ftextareads",show:function()
{return $.fbuilder.controls['ftextarea'].prototype.show.call(this);},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var v='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{v=data.data[0]['value'];}}
$('#'+me.name).val(v).change();});}});$.fbuilder.controls['fcheckds']=function(){};$.extend($.fbuilder.controls['fcheckds'].prototype,$.fbuilder.controls['fcheck'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fcheckds",show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label>'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var str='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{for(var i=0,h=data.data.length;i<h;i++)
{var e=data.data[i];str+='<div class="'+this.layout+'"><label><input name="'+me.name+'[]" id="'+me.name+'" class="field group '+((me.required)?" required":"")+'" value="'+$.fbuilder.htmlEncode(e.value)+'" vt="'+$.fbuilder.htmlEncode(e.text)+'" type="checkbox" /> '+e.text+'</label></div>';}}
$('#field'+me.form_identifier+'-'+me.index+' .dfield').html(str);$('#'+me.name).change();});}});$.fbuilder.controls['fradiods']=function(){};$.extend($.fbuilder.controls['fradiods'].prototype,$.fbuilder.controls['fradio'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fradiods",show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><label>'+this.title+''+((this.required)?"<span class='r'>*</span>":"")+'</label><div class="dfield"><span class="uh">'+this.userhelp+'</span></div><div class="clearer"></div></div>';},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var str='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{for(var i=0,h=data.data.length;i<h;i++)
{var e=data.data[i];str+='<div class="'+me.layout+'"><label><input name="'+me.name+'" id="'+me.name+'" class="field group '+((me.required)?" required":"")+'" value="'+$.fbuilder.htmlEncode(e.value)+'" vt="'+$.fbuilder.htmlEncode(e.text)+'" type="radio" i="'+i+'" /> '+e.text+'</label></div>';}}
$('#field'+me.form_identifier+'-'+me.index+' .dfield').html(str);$('#'+me.name).change();});}});$.fbuilder.controls['fPhoneds']=function(){};$.extend($.fbuilder.controls['fPhoneds'].prototype,$.fbuilder.controls['fPhone'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fPhoneds",show:function()
{return $.fbuilder.controls['fPhone'].prototype.show.call(this);},after_show:function()
{var me=this;$.fbuilder.controls['fPhone'].prototype.after_show.call(me);$.fbuilder.controls['datasource'].prototype.getData.call(me,function(data)
{var p=$.trim(me.dformat.replace(/[^\s#]/g,'').replace(/\s+/g,' ')).split(' '),h=p.length,e=[];if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{var v=data.data[0].value.replace(/\s+/,''),r='',vArr;for(var i=0;i<h;i++){r+='(.{'+p[i].length+'})';}
vArr=(new RegExp(r)).exec(v);if(vArr){e=vArr.slice(1);}}}
for(var i=0;i<h;i++)
{$('#'+me.name+'_'+i).val((typeof e[i]!='undefined')?e[i]:'').change();}});}});$.fbuilder.controls['fdropdownds']=function(){};$.extend($.fbuilder.controls['fdropdownds'].prototype,$.fbuilder.controls['fdropdown'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fdropdownds",show:function()
{this.choices=[];return $.fbuilder.controls['fdropdown'].prototype.show.call(this);},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var str='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{var t,v;for(var i=0,h=data.data.length;i<h;i++)
{v=((typeof data.data[i]['value']!='undefined')?data.data[i]['value']:'');t=((typeof data.data[i]['text']!='undefined')?data.data[i]['text']:v);str+='<option value="'+$.fbuilder.htmlEncode(v)+'" vt="'+$.fbuilder.htmlEncode(t)+'">'+t+'</option>';}}
$('#'+me.name).html(str).change();});}});$.fbuilder.controls['fhiddends']=function(){};$.extend($.fbuilder.controls['fhiddends'].prototype,$.fbuilder.controls['fhidden'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fhiddends",show:function()
{return $.fbuilder.controls['fhidden'].prototype.show.call(this);},after_show:function()
{var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var v='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{v=data.data[0]['value'];}}
$('#'+me.name).val(v).change();});}});$.fbuilder.controls['fcontainer']=function(){};$.fbuilder.controls['fcontainer'].prototype={fields:[],columns:1,after_show:function()
{var e=$('#'+this.name),f;for(var i=0,h=this.fields.length;i<h;i++)
{f=$('#'+this.fields[i]+this.form_identifier).closest('.fields').detach();if(this.columns>1)
{f.addClass('column'+this.columns);if(i%this.columns==0)f.css('clear','left');}
f.appendTo(e);}},showHideDep:function(toShow,toHide)
{var hide=($.inArray(this.name,toHide)!=-1),index;for(var i=0,h=this.fields;i<h;i++)
{if($.inArray(this.fields[i]+this.form_identifier,toHide)==-1)
{toHide.push(this.fields[i]+this.form_identifier);index=$.inArray(d[i],toShow);if(index!=-1)
{toShow.splice(index,1);}
$('#'+this.fields[i]+this.form_identifier).closest('.fields').find('.field').each(function()
{$(this).addClass('ignore');});}}}};$.fbuilder.controls['ffieldset']=function(){};$.extend($.fbuilder.controls['ffieldset'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Untitled",ftype:"ffieldset",fields:[],columns:1,show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><FIELDSET id="'+this.name+'">'+((!/^\s*$/.test(this.title))?'<LEGEND>'+this.title+'</LEGEND>':'')+'</FIELDSET><div class="clearer"></div></div>';},after_show:function()
{$.fbuilder.controls['fcontainer'].prototype.after_show.call(this);},showHideDep:function(toShow,toHide)
{$.fbuilder.controls['fcontainer'].prototype.showHideDep.call(this,toShow,toHide);}});$.fbuilder.controls['fdiv']=function(){};$.extend($.fbuilder.controls['fdiv'].prototype,$.fbuilder.controls['ffields'].prototype,{ftype:"fdiv",fields:[],columns:1,show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><div id="'+this.name+'"></div><div class="clearer"></div></div>';},after_show:function()
{$.fbuilder.controls['fcontainer'].prototype.after_show.call(this);},showHideDep:function(toShow,toHide)
{$.fbuilder.controls['fcontainer'].prototype.showHideDep.call(this,toShow,toHide);}});$.fbuilder.controls['fMedia']=function(){};$.extend($.fbuilder.controls['fMedia'].prototype,$.fbuilder.controls['ffields'].prototype,{ftype:"fMedia",sMediaType:"image",data:{image:{sWidth:"",sHeight:"",sSrc:"",sAlt:"",sLink:"",sTarget:"",sFigcaption:""},audio:{sWidth:"",sSrc:"",sSrcAlt:"",sControls:1,sLoop:0,sAutoplay:0,sMuted:0,sPreload:"auto",sFallback:"",sFigcaption:""},video:{sWidth:"",sHeight:"",sSrc:"",sSrcAlt:"",sPoster:"",sControls:1,sLoop:0,sAutoplay:0,sMuted:0,sPreload:"auto",sFallback:"",sFigcaption:""}},_show_image:function()
{var d=this.data.image,esc=$.fbuilder.htmlEncode,a=[],l=[],r='';if($.trim(d.sWidth))a.push('width="'+esc(d.sWidth)+'"');if($.trim(d.sHeight))a.push('height="'+esc(d.sHeight)+'"');if($.trim(d.sSrc))a.push('src="'+esc(d.sSrc)+'"');if($.trim(d.sAlt))a.push('alt="'+esc(d.sAlt)+'"');if($.trim(d.sLink))
{l.push('href="'+esc(d.sLink)+'"');if($.trim(d.sTarget))l.push('target="'+esc(d.sTarget)+'"');r='<a '+l.join(' ')+' ><img '+a.join(' ')+' /></a>';}
else
{r='<img '+a.join(' ')+' />';}
return r;},_show_audio_video:function(d,isV)
{var esc=$.fbuilder.htmlEncode,a=[],t=(isV)?'video':'audio';if($.trim(d.sWidth))a.push('width="'+esc(d.sWidth)+'"');if(isV&&$.trim(d.sHeight))a.push('height="'+esc(d.sHeight)+'"');if(isV&&$.trim(d.sPoster))a.push('poster="'+esc(d.sPoster)+'"');if($.trim(d.sSrc))a.push('src="'+esc(d.sSrc)+'"');if(d.sAutoplay)a.push('autoplay');if(d.sControls)a.push('controls');if(d.sLoop)a.push('loop');if(d.sMuted)a.push('muted');a.push('preload="'+esc(d.sPreload)+'"');return'<'+t+' '+a.join(' ')+'>'+(($.trim(d.sSrcAlt))?'<source src="'+esc(d.sSrcAlt)+'" />':'')+'<p>'+d.sFallback+'</p></'+t+'>';},_show_audio:function()
{return this._show_audio_video(this.data.audio,false);},_show_video:function()
{return this._show_audio_video(this.data.video,true);},show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><div class="clearer"><div class="field" id="'+this.name+'">'+this['_show_'+this.sMediaType]()+'</div></div><span class="uh">'+this.data[this.sMediaType].sFigcaption+'</span><div class="clearer"></div></div>';}});$.fbuilder.controls['fButton']=function(){};$.extend($.fbuilder.controls['fButton'].prototype,$.fbuilder.controls['ffields'].prototype,{ftype:"fButton",sType:"button",sValue:"button",sOnclick:"",userhelp:"A description of the section goes here.",show:function()
{var esc=function(v){v=v.replace(/&lt/g,'&amp;').replace(/"/g,"&quot;").replace(/\n+/g,' ');return v;},type=this.sType,clss='';if(this.sType=='calculate')
{type='button';clss='calculate-button';}
else if(this.sType=='reset')
{clss='reset-button';}
return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><input id="'+this.name+'" type="'+type+'" value="'+esc(this.sValue)+'" class="field '+clss+'" onclick="'+esc(this.sOnclick)+'" /><span class="uh">'+this.userhelp+'</span><div class="clearer"></div></div>';},after_show:function()
{$('#'+this.name).click(function()
{var e=$(this);if(e.hasClass('calculate-button'))
{var suffix=e.attr('id').match(/_\d+$/)[0],items=$.fbuilder['forms'][suffix].getItems();$.fbuilder['calculator'].defaultCalc('#'+e.closest('form').attr('id'));for(var i=0,h=items.length;i<h;i++)
{if(items[i].ftype=='fsummary')
{items[i].update();}}}
if(e.hasClass('reset-button'))
{setTimeout(function()
{var identifier=e.closest('form').attr('id').replace(/cp_calculatedfieldsf_pform/,'');$.fbuilder['showHideDep']({'formIdentifier':identifier});var page=parseInt(e.closest('.pbreak').attr('page'));if(page)
{$("#fieldlist"+identifier+" .pbreak").css("display","none");$("#fieldlist"+identifier+" .pbreak").find(".field").addClass("ignorepb");$("#fieldlist"+identifier+" .pb0").css("display","block");if($("#fieldlist"+identifier+" .pb0").find(".field").length>0)
{$("#fieldlist"+identifier+" .pb0").find(".field").removeClass("ignorepb");try
{$("#fieldlist"+identifier+" .pb0").find(".field")[0].focus();}
catch(e){}}}},50);}});}});$.fbuilder.controls['fnumberds']=function(){};$.extend($.fbuilder.controls['fnumberds'].prototype,$.fbuilder.controls['fnumber'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fnumberds",show:function()
{return $.fbuilder.controls['fnumber'].prototype.show.call(this);},after_show:function(){var me=this;$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var v='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{v=data.data[0]['value'];}}
$('#'+me.name).val(v).change();});}});$.fbuilder.controls['fhtml']=function(){};$.extend($.fbuilder.controls['fhtml'].prototype,$.fbuilder.controls['ffields'].prototype,{ftype:"fhtml",fcontent:"",show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'"><div id="'+this.name+'" class="dfield">'+$('<div/>').html(this.fcontent).html()+'</div><div class="clearer"></div></div>';}});$.fbuilder.controls['fcurrencyds']=function(){};$.extend($.fbuilder.controls['fcurrencyds'].prototype,$.fbuilder.controls['fcurrency'].prototype,$.fbuilder.controls['datasource'].prototype,{ftype:"fcurrencyds",show:function()
{return $.fbuilder.controls['fcurrency'].prototype.show.call(this);},after_show:function(){var me=this;$.fbuilder.controls['fcurrency'].prototype.after_show.call(this);$.fbuilder.controls['datasource'].prototype.getData.call(this,function(data)
{var v='';if(typeof data.error!='undefined')
{alert(data.error);}
else
{if(data.data.length)
{v=data.data[0]['value'];}}
$('#'+me.name).val(v).change();});}});$.fbuilder.controls['fCalculated']=function(){};$.extend($.fbuilder.controls['fCalculated'].prototype,$.fbuilder.controls['ffields'].prototype,{title:"Untitled",ftype:"fCalculated",predefined:"",required:false,size:"medium",eq:"",eq_factored:"",suffix:"",prefix:"",decimalsymbol:".",groupingsymbol:"",dependencies:[{'rule':'','complex':false,'fields':['']}],readonly:true,hidefield:false,show:function()
{return'<div class="fields '+this.csslayout+'" id="field'+this.form_identifier+'-'+this.index+'" style="'+((this.hidefield)?'padding:0;margin:0;border:0;opacity:0;width:0;height:0;overflow:hidden;':'')+'"><label>'+this.title+''+((this.required)?'<span class="r">*</span>':'')+'</label><div class="dfield"><input id="'+this.name+'" name="'+this.name+'" '+((this.readonly)?' readonly ':'')+' class="codepeoplecalculatedfield field '+this.size+((this.required)?" required":"")+'" type="'+((this.hidefield)?'hidden':'text')+'" value="'+this.predefined+'"/>'+((!this.hidefield)?'<span class="uh">'+this.userhelp+'</span>':'')+'</div><div class="clearer"></div></div>';},after_show:function()
{var me=this,configuration={"suffix":me.suffix,"prefix":me.prefix,"groupingsymbol":me.groupingsymbol,"decimalsymbol":me.decimalsymbol},dependencies=[];$.each(me.dependencies,function(i,d)
{d.rule=d.rule.replace(/^\s+/,'').replace(/\s+$/,'');if(d.rule!=''&&d.fields.length){var fields=[];$.each(d.fields,function(j,f){if(f!='')
{fields.push(f);}});if(fields.length){dependencies.push({'rule':d.rule,'fields':fields});}}});me.dependencies=dependencies;if(typeof me.optimizeEq=='undefined'||!me.optimizeEq||/^\s*$/.test(me.eq_factored)||/distance/i.test(me.eq_factored))
{me.eq_factored=me.eq;}
else
{var tmp=me.eq_factored.replace(/fieldname\d+/g,1);try{eval(tmp);}catch(er)
{me.eq_factored=me.eq;}}
var eq=me.eq_factored;eq=eq.replace(/\n/g,' ').replace(/fieldname(\d+)/g,"fieldname$1"+me.form_identifier).replace(/;\s*\)/g,')').replace(/;\s*$/,'');if(!/^\s*$/.test(me.eq))
{$.fbuilder.calculator.addEquation(me.name,eq,configuration,dependencies,me.form_identifier);}
var e=$('[id="'+me.name+'"]');e.bind('calcualtedfield_change',{obj:me},function(evt){if($.fbuilder['calculator'].getDepList(evt.data.obj.name,evt.data.obj.val(),evt.data.obj.dependencies))
{$.fbuilder.showHideDep({'formIdentifier':evt.data.obj.form_identifier,'throwEvent':false});}});},showHideDep:function(toShow,toHide)
{var item=$('#'+this.name),identifier=this.form_identifier;try
{if((item.closest('#fieldlist'+identifier).length==1)&&((item.attr('dep')&&item.attr('dep')!='')||(item.attr('notdep')&&item.attr('notdep')!='')))
{var d=item.attr('dep').split(',');for(i=0;i<d.length;i++)
{if(d[i]!='')
{d[i]=d[i]+identifier;if($.inArray(d[i],toShow)==-1)
{try
{if($.inArray(item.attr('id'),toHide)==-1)
{$('#'+d[i]).closest('.fields').css('display','');$('#'+d[i]).closest('.fields').find('.field').each(function()
{$(this).removeClass('ignore');});if($.inArray(d[i],toShow)==-1)
{toShow[toShow.length]=d[i];}
var index=$.inArray(d[i],toHide);if(index!=-1)
{toHide.splice(index,1);}}
else
{$('#'+d[i]).closest('.fields').css('display','none');$('#'+d[i]).closest('.fields').find('.field').each(function()
{$(this).addClass('ignore');});if($.inArray(d[i],toHide)==-1)
{toHide[toHide.length]=d[i];}}}
catch(e){}}}}
var d=item.attr('notdep').split(',');for(i=0;i<d.length;i++)
{if(d[i]!='')
{d[i]=d[i]+identifier;if(d[i]!=''&&$.inArray(d[i],toShow)==-1)
{try
{$('#'+d[i]).closest('.fields').css('display','none');$('#'+d[i]).closest('.fields').find('.field').each(function()
{$(this).addClass('ignore');});toHide[toHide.length]=d[i];}
catch(e){}}}}}}
catch(e){}},val:function()
{var e=$('[id="'+this.name+'"]:not(.ignore)');if(e.length)
{var v=$.trim(e.val());v=v.replace(new RegExp($.fbuilder['escape_symbol'](this.prefix),'g'),'').replace(new RegExp($.fbuilder['escape_symbol'](this.suffix),'g'),'');return $.fbuilder.parseVal(v,this.groupingsymbol,this.decimalsymbol);}
return 0;}});$.fbuilder['extend_window']=function(prefix,obj)
{for(method in obj)
{window[prefix+method]=(function(m)
{return function()
{return m.obj[m.method_name].apply(m.obj,arguments);};})({"method_name":method,'obj':obj});}};$.fbuilder['calculator']=(function()
{var validators=[];if(typeof $.fbuilder['modules']!='undefined')
{var modules=$.fbuilder['modules'];for(var module in modules)
{if(typeof modules[module]['callback']!='undefined')
{modules[module]['callback']();}
if(typeof modules[module]['validator']!='undefined')
{validators.push(modules[module]['validator']);}}}
_validate_result=function(v)
{if(validators.length)
{var h=validators.length;while(h--)
{if(validators[h](v))
{return true;}}}
else
{return true;}
return false;};_calculate=function(form,eq,suffix)
{var f=$(form),_match,field_regexp=new RegExp('(fieldname\\d+'+suffix+')([\\D\\b])');obj.form_id=f.attr('id');eq='('+eq+')';while(_match=field_regexp.exec(eq))
{var field=$.fbuilder['forms'][suffix].getItem(_match[1]),v='';if(field)
{v=field.val();if($.isNumeric(v))v='('+v+')';}
eq=eq.replace(_match[0],v+''+_match[2]);}
try
{var r=eval(eq.replace(/^\(/,'').replace(/\)$/,''));return(typeof r!='undefined'&&_validate_result(r))?r:false;}
catch(e)
{return false;}};var CalcFieldClss=function(){};CalcFieldClss.prototype={enqueue_fields:[],addEquation:function(calculeated_field,equation,configuration,dependencies,form_identifier)
{var equation_result=$('[id="'+calculeated_field+'"]');if(equation_result.length)
{var form=equation_result[0].form;if(typeof form.equations=='undefined')form['equations']=[];var i,h=form.equations.length;for(i=0;i<h;i++){if(form.equations[i].result==calculeated_field)break;}
if(i==h){form.equations.push({'result':calculeated_field,'equation':equation,'conf':configuration,'dep':dependencies,'identifier':form_identifier});}}},getDepList:function(calculated_field,value,dependencies)
{var list=[],list_h=[];if(value!==false&&dependencies.length)
{for(var i=0,h=dependencies.length;i<h;i++)
{try
{var rule=dependencies[i].rule.replace(/value/gi,value);if(eval(rule))
{$.each(dependencies[i].fields,function(j,e)
{if($.inArray(e,list_h)==-1&&$.inArray(e,list)==-1)
{list.push(e);}});}
else
{list_h=list_h.concat(dependencies[i].fields);$.each(dependencies[i].fields,function(j,e)
{var j=$.inArray(e,list);if(j!=-1)list.splice(j,1);});}}
catch(e)
{continue;}}}
$('[id="'+calculated_field+'"]').attr('dep',list.join(',')).attr('notdep',list_h.join(','));return list.length||list_h.length;},defaultCalc:function(form_identifier,recalculate)
{var form=$(form_identifier),dep=false;if(form.length&&(typeof form[0].equations!='undefined'))
{var equation_object=form[0].equations;for(var i in equation_object)
{var calculated_field=$('[id="'+equation_object[i].result+'"]');if(calculated_field.length)
{var result=_calculate(form[0],equation_object[i].equation,equation_object[i].identifier);calculated_field.val((result!==false)?this.format(result,equation_object[i].conf):'');$.when(calculated_field.change()).done((function(e){e.trigger('calcualtedfield_change');})(calculated_field));}}}
$(form).trigger('cpcff_default_calc');},Calculate:function(field)
{if(field.id==undefined)return;var form=field.form,me=this;if(/(button|img)/i.test(field.tagName)||(field.type&&/(button|submit)/i.test(field.type)))
{return;}
if(form&&typeof form.equations!='undefined')
{var equations=form.equations,id=field.id,reg=new RegExp(id+'[\\D\\b]');for(var i in equations)
{if(reg.test(equations[i].equation+' '))
{var calculated_field=$('[id="'+equations[i].result+'"]');if(calculated_field.length)
{var result=_calculate(form,equations[i].equation,equations[i].identifier);calculated_field.val(((result!==false)?this.format(result,equations[i].conf):''));$.when(calculated_field.change()).done((function(e){e.trigger('calcualtedfield_change');})(calculated_field));}}}}},format:function(value,config)
{if(!/^\s*$/.test(value))
{if($.isNumeric(value)&&!/[+\-]?(?:0|[1-9]\d*)(?:\.\d*)?(?:[eE][+\-]?\d+)/.test(value))
{var symbol=(value<0)?'-':'',parts=value.toString().replace("-","").split("."),counter=0,str='';if(config.groupingsymbol)
{for(var i=parts[0].length-1;i>=0;i--){counter++;str=parts[0][i]+str;if(counter%3==0&&i!=0)str=config.groupingsymbol+str;}
parts[0]=str;}
value=symbol+parts.join(config.decimalsymbol);}
if(config.prefix)
{value=config.prefix+value;}
if(config.suffix)
{value+=config.suffix;}}
return value;},unformat:function(field)
{var escape_symbol=$.fbuilder.escape_symbol;var eq=field[0].form.equations,v=field.val();for(var i=0,h=eq.length;i<h;i++)
{if(eq[i].result==field[0].id)
{var c=eq[i].conf;if(c.prefix&&!/^\s*$/.test(c.prefix))
{v=v.replace(new RegExp("^"+escape_symbol(c.prefix)),'');}
if(c.suffix&&!/^\s*$/.test(c.suffix))
{v=v.replace(new RegExp(escape_symbol(c.suffix)+"$"),'');}
if(!/[+\-]?(?:0|[1-9]\d*)(?:\.\d*)?(?:[eE][+\-]?\d+)/.test(v))
{if(c.groupingsymbol&&!/^\s*$/.test(c.groupingsymbol))
{v=v.replace(new RegExp(escape_symbol(c.groupingsymbol),'g'),'');}
if(c.decimalsymbol&&!/^\s*$/.test(c.decimalsymbol))
{v=v.replace(new RegExp(escape_symbol(c.decimalsymbol),'g'),'.');}}}}
return v;}};var obj=new CalcFieldClss();$(document).bind('keyup change blur',function(evt)
{var evalequations=$(evt.target).closest('form').attr('data-evalequations'),t=$(evt.target);if(typeof evalequations!='undefined'&&evalequations*1==0&&!(t.hasClass('codepeoplecalculatedfield')&&evt.type=='change'))
{return;}
if(evt.type=='keyup')
{if(evt.keyCode&&(evt.keyCode>=33&&evt.keyCode<=40))
{return;}
if($.inArray(t[0],$.fbuilder['calculator']['enqueue_fields'])==-1)
{setTimeout((function(t)
{return function()
{$.fbuilder['calculator']['enqueue_fields'].splice($.inArray(t,$.fbuilder['calculator']['enqueue_fields']),1);$(t).trigger('calcualtedfield_change');obj.Calculate(t);};})(t[0]),500);}}
else
{if(t.hasClass('depItem')||(t.prop('tagName')=='INPUT'&&t.attr('type').toLowerCase()=='text'&&evt.type!='change'))
{return;}
obj.Calculate(t[0]);}});$(document).bind('showHideDepEvent',function(evt,form_identifier)
{var evalequations=$('#'+form_identifier).attr('data-evalequations');if(typeof evalequations=='undefined'||evalequations*1==1)
{obj.defaultCalc('#'+form_identifier);}});return obj;})();try{!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);}catch(err){}var fcount=1;var fnum="_"+fcount;while(eval("typeof cp_calculatedfieldsf_fbuilder_config"+fnum+" != 'undefined'")||fcount<10)
{try{var cp_calculatedfieldsf_fbuilder_config=eval("cp_calculatedfieldsf_fbuilder_config"+fnum);var f=$("#fbuilder"+fnum).fbuilder((typeof cp_calculatedfieldsf_fbuilder_config.obj=='string')?$.parseJSON(cp_calculatedfieldsf_fbuilder_config.obj):cp_calculatedfieldsf_fbuilder_config.obj);f.fBuild.loadData("form_structure"+fnum);$("#cp_calculatedfieldsf_pform"+fnum).validate({ignore:".ignore,.ignorepb",errorElement:"div",errorPlacement:function(e,element)
{if(element.hasClass('group'))
element=element.parent();e.insertBefore(element);e.addClass('message');e.css('position','absolute');e.css('left',0);e.css('top',element.parent().outerHeight(true));}});}catch(e){}
fcount++;fnum="_"+fcount;}})(fbuilderjQuery);});