<?php

	class Connector{
		public function connect($url,$params){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_VERBOSE, 0);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_POST, true);

		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		    $res = curl_exec($ch);
			curl_close($ch);
			return $res;
		}
	}
	
	$config = array(
		'client_id' => 'p98mt428merh'
	);
	$baseUrl = 'http://timesheet.stratpoint.me/';

	try {
		$c=new Connector();
		if(!(isset($_POST['username']) && !empty($_POST['username']))){
			throw new Exception("Invalid/empty username", 1);
		}
		if(!(isset($_POST['password']) && !empty($_POST['password']))){
			throw new Exception("Invalid/empty username", 1);		
		}
		if(!(isset($_POST['type']) && !empty($_POST['type']))){
			throw new Exception("Invalid/empty user type", 1);		
		}

		$params = array('username'=>$_POST['username'],'password'=>$_POST['password']);
		$res = $c->connect($baseUrl.'users/login',$params);
		$res = json_decode($res);
		if(isset($res->error)){
			throw new Exception($res->error, 1);		
		}
		$user = $res->data; 	

		$params = array('user_id'=>$user->id,'client_id'=>$config['client_id']);
		$res = $c->connect($baseUrl.'oauth/request_token',$params);
		$res = json_decode($res);
		if(isset($res->error)){
			throw new Exception($res->error, 1);
		}
		//stop here
		$admin = false;
		if($user->type == 'user' && $_POST['type'] == 'admin'){
			throw new Exception("You are not permitted for this action", 1);
		}
		if($user->type == 'admin' && $_POST['type'] == 'admin'){
			$scopes = array('web.view','users.edit','users.delete','users.add','users.view','project.edit','project.delete','project.add','time_entry.edit','time_entry.delete','time_entry.add');
			$admin = true;
		} else {
			$scopes = 'web.view,mobile.view,time_entry.edit,time_entry.delete,time_entry.add,self.edit';
		}

		$params = array('user_id'=>$user->id,'client_id'=>$config['client_id'],'request_token'=>$res->data->request_token,'scopes'=>$scopes);
		$res = $c->connect($baseUrl.'oauth/access_token',$params);
		$res = json_decode($res);

		if(isset($res->error)){
			throw new Exception($res->error, 1);
		}
		setcookie('access_token',$res->data->access_token);
		setcookie('user_id',$user->id);
		setcookie('login',$user->type);
		setcookie('login_type',$_POST['type']);
		if(!$admin){
			header('Location: index.html');
		} else {
			header('Location: admin');
		}
	} catch(Exception $e) {
		header('Location: login.html?msg='.$e->getMessage());
	}



