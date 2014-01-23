var mnth = ['January','February','March','April','May','June','July','August','September','October','November','December'];
var c_day,c_month,c_year;
var is_update = false;
var current_updating_event;
function init_all(){
	var mclose = true;
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	curr_start = null;
	curr_end = null;
	var calendar = $('#calendar').fullCalendar({
		header: {
			left: null,
			center: 'title',
			right: 'prev,next today'
		},
		defaultView: 'agendaDay',
		selectable: {
				day:true,
				week:false,
				month:false
			},
		selectHelper: true,
		viewDisplay: calendar_util.handleOnchange,
		editable: true,
		dayClick: function(date, allDay, jsEvent, view) {
			if(mclose){
				console.log('month');
				current_updating_event = null;
				is_update = false;
				curr_start = date;
				d = new Date(date);
				c_year = d.getFullYear();
				c_month = d.getMonth();
				c_day = d.getDate();
				c_start = d.getHours()+':'+(d.getMinutes()==0?'00':d.getMinutes()); 
				calendar_util.assignTime(c_start,'a-start');
				document.getElementById('time-label').innerHTML = mnth[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear();
				$('#add-time').foundation('reveal', 'open',function(){mclose=false;});
				calendar.fullCalendar('unselect');
       		} 	
    	},
    	select :function (start,end,allDay){
			if(mclose){
				current_updating_event = null;
				is_update = false;
				curr_start = start;
				curr_end = end;
				d = new Date(start);
				c_year = d.getFullYear();
				c_month = d.getMonth();
				c_day = d.getDate();
				c_start = d.getHours()+':'+(d.getMinutes()==0?'00':d.getMinutes()); 
				de = new Date(end);
				c_end = de.getHours()+':'+(de.getMinutes()==0?'00':de.getMinutes()); 
				calendar_util.assignTime(c_start,'a-start');
				calendar_util.assignTime(c_end,'a-end');

				document.getElementById('time-label').innerHTML = mnth[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear();
				$('#add-time').foundation('reveal', 'open',function(){mclose=false;});
				calendar.fullCalendar('unselect');
       		} 	

    	},
		eventDragStop : function(event, jsEvent, ui, view) {
			console.log(event);
			
			//UPDATE TIME CALL AJAX HERE
			
		},
		eventResizeStop: function( event, jsEvent, ui, view ) { 
			
			console.log(event);
			//UPDATE TIME CALL AJAX HERE

		},
		eventClick: function(calEvent, jsEvent, view) {
			console.log(calEvent.details);
			is_update = true;
			current_updating_event = calEvent;
			d = new Date(calEvent.start);
			if(d.getMinutes()*1 > 10){
				mins = d.getMinutes();
			} else{
				mins = d.getMinutes()+'0';
			}
			calendar_util.assignTime(d.getHours()+':'+mins,'a-start');
			document.getElementById('time-label').innerHTML = mnth[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear();
			e = new Date(calEvent.end);
			if(e.getMinutes()*1 > 10){
				mins = e.getMinutes();
			} else{
				mins = e.getMinutes()+'0';
			}

			calendar_util.assignTime(e.getHours()+':'+mins,'a-end');
			document.getElementById('a-title').value = calEvent.title;
			document.getElementById('a-details').value = calEvent.details;
			calendar_util.assignTime(calEvent.project_id,'a-project');
			$('#add-time').foundation('reveal', 'open');
	        

	    },
		events: function(start, end, callback) {
			document.getElementById('loading').style.display = "block";
	        $.ajax({
	            url: '/time_entry/list',
	            dataType: 'json',
	            data: {
	                start: Math.round(start.getTime() / 1000),
	                end: Math.round(end.getTime() / 1000),
	                user_id: user_id,
	                access_token: access_token
	            },
	            success: function(doc) {
	               	console.log(doc.data);
	                console.log(Math.round(start.getTime() / 1000));
	                console.log(Math.round(end.getTime() / 1000));

	                var events = [];
	                	
	                for(var i=0, mx = doc.data.result.length;i<mx;i++){
	                	// doc[i]
	                	events.push({
	                		id: doc.data.result[i].id,
	                		title : doc.data.result[i].title,
	                		color: doc.data.result[i].color,
	                		details: doc.data.result[i].details,
	                		project_id: doc.data.result[i].project_id,
	                		user_id: doc.data.result[i].user_id,
	                		start: doc.data.result[i].start_time,
	                		end: doc.data.result[i].end_time,
	                		allDay: false

	                	});
	                }
					document.getElementById('loading').style.display = "none";

	                callback(events);
	            },
	            error : function(err){
					document.getElementById('loading').style.display = "none";
	            	console.log(err);
	            }
	        });
    	}
	});
	

	$('#left-button').sidr();
	$('#up-button').sidr();
	$('#add-time').bind('closed', function() {
  		mclose = true;
  		document.getElementById('error-msg').innerHTML = "";
  		document.getElementById('a-title').value = "";
  		document.getElementById('a-details').value = "";
	});
	bindEvent(document.getElementById('sbm-btn'),'click',calendar_util.inputTime);

	router.setMethod('get');
	router.setTargetUrl('/projects');
	router.setParams({access_token:access_token});
	events.setCurrentEvent('add_projects(data)');
	events.setErrorEvent('console.log(data)');
	router.connect();
}



var calendar_util = {
	curr_calendar : "#calendar",
	
	changeView: function(view){
		$('#calendar').fullCalendar('changeView', view);
	},
	navigate: function(where){
		$('#calendar').fullCalendar(where);
	},
	handleOnchange:function(view,element){
			dt = $('#calendar').fullCalendar( 'getDate' );
			vt = $('#calendar').fullCalendar( 'getView' );
			document.getElementById('title-date').innerHTML = vt.title;
	},
	assignTime: function(value,obj){
		prnt = document.getElementById(obj);
		chld = prnt.getElementsByTagName('option');
		
		for(i=0;i<chld.length;i++){
			chld[i].selected = false;
			if(chld[i].value == value){
				chld[i].selected = true;
			}
		}
	},
	inputTime: function(){
		if(curr_start != null){
			d = new Date(curr_start);
			curr_start = null;
			curr_end = null;
		} else {
			d = new Date();
		}
		c_year = d.getFullYear();
		c_month = d.getMonth();
		c_day = d.getDate();
		title = document.getElementById('a-title');
		project = document.getElementById('a-project');
		dtl = document.getElementById('a-details');
		tm = document.getElementById('a-start').value.split(':');
		strt = toTimeStamp(c_year,c_month,c_day,tm[0],tm[1],0);
		tm2 = document.getElementById('a-end').value.split(':');
		end = toTimeStamp(c_year,c_month,c_day,tm2[0],tm2[1],0);
		u_id = 'ninz';
		if(title.value != ""){
			if(strt < end){
				document.getElementById('error-msg').innerHTML = '';
				/*this is temporary*/
				
					var form_data = {
						start_time: strt,
						end_time: end,
						title: title.value,
						details: dtl.value,
						project_id: project.value,
						user_id: user_id, //temp
						color: '#CEAAE3',
						allDay: false,
						access_token:access_token

					}
					router.setMethod('post');
					router.setTargetUrl('/time_entry');
					//put access_token in cookies later
					router.setParams(form_data);
					events.setCurrentEvent('calendar_util.added(data);');
					events.setErrorEvent('console.log(data)');
					router.connect();
				/* ************************ */
			} else{
				document.getElementById('error-msg').innerHTML = '**End time is earlier than start time..!**';
			}
		} else{
			document.getElementById('error-msg').innerHTML = '**Fill out name field..!**';
		}

	},
	added: function(sdata){
		console.log(sdata.data);

		if(is_update){
			current_updating_event.title = data.title; 
			current_updating_event.start = data.start; 
			current_updating_event.end = data.end; 
			current_updating_event.details = data.details; 
			current_updating_event.project_id = data.project_id; 
			current_updating_event.user_id = data.user_id; 
			current_updating_event.color = data.color; 
			current_updating_event.allDay = data.allDay;
			$('#calendar').fullCalendar('updateEvent', current_updating_event);
			current_updating_event = null; 
		} else{
			d.title = sdata.data.title;
			d.start = sdata.data.start_time;
			d.end = sdata.data.end_time;
			d.details = sdata.data.details;
			d.project_id = sdata.data.project_id;
			d.user_id = sdata.data.user_id;
			d.color = '#CEAAE3';
			d.allDay = false;
			$('#calendar').fullCalendar('renderEvent',d,true); //make it stick
		}
		$('#add-time').foundation('reveal', 'close');
	}
}

function toTimeStamp(yr,month,day,hrs,mins,sec)
{
   dt =  new Date(yr,month,day,hrs,mins,sec);
   return dt.getTime()/1000.0;
}

function bindEvent(element, type, handler) 
{
   if(element.addEventListener) {
      element.addEventListener(type, handler, false);
   } else {
      element.attachEvent('on'+type, handler);
   }
}

function modalOpen()
{
	var date = new Date();
	d = date.getDate();
	m = date.getMonth();
	y = date.getFullYear();
	document.getElementById('time-label').innerHTML = mnth[m]+' '+d+' '+y+' (Default Today)';
	$('#add-time').foundation('reveal', 'open',function(){mclose=false;});
}

function add_projects(data)
{
	pd = document.getElementById('a-project');
	p = data.data;
	s='';
	for(i=0;i<p.result_count;i++){
		s+="<option value='"+p.result[i].id+"'>"+p.result[i].name+"</option>";
	}
	pd.innerHTML = s;
}

function selected(target,value)
{
	a=document.getElementById(target);
	for(i=0;i<a.childNodes.length;i++){
		if(a.childNodes[i].value == value){

			a.childNodes[i].selected = true;;
			break;
		}
	}
}

function logout()
{
	document.getElementById('loading').style.display = 'block';
	router.setMethod('post');
	router.setTargetUrl('/users/logout');
	router.setParams({access_token:access_token,user_id:user_id});
	events.setCurrentEvent('c_d(data)');
	events.setErrorEvent('console.log(data)');
	router.connect();
}

function c_d(d){
    document.cookie = encodeURIComponent('access_token') + "=deleted; expires=" + new Date(0).toUTCString();
    document.cookie = encodeURIComponent('user_id') + "=deleted; expires=" + new Date(0).toUTCString();
    document.cookie = encodeURIComponent('login') + "=deleted; expires=" + new Date(0).toUTCString();
    document.cookie = encodeURIComponent('login_type') + "=deleted; expires=" + new Date(0).toUTCString();
    window.location = '/timesheet/login.html';
}