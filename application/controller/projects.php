<?php
class Projects extends Kiel_Controller{


	/*
	* Always pass the args to the parent construct
	*
	*/
	public function __construct($args)
	{
		parent::__construct($args);
		$this->load_model('project_model');
	}

	/**
	*	@param 		name
	*	@param 		client_id
	*	@param 		access_token
	*	@return 	error || project
	*/
	public function index_post()
	{
		$required = array('client_id','name','access_token');
		$this->required_fields($required,$this->post_args);
		$this->check_access_token($this->post_args['access_token']);
		$this->has_scopes(array('web.view','project.add'),$this->post_args['access_token']);

		$data = $this->post_args;
		unset($data['access_token']);
		$res = $this->project_model->add($data);
		if($res){
			$this->response(array('status'=>'Success','data'=>$res),200);
		} else {
			throw new Exception("Hey hey... Something went wrong while adding. Please try again.", 1);
		}
	}

	/**
	*	@param 		access_token
	*	@return 	project list
	*/
	public function index_get()
	{
		$this->required_fields(array('access_token'),$this->get_args);
		$this->check_access_token($this->get_args['access_token']);

		$res = $this->project_model->get_projects();
		$this->response(array('status'=>'Success','data'=>$res),200);

	}



}

?>