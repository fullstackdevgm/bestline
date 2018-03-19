<?php

Route::any("logout", array(
    "as" => "user.logout",
    "uses" => "UserController@logoutAction"
));

Route::group(array("before" => "guest"), function(){
	Route::any("/", array(
		"as" => "user.login",
		"uses" => "UserController@loginAction"
	));

	Route::any("user/login", "UserController@loginAction");
});

Route::group(array("before" => "auth|user"), function () {

	Route::get("dashboard", array(
		'as' => 'dashboard',
		'uses' => 'DashboardController@indexAction'
	));

	Route::group(array('prefix' => 'inventory'), function(){

		Route::group(array('prefix' => 'part'), function(){

			Route::get('add', [
			   'as' => 'inventory.part.add',
			   'uses' => 'Inventory\InventoryController@addPart'
			]);

			Route::get('edit/{id}', [
			   'as' => 'inventory.part.edit',
			   'uses' => 'Inventory\InventoryController@editPart'
			]);

			Route::post('save/{id?}', [
			   'as' => 'inventory.part.save',
			   'uses' => 'Inventory\InventoryController@savePart'
			]);

			Route::get('delete/{id}', [
				'as' => 'inventory.part.delete',
				'uses' => 'Inventory\InventoryController@deletePart'
			]);

			Route::post('adjust/{id}', [
			    'as' => 'inventory.part.adjust',
			    'uses' => 'Inventory\InventoryController@adjustPart'
			]);
		});

		Route::group(array('prefix' => 'fabric'), function(){

			Route::get('edit/{id?}', [
			   	'as' => 'inventory.fabric.edit',
			   	'uses' => 'Inventory\InventoryController@editFabric'
			])->where('id', '[0-9]+');

			Route::get('delete/{id}', [
			   	'as' => 'inventory.fabric.delete',
			   	'uses' => 'Inventory\InventoryController@deleteFabric'
			]);
		});

		Route::get('/', [
		   'as' => 'inventory.dashboard',
		    'uses' => 'Inventory\DashboardController@getDashboard'
		]);
	});

	Route::group(array('prefix' => 'order'), function(){

		Route::get('confirmation/{orderId}', [
		    'as' => 'order.confirmation',
		    'uses' => 'OrderController@quoteConfirmationInvoice'
		]);

		Route::get('invoice/{orderId}', [
		    'as' => 'order.invoice',
		    'uses' => 'OrderController@quoteConfirmationInvoice'
		]);

		Route::get('quote/{orderId}', [
		    'as' => 'order.invoice',
		    'uses' => 'OrderController@quoteConfirmationInvoice'
		]);

		Route::group(array('prefix' => 'final'), function(){



			Route::group(array('prefix' => 'ticket'), function(){

				Route::get('/', [
				   'as' => 'finalized.order.ticket',
				   'uses' => 'FinalizedOrderController@ticket'
				]);

				Route::get('/labels', [
				   'as' => 'routes.ticketLabels',
				   'uses' => 'FinalizedOrderController@labels'
				]);
			});

			Route::get('view', [
			   'as' => 'finalized.order.view',
			   'uses' => 'FinalizedOrderController@view'
			]);
		});
	});

	Route::resource('order', 'OrderController');

	//group to organize /api endpoints
	Route::group(array('prefix' => 'api'), function(){

		//group for /api/fabric
		Route::group(array('prefix' => 'fabric'), function(){

			// endpoint for /api/fabric/type/{type}
			Route::get('type/{fabric_type_id}', [
			   'uses' => 'Api\FabricController@getType'
			]);

			// endpoint for /api/fabric/types/
			Route::get('types', [
				'uses' => 'Api\FabricController@getTypes'
			]);

			// endpoint for /api/fabric/all
			Route::get('all', [
				'uses' => 'Api\FabricController@getAll'
			]);

			// endpoint for /api/fabric/bestline
			Route::get('bestline', [
				'uses' => 'Api\FabricController@getBestline'
			]);

			// endpoint for /api/fabric/com
			Route::get('com', [
				'uses' => 'Api\FabricController@getCom'
			]);

			// endpoint for /api/fabric/unknown
			Route::get('unknown', [
				'uses' => 'Api\FabricController@getUnknown'
			]);

			//group for /api/fabric/{id}
			Route::group(array('prefix' => '{id?}'), function(){

				// endpoint for /api/fabric/{id}/save
				Route::put('save', [
					'uses' => 'Api\FabricController@saveFabric'
				]);
			});

			//controller for /api/fabric/{id?}
			Route::controller('{id?}', 'Api\FabricController');
		});

		//group for /api/inventory
		Route::group(array('prefix' => 'inventory'), function(){

			// endpoint: /api/inventory/part
			Route::group(array('prefix' => 'part'), function(){

				// endpoint: /api/inventory/part/datatable
				Route::get('datatable/{id}', [
				   'uses' => 'Api\Inventory\PartsController@getInventoryHistoryDatatable'
				]);
			});

			// endpoint: /api/inventory/fabric
			Route::group(array('prefix' => 'fabric'), function(){

				// endpoint: /api/inventory/fabric/{id}
				Route::group(array('prefix' => '{id}'), function(){

					// endpoint: /api/inventory/fabric/{id}
					Route::get('/', [
					   'uses' => 'Api\InventoryFabricController@getFabric'
					]);

					// endpoint: /api/inventory/fabric/{id}/adjust
					Route::post('/adjust', [
					   'uses' => 'Api\Inventory\FabricController@adjustFabric'
					]);
				});
			});
		});

		//group for /api/option
		Route::group(array('prefix' => 'option'), function(){

			//group for /api/option/{id?}
			Route::get('{id?}', [
			    'uses' => 'Api\OptionController@getIndex'
			])->where('id', '[0-9]+');
			//group for /api/option/tree
			Route::get('tree', [
			    'uses' => 'Api\OptionController@getTree'
			]);
			//group for /api/option/list
			Route::get('list', [
			    'uses' => 'Api\OptionController@getList'
			]);
			//group for /api/option/all
			Route::get('all', [
			    'uses' => 'Api\OptionController@getAll'
			]);
			//group for /api/option/save
			Route::post('save', [
			   'uses' => 'Api\OptionController@postSave'
			]);
			//group for /api/option/delete/{id}
			Route::post('delete/{id}', [
			   'uses' => 'Api\OptionController@postDelete'
			]);
			//group for /api/option/name/{id}/{sid?}
			Route::get('name/{id}/{sid?}', [
			   'uses' => 'Api\OptionController@getName'
			])->where('id', '[0-9]+');
		});

		//group for /api/order
		Route::group(array('prefix' => 'order'), function(){

			//endpoint: /api/order/transactions/datatable/{id}
			Route::get('transactions/datatable/{id}', [
			    'uses' => 'Api\OrderController@getTransactionsDatatable'
			])->where('id', '[0-9]+');

			//group for /api/order/final
			Route::group(array('prefix' => 'final'), function(){

				//group for /api/order/final/{orderId}
				Route::group(array('prefix' => '{orderId}'), function(){

					//endpoint: /api/order/final/{orderId}/unfinalize
					Route::get('unfinalize', [
						'uses' => 'Api\FinalizedOrderController@unfinalize'
					]);

					//endpoint: /api/order/final/{orderId}/labels
					Route::get('labels', [
						'uses' => 'Api\FinalizedOrderController@getOrderForLabels'
					]);
				});

				//endpoint: /api/order/final/{id}
				Route::controller('{id}', 'Api\FinalizedOrderController');
			});

			Route::get('select-options', [
				'uses' => 'Api\OrderController@selectOptions'
			]);

			Route::put('step-one', [
				'uses' => 'Api\OrderController@storeStep1'
			]);

			//group for /api/order/{order_id}
			Route::group(array('prefix' => '{order_id}'), function(){

				//endpoint: DELETE /api/order/{order_id}
				Route::delete('/', [
					'uses' => 'Api\OrderController@destroy'
				]);

				//group for /api/order/{order_id}/option
				Route::group(array('prefix' => 'option'), function(){

					//endpoint: DELETE /api/order/{order_id}/option/{option_id}
					Route::delete('{option_id}', [
						'uses' => 'Api\Order\OptionController@deleteIndex'
					]);
				});

				//group for /api/order/{order_id}/fabric
				Route::group(array('prefix' => 'fabric'), function(){

					Route::delete('{fabric_id}', [
						'uses' => 'Api\Order\FabricController@deleteIndex'
					]);

					Route::group(array('prefix' => '{fabric_id}'), function(){

						Route::group(array('prefix' => 'option'), function(){

							Route::delete('{option_id}', [
								'uses' => 'Api\Order\Fabric\OptionController@deleteIndex'
							]);
						});
					});
				});

				//group for /api/order/{order_id}/order-line
				Route::group(array('prefix' => 'order-line'), function(){

					//group for /api/order/{order_id}/order-line/{order_line_id}
					Route::group(array('prefix' => '{order_line_id}'), function(){

						// endpoint: GET /api/order/{order_id}/order-line/{order_line_id}/new
						Route::get('new', [
							'uses' => 'Api\Order\OrderLineController@getNew'
						]);

						// endpoint: POST /api/order/{order_id}/order-line/{order_line_id}/calculate
						Route::post('calculate', [
							'uses' => 'Api\Order\OrderLineController@calculate'
						]);

						// endpoint: DELETE /api/order/{order_id}/order-line/{order_line_id}/
						Route::delete('/', [
							'uses' => 'Api\Order\OrderLineController@delete'
						]);

						Route::group(array('prefix' => 'option'), function(){

							Route::group(array('prefix' => '{option_id}'), function(){

								Route::delete('/', [
									'uses' => 'Api\Order\OrderLine\OptionController@deleteIndex'
								]);
							});
						});

						// group: /api/order/{order_id}/order-line/{order_line_id}/work
						Route::group(array('prefix' => 'work'), function(){

							// group: /api/order/{order_id}/order-line/{order_line_id}/work/{work_id}
							Route::group(array('prefix' => '{work_id}'), function(){

								// POST: /api/order/{order_id}/order-line/{order_line_id}/work/{work_id}/checkin
								Route::post('/checkin', [
									'uses' => 'Api\Order\OrderLine\WorkController@checkin'
								]);

								// POST: /api/order/{order_id}/order-line/{order_line_id}/work/{work_id}/checkout/{user_id}
								Route::post('checkout/{user_id}', [
									'uses' => 'Api\Order\OrderLine\WorkController@checkout'
								]);

								// POST: /api/order/{order_id}/order-line/{order_line_id}/work/{work_id}/undo
								Route::post('/undo', [
									'uses' => 'Api\Order\OrderLine\WorkController@undoCheckout'
								]);
							});
						});
					});
				});

				// endpoint: POST /api/order/{order_id}/finalize
				Route::post('finalize', [
					'uses' => 'Api\OrderController@finalize'
				]);

				// endpoint: POST /api/order/{order_id}/confirm
				Route::post('confirm', [
					'uses' => 'Api\OrderController@confirm'
				]);
			});

			//group for /api/order/all
			Route::group(array('prefix' => 'all'), function(){

				//endpoint: /api/order/all/open
				Route::get('open', [
					'uses' => 'Api\OrderController@getAllOpen'
				]);
			});

			//endpoint: /api/order/{id}
			Route::controller('{id}', 'Api\OrderController');
		});

		//group for /api/product
		Route::group(array('prefix' => 'product'), function(){

			//endpoint: /api/product/valance
			Route::get('valance', [
				'uses' => 'Api\ValanceController@calculateValance'
			])->where('id', '[0-9]+');
			//endpoint: /api/product/{id}
			Route::controller('{id}', 'Api\ProductController');
		});

		//group for /api/company
		Route::group(array('prefix' => 'company'), function(){

			Route::get('all', [
				'uses' => 'Api\CompanyController@all'
			]);
			Route::get('select-options', [
				'uses' => 'Api\CompanyController@selectOptions'
			]);
			Route::get('new', [
				'uses' => 'Api\CompanyController@newCompany'
			]);

			//group for /api/company/{company_id}
			Route::group(array('prefix' => '{company_id}'), function(){

				//group for /api/company/{company_id}/price
				Route::group(array('prefix' => 'price'), function(){

					//endpoint: /api/company/{company_id}/price/fabric/{fabric_id}
					Route::get('/fabric/{fabric_id}', [
						'uses' => 'Api\Company\PriceController@getFabricPrices'
					]);

					//endpoint: /api/company/{company_id}/price/product/{product_id}
					Route::get('/product/{product_id}', [
						'uses' => 'Api\Company\PriceController@getProductPrices'
					]);

					//endpoint: /api/company/{company_id}/price/option/{option_id}
					Route::get('/option/{option_id}', [
						'uses' => 'Api\Company\PriceController@getOptionPrices'
					]);

					//endpoint: /api/company/{company_id}/price/select-options
					Route::get('/select-options', [
						'uses' => 'Api\Company\PriceController@getSelectOptions'
					]);

					//group for /api/company/{company_id}/price/{price_id}
					Route::group(array('prefix' => '{price_id}'), function(){

						//endpoint: /api/company/{company_id}/price/{price_id}/save
						Route::put('/save', [
							'uses' => 'Api\Company\PriceController@savePrice'
						]);

						//endpoint: /api/company/{company_id}/price/{price_id}/delete
						Route::delete('/delete', [
							'uses' => 'Api\Company\PriceController@deletePrices'
						]);
					});
				});
			});

			//endpoint: /api/company/{id}
			Route::controller('{id}', 'Api\CompanyController');
		});

		//group for /api/contact
		Route::group(array('prefix' => 'contact'), function(){

			//endpoint: /api/contact
			Route::controller('{id}', 'Api\ContactController');
		});

		//group for /api/lookups
		Route::group(array('prefix' => 'lookups'), function(){

			//endpoint: /api/lookups
			Route::controller('/', 'Api\LookupsController');
		});

		//group for /api/user
		Route::group(array('prefix' => 'user'), function(){

			//endpoint: /api/user/get
			Route::get('get', [
				'uses' => 'Api\UserController@getCurrent',
			]);
		});

		//group for /api/station
		Route::group(array('prefix' => 'station'), function(){

			//group for /api/station/{station_id}
			Route::group(array('prefix' => '{station_id}'), function(){

				//endpoint: /api/station/{station_id}/orders
				Route::get('orders', [
					'uses' => 'Api\StationController@getOrders',
				]);

				//endpoint: /api/station/{station_id}/users
				Route::get('users', [
					'uses' => 'Api\StationController@getUsers',
				]);
			});
		});

		//group for /api/upload
		Route::group(array('prefix' => 'upload'), function(){

			//group for /api/upload/{type}
			Route::group(array('prefix' => '{type}'), function(){

				//endpoint: POST:/api/upload/{upload_id}
				Route::post('/', [
					'uses' => 'Api\UploadController@upload',
				]);
			});
		});

		//group for /api/parts
		Route::group(array('prefix' => 'parts'), function(){

			//group for /api/parts/products
			Route::group(array('prefix' => 'products'), function(){

				//endpoint: GET:/api/parts/products/all
				Route::get('/all', [
					'uses' => 'Api\Parts\ProductsController@all',
				]);

				//endpoint: GET:/api/parts/products/select-options
				Route::get('/select-options', [
					'uses' => 'Api\Parts\ProductsController@selectOptions',
				]);

				//group for /api/parts/products/{product_id}
				Route::group(array('prefix' => '{product_id}'), function(){

					//endpoint: GET:/api/parts/products/{product_id}/
					Route::get('/', [
						'uses' => 'Api\Parts\ProductsController@getProductById',
					]);

					//endpoint: PUT:/api/parts/products/{product_id}/save
					Route::put('/save', [
						'uses' => 'Api\Parts\ProductsController@save',
					]);

					//endpoint: DELETE:/api/parts/products/{product_id}/
					Route::delete('/', [
						'uses' => 'Api\Parts\ProductsController@delete',
					]);
				});
			});

			//group for /api/parts/options
			Route::group(array('prefix' => 'options'), function(){

				//endpoint: GET:/api/parts/options/all
				Route::get('/all', [
					'uses' => 'Api\Parts\OptionsController@all',
				]);

				//endpoint: GET:/api/parts/options/all-suboptions
				Route::get('/all-suboptions', [
					'uses' => 'Api\Parts\OptionsController@allSuboptions',
				]);

				//endpoint: GET:/api/parts/options/select-options
				Route::get('/select-options', [
					'uses' => 'Api\Parts\OptionsController@selectOptions',
				]);

				//group for /api/parts/options/{option_id}
				Route::group(array('prefix' => '{option_id}'), function(){

					//endpoint: GET:/api/parts/options/{option_id}/
					Route::get('/', [
						'uses' => 'Api\Parts\OptionsController@getOptionById',
					]);

					//endpoint: PUT:/api/parts/options/{option_id}/save
					Route::put('/save', [
						'uses' => 'Api\Parts\OptionsController@save',
					]);

					//endpoint: DELETE:/api/parts/options/{option_id}/
					Route::delete('/', [
						'uses' => 'Api\Parts\OptionsController@delete',
					]);
				});
			});
		});
	});

	//group for /company
	Route::group(array('prefix' => 'company'), function(){

		Route::get('all', [
			'uses' => 'CompanyController@all',
			'as' => 'company.all',
		]);

		//endpoint: /api/lookups
		Route::controller('{id}', 'CompanyController');
	});

	//group for /tracking
	Route::group(array('prefix' => 'tracking'), function(){

		Route::get('cutter', [
			'uses' => 'TrackingController@viewCutter',
			'as' => 'tracking.cutter',
		]);
	});

	//group for /parts
	Route::group(array('prefix' => 'parts'), function(){

		//group for /parts/products
		Route::group(array('prefix' => 'products'), function(){

			//endpoint GET /parts/products
			Route::get('/', [
				'uses' => 'PartsController@viewProducts',
				'as' => 'routes.partsView.productsView',
			]);

			//endpoint GET /parts/products/
			Route::get('{product_id}', [
				'uses' => 'PartsController@viewEditProducts',
				'as' => 'routes.partsView.productsView.editView',
			]);
		});

		//group for /parts/options
		Route::group(array('prefix' => 'options'), function(){

			//endpoint GET /parts/options
			Route::get('/', [
				'uses' => 'PartsController@viewOptions',
				'as' => 'routes.partsView.optionsView',
			]);

			//endpoint GET /parts/options/
			Route::get('{option_id}', [
				'uses' => 'PartsController@viewEditOptions',
				'as' => 'routes.partsView.optionsView.editView',
			]);
		});
	});
});
