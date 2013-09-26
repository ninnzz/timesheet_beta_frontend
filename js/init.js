$(document).ready(function() {
	var c_day,c_month,c_year;
	var mnth = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	var mclose = true;
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
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
				console.log(date);
				d = new Date(date);
				c_year = d.getFullYear();
				c_month = d.getMonth();
				c_day = d.getDate();
				document.getElementById('time-label').innerHTML = mnth[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear();
				$('#add-time').foundation('reveal', 'open',function(){mclose=false;});
				calendar.fullCalendar('unselect');
       		} 	
    	},
    	select :function (start,end,allDay){
			if(mclose){
				
				d = new Date(start);
				c_year = d.getFullYear();
				c_month = d.getMonth();
				c_day = d.getDate();
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
			calendar_util.assignTime(calEvent.project_id,'a-project');
			$('#add-time').foundation('reveal', 'open');
	        

	    },
		events: function(start, end, callback) {
	        $.ajax({
	            url: 'sdata.php',
	            dataType: 'json',
	            data: {
	                start: Math.round(start.getTime() / 1000),
	                end: Math.round(end.getTime() / 1000),
	                test: 1
	            },
	            success: function(doc) {
	               	console.log(doc);
	                var events = [];
	                	
	                for(i=0;i<doc.length;i++){
	                	doc[i]
	                	events.push({
	                		id: doc[i].id,
	                		title : doc[i].title,
	                		color: doc[i].color,
	                		details: doc[i].details,
	                		project_id: doc[i].project_id,
	                		user_id: doc[i].user_id,
	                		start: doc[i].start,
	                		end: doc[i].end,
	                		allDay: doc[i].allDay

	                	});
	                }
	                console.log(events);
	                callback(events);
	            },
	            error : function(err){
	            	console.log(err);
	            }
	        });
    	}
	});
	

	$('#left-button').sidr();
	$('#up-button').sidr();
	$('#add-time').bind('closed', function() {
  		mclose = true;
	});
});

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
	}
}


