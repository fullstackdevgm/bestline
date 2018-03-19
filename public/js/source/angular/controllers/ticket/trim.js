(function ($, angular) {
'use strict';

/**
 * @ngdoc function
 * @name bestline.controller:TrimTicketController
 * @description
 */
angular.module('bestline').controller('TrimTicketController', TrimTicketController);

function TrimTicketController($scope, $q){

	var vm = this;

	function setup(){

		vm.optionData = [
			{
			    key: 'size',
			    name: 'Size',
			},
			{
			    key: 'size_bottom',
			    name: 'Bottom',
			},
			{
			    key: 'size_sides',
			    name: 'Sides',
			},
			{
			    key: 'size_top',
			    name: 'Top',
			},
			{
			    key: 'inset_size_sides',
			    name: 'Inset Sides',
			},
			{
			    key: 'inset_size_bottom',
			    name: 'Inset Bottom',
			},
			{
			    key: 'inset_size_top',
			    name: 'Inset Top',
			},
			{
			    key: 'number',
			    name: 'Number',
			},
			{
			    key: 'degrees',
			    name: 'Degrees',
			},
			{
			    key: 'assembler_note',
			    name: 'Assembler Note',
			},
		]

		viewApi();
	};
	function viewApi(){};
	setup();
}

}(window.jQuery || window.$, window.angular));
