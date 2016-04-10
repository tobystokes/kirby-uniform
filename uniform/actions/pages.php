<?php

/*
 * Action to log the form data to a kirby content page
 */
uniform::$actions['pages'] = function ($form, $actionOptions) {
    // the form could contain arrays which are incompatible with the template function
    $templatableItems = array_filter($form, function ($item) {
        return is_scalar($item);
    });

	// the only option is to define what template, default = 'pages'
    $options = [
        'template' => a::get($actionOptions, 'template', 'pages'),
    ];

	// add some extra contextual info
	$data = array(
		'title' => 'Entry from ' . a::get($form, '_from'),
		'date' => date('Y-m-d'),
		'time' => date('H:i'),
		'ua' => visitor::userAgent()
	);

	// add all the rest of the form fields
	foreach ($form as $key => $value) {
		$data[$key] = $value;
	}

	$newname = 'entry' . date('c');
	$success = page('form')->children()->create($newname, 'entry', $data);

	if ($success === false)
	{
		return array(
			'success' => false,
			'message' => l::get('uniform-log-error')
		);
	}
	else
	{
		return array(
			'success' => true,
			'message' => l::get('uniform-log-success')
		);
	}

};
