/**
 *	Router/Event Classes
 *  Author: nino eclarin | neclarin@stratpoint.com
 *  Description
 *		Allows sending ajax requests to cross domain APIs 
 *		Allows custom event handling after requests fires
 *
 *	Sample usage:
 *	
 *		events.setCurrentEvent('class.callback(data)');		//set current event(callback)
 *	 	router.setMethod('get');		//set method
 *		router.setTargetUrl('user/v1/detail');		//target URL
 *		router.setParams({'user_ids':userid,'access_token':access_token});		//set parameters
 *		router.connect();		//connect
 */ 
var router = {
	method_type : "GET",
	params : {},
	target_url : "",
	resp_data : {},

	connect : function (){
		$.ajax({
			url: this.target_url,
			type: this.method_type,
			data: this.params,
			contentType: 'application/x-www-form-urlencoded',
			dataType: 'json',
			success: function(resp){
				this.resp_data = {
					success : true,
					message : "Success..!",
					data 	: resp
				}
				events.toggleEvent(resp);			
			},
			error: function(resp){
				this.resp_data = {
					success : false,
					message : "Request has encountered an error:"+resp,
					data 	: "" 
				}
				events.toggleErrorEvent(resp);			
			}
		 });//done ajax
	},
	setMethod : function(m){
		switch(m.toLowerCase()){
			case 'get' : 
				this.method_type = 'GET';
				break;
			case 'put' :
				this.method_type = 'PUT';
				break;
			case 'post' :
				this.method_type = 'POST';
				break;
			case 'delete' :
				this.method_type = 'DELETE';
				break;
			default:
				alert('Undfined method type');
		}
	},
	setTargetUrl : function(t){
		this.target_url = t;
	},
	setParams : function (p){
		this.params = p;
	}

}


/**** Add custom events after running routes.connect() *****/
var events = {
	current_event : 'login',
	error_event : 'error',
	toggleEvent : function(data){
		eval(this.current_event);
	},
	setCurrentEvent : function(evt){
		this.current_event = evt;
	},
	toggleErrorEvent : function(data){
		eval(this.error_event);
	},
	setErrorEvent : function(evt){
		this.error_event = evt;
	}
}