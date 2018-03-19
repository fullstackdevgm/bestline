<!DOCTYPE html>
<html lang="en" ng-app="bestline">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @section('stylesheets')
            <link rel="stylesheet" href="/css/vendor/bootstrap.css" type="text/css"/>
            <link rel="stylesheet" href="/css/source/layout.css" type="text/css"/>
            <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
            <link rel="stylesheet" href="/css/build/bestline.css" type="text/css"/>
        @show

        @section('javascript-head')
            <script type="text/javascript" src="/js/vendor/jquery.js"></script>
            <script type="text/javascript" src="/js/vendor/bower_components/angular/angular.min.js"></script>
            <script type="text/javascript" src="/js/vendor/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
            <script type="text/javascript" src="/js/vendor/bower_components/ngSmoothScroll/lib/angular-smooth-scroll.js"></script>
            <script type="text/javascript" src="/js/source/angular.js"></script>
        @show

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <title>
            @section("title")
            Bestline
            @show
        </title>
    </head>
    <body class="{{ preg_replace("/\./", " ", Route::currentRouteName()) }}">
    <div class="content">
        <div class="container">
            @yield("content")
        </div>
    </div>

    @section('javascript')

        <script src="/js/vendor/bootstrap.js" type="application/javascript"></script>
    @show
    </body>
</html>