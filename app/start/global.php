<?php

use Monolog\Handler\ChromePHPHandler;
/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',
	app_path().'/library'

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

$monolog = Log::getMonolog();

$monolog->pushHandler(new ChromePHPHandler());

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';


/*
 *
 * Bootstrap form macros
 *
 */



Form::macro('textField', function($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::text($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('passwordField', function($name, $label = null, $attributes = array())
{
    $element = Form::password($name, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('textareaField', function($name, $label = null, $value = null, $attributes = array())
{
    $element = Form::textarea($name, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('selectField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $element = Form::select($name, $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('selectMultipleField', function($name, $label = null, $options, $value = null, $attributes = array())
{
    $attributes = array_merge($attributes, array('multiple' => true));
    $element = Form::select($name, $options, $value, fieldAttributes($name, $attributes));

    return fieldWrapper($name, $label, $element);
});

Form::macro('checkboxField', function($name, $label = null, $value = 1, $checked = null, $attributes = array())
{
    $attributes = array_merge(array('id' => 'id-field-' . $name), $attributes);

    $out = '<div class="checkbox';
    $out .= fieldError($name) . '">';
    $out .= '<label>';
    $out .= Form::checkbox($name, $value, $checked, $attributes) . ' ' . $label;
    $out .= '</div>';

    return $out;
});

function fieldWrapper($name, $label, $element)
{
    $out = '<div class="form-group';
    $out .= fieldError($name) . '">';
    $out .= fieldLabel($name, $label);
    $out .= $element;
    $out .= '</div>';

    return $out;
}

function fieldError($field)
{
    $error = '';

    if ($errors = Session::get('errors'))
    {
        $error = $errors->first($field) ? ' has-error' : '';
    }

    return $error;
}

function fieldLabel($name, $label)
{
    if (is_null($label)) return '';

    $name = str_replace('[]', '', $name);

    $out = '<label for="id-field-' . $name . '" class="control-label">';
    $out .= $label . '</label>';

    return $out;
}

function fieldAttributes($name, $attributes = array())
{
    $name = str_replace('[]', '', $name);

    return array_merge(array('class' => 'form-control', 'id' => 'id-field-' . $name), $attributes);
}


require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/events.php';