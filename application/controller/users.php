<?php
class Users extends Kiel_Controller{


	/*
	* Always pass the args to the parent construct
	*
	*/
	public function __construct($args)
	{
		parent::__construct($args);
		$this->load_model('user_model');
	}

	/**
	* @param access_token
	* @param username
	* @param password
	* @param password_c
	* @param first_name
	* @param last_name
	* @param email
	* @return user_object || error message
	*/
	public function index_post()
	{
		$required = array('access_token','username','password','password_c','first_name','last_name','email');
		$this->required_fields($required,$this->post_args);
		$this->has_scopes(array('web.view','users.add'),$this->post_args['access_token']);

		$data = $this->post_args;

		if($data['password'] !== $data['password_c']){
			throw new Exception("Password is not the same!", 1);
		}
		if(!$this->user_model->username_exists($data['username'])){
			throw new Exception("Sadly, that username is taken. Try another one.", 1);
		}
		if(!$this->user_model->email_exists($data['email'])){
			throw new Exception("Sadly, that email is taken. Try another one.", 1);
		}

		$res = $this->user_model->add_user($data);
		if(!$res){
			throw new Exception("Something went wrong while adding data. Please try again.", 1);
		}
		$this->response(array('status'=>'Success','data'=>$res),200);

	}

	/**
	* @param access_token
	* @param user_id
	* @param password
	* @param first_name
	* @param last_name
	* @param email
	* @return user_object || error message
	*/
	public function index_put()
	{
		$this->required_fields(array('access_token','user_id'),$this->put_args);
		$this->check_access_token($this->put_args['access_token']);
		$this->has_scopes(array("web.view","self.edit"),$this->put_args['access_token']);

		$id = $this->put_args['user_id'];

		isset($this->put_args['password']) && !empty($this->put_args['password']) && $data['password'] = $this->put_args['password'];
		isset($this->put_args['title']) && !empty($this->put_args['title']) && $data['title'] = $this->put_args['title'];
		isset($this->put_args['first_name']) && !empty($this->put_args['first_name']) && $data['first_name'] = $this->put_args['first_name'];
		isset($this->put_args['last_name']) && !empty($this->put_args['last_name']) && $data['last_name'] = $this->put_args['last_name'];
		
		$res = $this->user_model->update_user($id,$data);

		if(!$res){
			throw new Exception("Something went wrong while updating data. Please try again.", 1);
		}
		$this->response(array('status'=>'Success','data'=>$res),200);
	}

	/**
	* @param username
	* @param password
	* @return user_object || error message
	*/

	public function login_post()
	{
		$required = array('username','password');
		$this->required_fields($required,$this->post_args);
		
		$user = $this->user_model->get_by_username($this->post_args['username']);
		if(!$user){
			throw new Exception("Woah..! We can't find tshat username in our database.", 1);
		}
		if($user['password'] != md5($this->post_args['password'])){
			throw new Exception("Woah..! Username and password does not match!", 1);
		}
		if($user['active'] == 0){
			throw new Exception("Woah..! User is not an active user. Verify email or contact the site administrator", 1);
		}
		unset($user['password']);
		$this->response(array('status'=>'Success','data'=>$user),200);
	}

	/**
	* @param access_token
	*/
	public function logout_post()
	{
		$this->load_model('auth_model');
		$required = array('access_token','user_id');
		$this->required_fields($required,$this->post_args);
		$this->auth_model->delete_access_token($this->post_args['user_id']);
	}
}

?>