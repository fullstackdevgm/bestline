@extends("layout-plain")

@section("content")
    <div class="row">
        <div class="col-md-4 col-md-offset-4 text-center">
            <div class="panel-body">
                <img src="/images/black_logo.png"/>
            </div>
        </div>
    </div>
    <div class="row" id="login_content">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
    		  	<div class="panel-heading">
    		    	<h3 class="panel-title">Please Sign In</h3>
    		 	</div>
    		  	<div class="panel-body">
                    {{ Form::open(array(
                        "route" => "user.login",
                        "autocomplete" => "off",
                        "class" => "form-signin"
                    ))}}
                        <fieldset>
                            <div class="form-group">
                                {{ Form::text("username", Input::get("username"), array(
                                        "placeholder" => "Username",
                                        "class" => "form-control",
                                        "required" => "required",
                                        "autofocus" => "autofocus"
                                ))}}
                            </div>
                            <div class="form-group">
                                {{ Form::password("password", array(
                                        "placeholder" => "Password",
                                        "class" => "form-control",
                                        "required" => "required"
                                ))}}
                            </div>
                            {{ Form::button("Sign in", array(
                                "type" => "submit",
                                "class" => "btn btn-lg btn-success btn-block"
                            ))}}

                            <label class="checkbox pull-left">
                                <input type="checkbox" value="remember-me">
                                Remember me
                            </label>
                            <span class="clearfix"></span>

                            @if($error = $errors->first("password"))
                                <div class="error">
                                    {{ $error }}
                                </div>
                            @endif
                        </fieldset>
                        </form>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section("footer")
    @parent
    <script src="//polyfill.io"></script>
@stop
