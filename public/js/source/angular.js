(function (angular) {
	'use strict';

	/**
	 * @name bestline
	 * @description
	 * # bestline
	 *
	 * Main module of the application.
	 */
	var app = angular
	.module('bestline', [
		//this is where you inject other dependencies
		'ui.bootstrap',
		'smoothScroll',
	]);

	app.config(function(
		$interpolateProvider,
		$locationProvider
	) {
	    $interpolateProvider.startSymbol('{[{');
	    $interpolateProvider.endSymbol('}]}');
	    $locationProvider.html5Mode({
		    enabled: true,
		    requireBase: false,
		    rewriteLinks: false,
	    });
	  });
}(window.angular));
