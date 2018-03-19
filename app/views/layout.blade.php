<!DOCTYPE html>
<html lang="en" ng-app="bestline">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @section('stylesheets')
            <link rel="stylesheet" href="/css/vendor/bootstrap.css" type="text/css"/>
            <link rel="stylesheet" href="/css/vendor/selectize.css" type="text/css"/>
            <link rel="stylesheet" href="/css/vendor/selectize.bootstrap3.css" type="text/css"/>
            <link rel="stylesheet" href="/css/vendor/datepicker.css" type="text/css"/>
            <link rel="stylesheet" href="/css/source/layout.css" type="text/css"/>
            <link rel="stylesheet" href="/css/vendor/font-awesome-4.2.0.min.css">
            <link rel="stylesheet" href="/css/build/bestline.css" type="text/css"/>
            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
                <script src="/js/vendor/html5shiv.js"></script>
                <script src="/js/vendor/respond.min.js"></script>
            <![endif]-->
        @show

        @section('javascript-head')
            <script type="text/javascript" src="/js/vendor/jquery.js"></script>
            <script type="text/javascript" src="/js/vendor/bower_components/angular/angular.min.js"></script>
            <script type="text/javascript" src="/js/vendor/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
            <script type="text/javascript" src="/js/vendor/bower_components/ngSmoothScroll/lib/angular-smooth-scroll.js"></script>
            <script type="text/javascript" src="/js/source/angular.js"></script>
            <script type="text/javascript" src="/js/source/angular/directives/bestline-tooltip.js"></script>
            <script type="text/javascript" src="/js/source/angular/filters/bestline-inches.js"></script>
        @show

        <title>
            @section("title")
            Bestline
            @show
        </title>
    </head>
    <body>

        @include("header")

        <div class="content">
            <div class="container-fluid {{ preg_replace("/\./", " ", Route::currentRouteName()) }}">
                <div class="row">
                    <div class="col-md-12">
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <p>{{ Session::get('success') }}</p>
                            </div>
                        @endif

                        @if (Session::has('warning'))
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <p>{{ Session::get('warning') }}</p>
                            </div>
                        @endif

                        @if (Session::has('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <p>{{ Session::get('error') }}</p>
                            </div>
                        @endif

                        @if (Session::has('message'))

                            <div class="alert flash alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <p>{{ Session::get('message') }}</p>
                            </div>
                        @endif

                        @if($errors->count() > 0)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <ul>
                                    @foreach($errors->getMessages() as $error)
                                        @foreach($error as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>

                @yield('main')
            </div>
        </div>

        @yield('footer')

        @section('javascript')

            <script src="/js/vendor/spin.js" type="application/javascript"></script>
            <script src="/js/vendor/jquery.spin.js" type="application/javascript"></script>
            <script src="/js/vendor/jquery.json.js" type="application/javascript"></script>
            <script src="/js/vendor/bootstrap.js" type="application/javascript"></script>
            <script src="/js/source/global.js" type="application/javascript"></script>
        @show
    </body>
</html>
