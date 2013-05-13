(function(o,s){
	var p;
	for(p in o)
		Date.prototype[p] = o[p];
	for(p in s)
		Date[p] = s[p];
})({
	getDayOfWeek	: function(){ return Date.daysOfWeek[this.getDay()]; },
	getMonthOfYear	: function(){ return Date.monthsOfYear[this.getMonth()]; },
	//http://www.svendtofte.com/code/date_format/formatDate.js
	getGMTDifference: function(){
			// Difference to Greenwich time (GMT) in hours
			var os = Math.abs(this.getTimezoneOffset());
			var h = new String(Math.floor(os/60));
			var m = new String(os%60);
			h.length == 1 ? h = "0"+h : 1;
			m.length == 1 ? m = "0"+m : 1;
			return this.getTimezoneOffset() < 0 ? "+"+h+m : "-"+h+m;
	},
	
	isDST: function(){ return (/daylight/i).test(this.toString()); },
	
	//http://www.uic.edu/depts/accc/software/isodates/leapyear.html
	isLeapYear: function(){ var Y = this.getFullYear(); return ((Y%4)==0&&(Y%100)!=0) || ((Y%4)==0&&(Y%100)==0&&(Y%400)==0); },
	
	//www.quirksmode.org/js/week.html
	getWeekNumber: function(){
			var d = new Date( this.getFullYear(), this.getMonth(), ( this.getDate()+1 ) );
			var n = d.getTime();
			var t = new Date(this.getFullYear(),0,1,0,0,0);
			var C = t.getDay();
			while(t.getDay() != 1){ t.setDate( t.getDate()+1 ); }
			t = t.getTime();
			if (C > 3) C -= 4;
			else C += 3;
			var nw =  Math.round((((n-t)/86400000)+C)/7);
			if(nw==0) nw = 52;
			return nw;
	},
	
	getISOWeekYear: function(){
			var n = this.getWeekNumber();
			if(n==52 && this.getMonth()==0) return this.getFullYear()-1;
			else return this.getFullYear();
	},
	
	//http://javascript.about.com/library/bldayyear.htm
	getDayOfYear: function() {
			var begin = new Date(this.getFullYear(),0,1);
			return Math.ceil((this - begin) / 86400000) - 1;
	},
	
	//this may be affected by daylight savings time
	getTimezone: function(){
			var r = ( this.getTimezoneOffset() / 60 ) * -1;
			if(this.isDST()) return Date.timezonesDST[r.toString()-1];
			return Date.timezones[r.toString()];
	},
	
	getDaysInMonth: function(){
			var r = new Date(this.getFullYear(), this.getMonth(), 32);
			return ( 32 - r.getDate() );
	},
	
	//http://www.xs4all.nl/~ppk/js/beat.html
	getSwatchTime: function(){
			var off = (this.getTimezoneOffset() + 60)*60;
			var theSeconds = (this.getHours() * 3600) + 
							 (this.getMinutes() * 60) + 
							  this.getSeconds() + off;
			var beat = Math.floor(theSeconds/86.4);
			if (beat > 1000) beat -= 1000;
			if (beat < 0) beat += 1000;
			if ((String(beat)).length == 1) beat = "00"+beat;
			if ((String(beat)).length == 2) beat = "0"+beat;
			return beat;
	},
	
	getCardinalPrefix: function(){
			var d = this.getDate();
			if((d - 7) <= 0) return "First";
			if((d - 14) <= 0) return "Second";
			if((d - 21) <= 0) return "Third";
			if((d - 28) <= 0) return "Fourth";
			return "Last";
	},
	
	increaseDate: function(n){ var s = this.getDate() + n; this.setDate(s); },
	increaseMonth: function(n){ var s = this.getMonth() + n; this.setMonth(s); },
	increaseYear: function(n){ var s = this.getFullYear() + n; this.setYear(s); },
	
	format: function(str){
		if(!str) return this.toLocaleString();
		var r = null, new_str = "";
		
		for(var i=0;i<str.length;i++){
			
			switch(str.charAt(i)){
				//Day
				case 'd': r = this.getDate(); new_str += ( r > 9 ? r : '0'+r ); break;
				
				case 'D': new_str += Date.abbrDaysOfWeek[this.getDay()]; break;
				
				case 'j': new_str += this.getDate(); break;
				
				case 'l': new_str += Date.daysOfWeek[this.getDay()]; break;
				
				case 'N': r = this.getDay(); r = r > 0 ? r : 7; new_str += r; break;
				
				case 'S':	switch (this.getDate()) {
								case  1: new_str += "st"; break; 
								case  2: new_str += "nd"; break; 
								case  3: new_str += "rd"; break;
								case 21: new_str += "st"; break; 
								case 22: new_str += "nd"; break; 
								case 23: new_str += "rd"; break;
								case 31: new_str += "st"; break;
								default: new_str += "th"; break;
							} break;
				
				case 'w': new_str += this.getDay(); break;
				
				case 'z': new_str += this.getDayOfYear(); break;
				
				//week
				case 'W': new_str += this.getWeekNumber(); break;
				
				//month
				case 'F': new_str += Date.monthsOfYear[this.getMonth()]; break;
				
				case 'm': r = this.getMonth() + 1; new_str += ( r > 9 ? r : "0"+r ); break;
				
				case 'M': new_str += Date.abbrMonthsOfYear[this.getMonth()]; break;
				
				case 'n': new_str += ( this.getMonth() + 1 ); break;
				
				case 't': new_str += this.getDaysInMonth(); break;
				
				//Year
				case 'L': new_str += ( this.isLeapYear() ? "1" : "0" ); break;
				
				case 'o': new_str += this.getISOWeekYear(); break;
				
				case 'Y': new_str += this.getFullYear(); break;
				
				case 'y': r = this.getYear().toString(); new_str += r.substr( ( r.length-2 ) ); break;
				
				//Time
				case 'a': new_str += ( this.getHours() > 11 ? "pm" : "am" ); break;
				
				case 'A': new_str += ( this.getHours() > 11 ? "PM" : "AM" ); break;
				
				case 'B': new_str += this.getSwatchTime(); break;//this will take work
				
				case 'g': r = this.getHours() + 1; new_str += ( r < 13 ? r : ( r - 12 ) ); break;
				
				case 'G': new_str += this.getHours(); break;
				
				case 'h': r = this.getHours() + 1; new_str += ( r < 13 ? ( r < 10 ? "0"+r : r ) : ( ( r - 12 ) < 10 ? "0"+(r - 12) : (r - 12) ) ); break;
				
				case 'H': r = this.getHours(); new_str += ( r < 10 ? "0"+r : r ); break;
				
				case 'i': r = this.getMinutes(); new_str += ( r < 10 ? "0"+r : r ); break;
				
				case 's': r = this.getSeconds(); new_str += ( r < 10 ? "0"+r : r ); break;
				
				case 'u': new_str += this.getMilliseconds(); break;
				
				//Timezone
				case 'e': break;//this will take work
				
				case 'I': new_str += ( this.isDST() ? "1" : "0" ); break;
				
				case 'O': new_str += this.getGMTDifference(); break;
				
				case 'P': r = this.getGMTDifference(); new_str += (r.substr(0, 3) + ":" + r.substr(3, 2)); break;
				
				case 'T': new_str += this.getTimezone(); break;
				
				case 'Z': new_str += ( this.getTimezoneOffset() * 60 ); break;
				
				//Full Date/Time
				case 'c': new_str += this.format("Y-m-d\\TH:i:sP"); break;
				
				case 'r': new_str += this.format("D, d M Y H:i:s O"); break;
				
				case 'U': new_str += Math.round(this.getTime() / 1000);
				
				//my own cardinal prefix
				case 'K': new_str += this.getCardinalPrefix(); break;
				
				//Escape character
				case '\\': new_str += str.charAt(++i); break;
				default: new_str += str.charAt(i); break;
			}
			r = null;
		}
		return new_str;
	},
	
	//alias of format
	$: function(str){
		return this.format(str);
	},
	
	nextMonth: function(){
		return new Date(this.getYear(), this.getMonth()+1, this.getDate(), this.getHours(), this.getMinutes(), this.getSeconds());
	},
	
	previousMonth: function(){
		return new Date(this.getYear(), this.getMonth()-1, this.getDate(), this.getHours(), this.getMinutes(), this.getSeconds());
	},
	
	nextWeek: function(){
		return new Date(this.getTime()+(7*24*3600000));
	},
	
	previousWeek: function(){
		return new Date(this.getTime()-(7*24*3600000));
	},
	
	tommorrow: function(){
		return new Date(this.getTime()+(24*3600000));
	},
	
	yesterday: function(){
		return new Date(this.getTime()-(24*3600000));
	},
	
	beginningOfDay: function(){
		return new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
	},
	
	endOfDay: function(){
		return new Date(this.getFullYear(), this.getMonth(), this.getDate(), 23, 59, 59);
	},
	
	beginningOfWeek: function(){
		return new Date(this.getFullYear(), this.getMonth(), (this.getDate() - this.getDay()), 0, 0, 0);
	},
	
	endOfWeek: function(){
		return new Date(this.getFullYear(), this.getMonth(), (this.getDate() + (6 - this.getDay())), 23, 59, 59);
	},
	
	beginningOfMonth: function(){
		return new Date(this.getFullYear(), this.getMonth(), 1, 0, 0, 0);
	},
	
	endOfMonth: function(){
		return new Date(this.getFullYear(), this.getMonth(), this.getDaysInMonth(), 23, 59, 59);
	},
	
	d: function(){return this.$('d');},
				
	D: function(){return this.$('D');},
	
	j: function(){return this.$('j');},
	
	l: function(){return this.$('l');},
	
	N: function(){return this.$('N');},
	
	S: function(){return this.$('S');},
	
	w: function(){return this.$('w');},
	
	z: function(){return this.$('z');},
	
	//week
	W: function(){return this.$('W');},
	
	//month
	F: function(){return this.$('F');},
	
	m: function(){return this.$('m');},
	
	M: function(){return this.$('M');},
	
	n: function(){return this.$('n');},
	
	t: function(){return this.$('t');},
	
	//Year
	L: function(){return this.$('L');},
	
	o: function(){return this.$('o');},
	
	Y: function(){return this.$('Y');},
	
	y: function(){return this.$('y');},
	
	//Time
	a: function(){return this.$('a');},
	
	A: function(){return this.$('A');},
	
	B: function(){return this.$('B');},
	
	g: function(){return this.$('g');},
	
	G: function(){return this.$('G');},
	
	h: function(){return this.$('h');},
	
	H: function(){return this.$('H');},
	
	i: function(){return this.$('i');},
	
	s: function(){return this.$('s');},
	
	u: function(){return this.$('u');},
	
	//Timezone
	e: function(){return this.$('e');},
	
	I: function(){return this.$('I');},
	
	O: function(){return this.$('O');},
	
	P: function(){return this.$('P');},
	
	T: function(){return this.$('T');},
	
	Z: function(){return this.$('Z');},
	
	//Full Date/Time
	c: function(){return this.$('c');},
	
	r: function(){return this.$('r');},
	
	U: function(){return this.$('U');},
	
	//my own cardinal prefix
	K: function(){return this.$('K');}
},{
	daysOfWeek		: ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
	abbrDaysOfWeek	: ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],
	monthsOfYear	: ["January","February","March","April","May","June","July","August","September","October","November","December"],
	abbrMonthsOfYear: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"],
	timezones		: {"12":"IDLE","11":"UNK","10":"GST","9":"JST","8":"CCT","7":"UNK","6":"UNK","5":"UNK","4":"UNK","3":"BT","2":"EET","1":"CET","0":"GMT","-1":"WAT","-2":"AT","-3":"UNK","-4":"AST","-5":"EST","-6":"CST","-7":"MST","-8":"PST","-9":"YST","-10":"AHST","-11":"NT","-12":"IDLW"},
	timezonesDST	: {"12":"IDLE","11":"UNK","10":"GDT","9":"JDT","8":"CCT","7":"UNK","6":"UNK","5":"UNK","4":"UNK","3":"BT","2":"EET","1":"CET","0":"GMT","-1":"WAT","-2":"AT","-3":"UNK","-4":"ADT","-5":"EDT","-6":"CDT","-7":"MDT","-8":"PDT","-9":"YDT","-10":"ADT","-11":"NT","-12":"IDLW"},
	observesDST		: function(){
		   var r = new Date();
		   var date1 = new Date(r.getFullYear(), 0, 1, 0, 0, 0, 0);
		   var date2 = new Date(r.getFullYear(), 6, 1, 0, 0, 0, 0);
		   var temp = date1.toGMTString();
		   var date3 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
		   var temp = date2.toGMTString();
		   var date4 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
		   var hst = (date1 - date3) / (1000 * 60 * 60);
		   var hdt = (date2 - date4) / (1000 * 60 * 60);
		   return (hdt == hst);
	}/*,
	getInstance: function(){
		var x = arguments;
		switch(arguments.length){
			case 6:		return new Date(x[0], x[1], x[2], x[3], x[4], x[5]);
			case 5:		return new Date(x[0], x[1], x[2], x[3], x[4]);
			case 4:		return new Date(x[0], x[1], x[2], x[3]);
			case 3:		return new Date(x[0], x[1], x[2]);
			case 2:		return new Date(x[0], x[1]);
			case 1:		return new Date(x[0]);
			case 0: 
			default:	return new Date();
			
		}
	}*/
});

