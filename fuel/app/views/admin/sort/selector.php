<?php
/*
Loading	the	list	of	all	categories	here,	since	it	doesn't
depend	on	the	post	being	created	/	edited.	(Temporary)
*/

$options = array();
foreach	($users as $user)	{
	$options[$user->id] = $user->username;
}
echo Form::select('user_id', $user_id, $options, ['class' => 'col-md-4 form-control']);
