<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input,Validator,Redirect,Hash,DB,Auth,Mail;
use App\Models\User;


class SandboxController extends Controller
{
    
    public function getSendEmail()
    {
        $user = array('name' => 'Jack Sparrow');
        $mail = Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
            $m->from('hello@app.com', 'Your Application');

            $m->to('jack@local.com', 'Jack')->subject('Your Reminder!');
        });

        var_dump($mail);
    }

    public function getVueToken()
    {
        $user  = User::find(1);        

       
        // Generate Token
        $user->deleteToken('register');
        $token = $user->createToken('register', 180, 100); // 180 minutes (3jam), 100 characters.

        echo $token->token;
       

        /*
        // Compare Token        
        if ($user->checkToken(Input::get('token'))) {
            echo "VALID : ".Input::get('token');
        }else{
            echo "INVALID : ".Input::get('token');
        }

        $token = $user->getToken(Input::get('token'), 'token');
        echo "<hr/>";
        print_r($token);
          
        
        $user = new User();
        $token = $user->checkToken('KexAMQSEG2PRykvt0j4DQqO9PrbvSdIUtlqbv5ZmPLyECtoalzV6MmwtJcGnwOaq46byCvwRKh7K7evEmRdafznaSAbTLcxPaIAS');     
        echo "<hr/><pre>";
        var_dump($token);
        echo "</pre>";
      */




    }
    
}
