<?php

use Illuminate\Support\MessageBag;
use \Log;

class UserController extends Controller
{
    public function loginAction()
    {
        $errors = new MessageBag();

        if ($old = Input::old("errors"))
        {
             $errors = $old;
        }

        $data = array(
             "errors" => $errors
        );

        if (Input::server("REQUEST_METHOD") == "POST")
        {
         	
            $validator = Validator::make(Input::all(), array(
                "username" => "required",
                "password" => "required"
            ));

            if ($validator->passes())
            {
                $credentials = array(
                    "username" => Input::get("username"),
                    "password" => Input::get("password")
                );

                if (Auth::attempt($credentials))
                {
                    
                    $user = Auth::user();
                    if($user->hasRole('Cutter Admin')){

                        return Redirect::route("tracking.cutter");
                    } else {

                        return Redirect::route("dashboard");
                    }
                }
            } 

            $data["errors"] = new MessageBag(array("password" => array("Username and/or password invalid.")));

            $data["username"] = Input::get("username");

            return Redirect::to("user/login")->withInput($data);
        }

        return View::make("user/login", $data);
    }

    public function requestAction()
    {
        $data = array(
             "requested" => Input::old("requested")
        );

        if (Input::server("REQUEST_METHOD") == "POST")
        {
             $validator = Validator::make(Input::all(), array(
                 "email" => "required"
             ));

             if ($validator->passes())
             {
                 $credentials = array(
                     "email" => Input::get("email")
                 );

                 Password::remind($credentials, function($message, $user){
                     $message->from("chris@example.com");
                 });

                 $data["requested"] = true;

                 return Redirect::route("user/request")->withInput($data);
             }
        }

        return View::make("user/request", $data);
    }

    public function resetAction()
    {
        $token = "?token=" . Input::get("token");

        $errors = new MessageBag();

        if ($old = Input::old("errors"))
        {
             $errors = $old;
        }

        $data = array(
             "token" => $token,
             "errors" => $errors
        );

        if(Input::server("REQUEST_METHOD") == "POST")
        {
             $validator = Validator::make(Input::all(), array(
                 "email" => "required|email",
                 "password" => "required|min:6",
                 "password_confirmation" => "same:password",
                 "token" => "exists:token,token"
             ));

             if ($validator->passes())
             {
                 $credentials = array(
                     "email" => Input::get("email")
                 );

                 Password::reset($credentials, function($user, $password)
                 {
                     $user->password = Hash::make($password);
                     $user->save();

                     Auth::login($user);

                     return Redirect::route("user/profile");
                 });
             }

             $data["email"] = Input::get("email");
             $data["errors"] = $validator->errors();

             return Redirect::to(URL::route("user/reset") . $token)->withInput($data);
        }

        return View::make("user/reset", $data);
    }

    public function logoutAction()
    {
        Auth::logout();
        return Redirect::route("user.login");
    }
}