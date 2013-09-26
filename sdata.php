<?php
	
	
	$evt1 = (object)array('id'=> '1',
		'title'=> 'Event1',
		'start'=> 1377765000,
		'end'=> 1377775800,
		'details'=> '',
		'project_id'=> 'g_deals',
		'user_id'=> 'ninz',
		'color'=> '#E3AAB2',
		'allDay' => false
		);

	
	
	$evt2 = (object)array('id'=> '2',
		'title'=> 'Event2',
		'start'=> 1377765000,
		'end'=> 1377775800,
		'details'=> '',
		'project_id'=> 'g_deals',
		'user_id'=> 'ninz',
		'color'=> '#E3AAB2',
		'allDay' => false
	);
	
	$evt3 = (object)array('id'=> '3',
		'title'=> 'Event3',
		'start'=> 1377765000,
		'end'=> 1377775800,
		'details'=> '',
		'project_id'=> 'g_deals',
		'user_id'=> 'ninz',
		'color'=> '#E3AAB2',
		'allDay' => false
	);
	
	$evt4 = (object)array('id'=> '4',
		'title'=> 'Event4',
		'start'=> 1377775800,
		'end'=> 1377783000,
		'details'=> '',
		'project_id'=> 'g_deals',
		'user_id'=> 'ninz',
		'color'=> '#E3AAB2',
		'allDay' => false
	);

	$evt5 = (object)array('id'=> '5',
		'title'=> 'Event5',
		'start'=> 1377783000,
		'end'=> 1377784800,
		'details'=> '',
		'project_id'=> 'pgc',
		'user_id'=> 'ninz',
		'color'=> '#CEAAE3',
		'allDay' => false
	);
	
	$evt6 = (object)array('id'=> '6',
		'title'=> 'Event6',
		'start'=> 1377784800,
		'end'=> 1377788400,
		'details'=> '',
		'project_id'=> 'g_deals',
		'user_id'=> 'ninz',
		'color'=> '#E3AAB2',
		'allDay' => false
	);
	
	$evt7 = (object)array('id'=> '7',
		'title'=> 'Event7',
		'start'=> 1377788400,
		'end'=> 1377790200,
		'details'=> '',
		'project_id'=> 'pgc',
		'user_id'=> 'ninz',
		'color'=> '#CEAAE3',
		'allDay' => false
	);


	print_r(json_encode(array($evt1,$evt2,$evt3,$evt4,$evt5,$evt6,$evt7)));



?>


