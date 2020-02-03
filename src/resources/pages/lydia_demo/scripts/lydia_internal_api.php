<?php

use COASniffle\Abstracts\AvatarResourceName;
use COASniffle\Handlers\COA;
use CoffeeHouse\Abstracts\ForeignSessionSearchMethod;
use CoffeeHouse\Bots\Cleverbot;
use CoffeeHouse\CoffeeHouse;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\Runtime;
use sws\sws;
use ZiProto\ZiProto;

    error_reporting(0);
    Runtime::import('CoffeeHouse');



    if(isset($_GET['action']))
    {
        switch($_GET['action'])
        {
            case 'create_session':
                lydia_create_session();
                break;

            case 'think_thought':
                lydia_think_thought();
                break;

            case 'get_user':
                get_user();
                break;
        }
    }

    function send_response(array $data)
    {
        $data = json_encode($data);
        header('Content-Type: application/json');
        header('Content-Size: ' . strlen($data));
        print($data);
        exit();
    }

    function get_user()
    {
        if(WEB_SESSION_ACTIVE == false)
        {
            send_response(array(
                'username' => "You",
                "user_avatar" => "/assets/images/lydia_full.png"
            ));
        }
        else
        {
            send_response(array(
                'username' => WEB_ACCOUNT_USERNAME,
                'user_avatar' => COA::getAvatarUrl(AvatarResourceName::Normal, WEB_ACCOUNT_PUBID)
            ));
        }
    }

    function lydia_think_thought()
    {
        /** @var sws $sws */
        $sws = DynamicalWeb::getMemoryObject('sws');
        $Cookie = $sws->WebManager()->getCookie('ch_session');

        if($Cookie->Data['demo_session_id'] == null)
        {
            send_response(array(
                'status' => false,
                'error_type' => "session_expired",
                'message' => null
            ));
        }

        if(isset(DynamicalWeb::$globalObjects['coffeehouse']) == false)
        {
            /** @var CoffeeHouse $CoffeeHouse */
            $CoffeeHouse = DynamicalWeb::setMemoryObject('coffeehouse', new CoffeeHouse());
        }
        else
        {
            /** @var CoffeeHouse $CoffeeHouse */
            $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');
        }

        try
        {
            $Cleverbot = new Cleverbot($CoffeeHouse);
            $Cleverbot->loadSession($Cookie->Data['demo_session_id']);
        }
        catch(Exception $e)
        {
            send_response(array(
                'status' => false,
                'error_type' => "session_error",
                'message' => null
            ));
        }

        if((int)time() > $Cleverbot->getSession()->Expires)
        {
            send_response(array(
                'status' => false,
                'error_type' => "session_expired",
                'message' => null
            ));
        }

        if(isset($_POST['input']) == false)
        {
            send_response(array(
                'status' => false,
                'error_type' => "session_error",
                'message' => null
            ));
        }

        try
        {
            $Response = $Cleverbot->think($_POST['input']);

            send_response(array(
                'status' => true,
                'response' => $Response,
                'session_id' => $Cleverbot->getSession()->SessionID
            ));
        }
        catch(Exception $e)
        {
            send_response(array(
                'status' => false,
                'error_type' => "session_error",
                'message' => null
            ));
        }
    }

    function lydia_create_session()
    {
        if(WEB_SESSION_ACTIVE == false)
        {
            send_response(array(
                'status' => false,
                'error_type' => "authentication_required",
                'message' => null
            ));
        }

        if(isset(DynamicalWeb::$globalObjects['coffeehouse']) == false)
        {
            /** @var CoffeeHouse $CoffeeHouse */
            $CoffeeHouse = DynamicalWeb::setMemoryObject('coffeehouse', new CoffeeHouse());
        }
        else
        {
            /** @var CoffeeHouse $CoffeeHouse */
            $CoffeeHouse = DynamicalWeb::getMemoryObject('coffeehouse');
        }


        try
        {
            $CleverBot = new Cleverbot($CoffeeHouse);
            $CleverBot->newSession('en');
            $Session = $CleverBot->getSession();

            /** @var sws $sws */
            $sws = DynamicalWeb::getMemoryObject('sws');
            $Cookie = $sws->WebManager()->getCookie('ch_session');
            $Cookie->Data['demo_session_id'] = $Session->SessionID;
            $sws->CookieManager()->updateCookie($Cookie);

            send_response(array(
                'status' => true,
                'language' => $Session->Language,
                'id' => $Session->SessionID,
                'expires' => $Session->Expires
            ));
        }
        catch(Exception $e)
        {
            send_response(array(
                'status' => false,
                'error_type' => "session_error",
                'message' => null
            ));
        }
    }