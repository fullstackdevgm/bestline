<?php

class PartsController extends Controller
{
	public function viewProducts()
	{
		return View::make('parts.products');
	}

	public function viewEditProducts()
	{
		return View::make('parts.products.edit');
	}

	public function viewOptions()
	{
		return View::make('parts.options');
	}

	public function viewEditOptions()
	{
		return View::make('parts.options.edit');
	}
}
