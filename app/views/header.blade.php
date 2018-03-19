@section("header")

	<div class="header">
		<div class="container">
			<h1>BestLine</h1>
		</div>
	</div>
	<!-- Fixed navbar -->
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	  <div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">&nbsp;</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="/dashboard">Dashboard</a></li>
				@if($user->can('update_companies'))<li><a href="{{{ route('company.all') }}}">Companies</a></li>@endif
				@if($user->can('update_inventory'))<li><a href="/inventory">Inventory</a></li>@endif

				<li class="dropdown">
					<a href="" class="dropdown-toggle" data-toggle="dropdown">Parts <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="{{{ route('routes.partsView.productsView') }}}">Products</a></li>
						<li><a href="{{{ route('routes.partsView.optionsView') }}}">Options</a></li>
						@if($user->can('update_administration'))<li><a href="/admin">Administration</a></li>@endif
					</ul>
				</li>

				<li class="dropdown">
					<a href="" class="dropdown-toggle" data-toggle="dropdown">Tracking <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="{{{ route('tracking.cutter') }}}">Cutter</a></li>
					</ul>
				</li>

				@if($user->can('update_orders'))<li><span><a class="create-order btn btn-default btn-sm" href="/order/create">New Order</a></span></li>@endif
				@if($user->can('update_orders'))<li><span><a class="create-order btn btn-default btn-sm b-margin b-left-sm" href="/order/create?is_quote=true">New Quote</a></span></li>@endif
				<!--
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Action</a></li>
					<li><a href="#">Another action</a></li>
					<li><a href="#">Something else here</a></li>
					<li class="divider"></li>
					<li class="dropdown-header">Nav header</li>
					<li><a href="#">Separated link</a></li>
					<li><a href="#">One more separated link</a></li>
				</ul>
				</li>
				-->
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>{{ link_to_route('user.logout', Auth::user()->username . " : Logout") }}</li>
			</ul>
		</div><!--/.nav-collapse -->
	  </div>
	</div>

	@if (App::environment() == 'local')
	    <p class="devNotification">{{ str_repeat("DEV",60) }}</p>
	@endif

@show