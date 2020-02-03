<?php
use COASniffle\Abstracts\AvatarResourceName;use COASniffle\COASniffle;
use COASniffle\Handlers\COA;use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
?>
var lydia_session = null;
var current_message = null;
var username = "You";
var user_avatar = "/assets/images/lydia_full.png";
function ui_user_message(alias, message, avatar){
    alias = ehtml(alias);
    message = ehtml(message);
    const message_content = `
        <div class="msg right-msg animated bounceInRight">
            <div class="msg-img" style="background-image: url(${avatar})"></div>
            <div class="msg-bubble">
                <div class="msg-info">
                    <div class="msg-info-name">${alias}</div>
                    <div class="msg-info-time">${formatDate(new Date())}</div>
                </div>
                <div class="msg-text">${message}</div>
             </div>
        </div>`;
    $("#chat_content").append(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_message(msgid){
    msgid = ehtml(msgid);
    const message_content = `
        <div class="msg left-msg animated bounceInLeft" id="remsg_${msgid}">
            <div class="msg-img" style="background-image: url(/assets/images/lydia_full.png);"></div>
            <div class="msg-bubble">
                <div class="msg-info">
                    <div class="msg-info-name">Lydia</div>
                    <div class="msg-info-time">${formatDate(new Date())}</div>
                </div>
                <div class="msg-text" id="remsg_text_${msgid}">
                    <div class="ticontainer">
                        <div class="tiblock">
                            <div class="ml-1 tidot"></div>
                            <div class="tidot"></div>
                            <div class="tidot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    $("#chat_content").append(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_authentication_required(msgid){
    <?php
        $params['redirect'] = 'lydia_demo';

        /** @var COASniffle $COASniffle */
        $COASniffle = DynamicalWeb::getMemoryObject('coasniffle');
        $Protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';
        $RedirectURL = $Protocol . $_SERVER['HTTP_HOST'] . DynamicalWeb::getRoute('index', $params);
        $AuthenticationURL = $COASniffle->getCOA()->getAuthenticationURL($RedirectURL);
    ?>
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `As much as i would love to chat with you, i need you to authenticate first! this is to prevent abuse and spam<br/><br/>
    <a href="<?php HTML::print($AuthenticationURL, false); ?>" class="text-white">
        <i class="mdi mdi-lock pl-2 pr-1"></i>Click here to authenticate
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_error(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `Uh oh... Something went wrong, try refreshing maybe?<br/><br/>
    <a href="<?php DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="text-white">
        <i class="mdi mdi-reload pl-2 pr-1"></i>Reload
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_session_error(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `Whoops! There seems to be an issue with our chat session. Try refreshing this page!<br/><br/>
    <a href="<?php DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="text-white">
        <i class="mdi mdi-reload pl-2 pr-1"></i>Reload
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_session_expired(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `Hey! our chat session expired, we can talk again though! Try refreshing this page!<br/><br/>
    <a href="<?php DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="text-white">
        <i class="mdi mdi-reload pl-2 pr-1"></i>Reload
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_intro(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `Hello! Try having a conversation with me!`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
    $("#user_input").prop("disabled", false);
    $("#user_input").focus();
}
function ui_bot_response(msgid, response){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    $(element_target).html(ehtml(response));
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
    $("#user_input").prop("disabled", false);
    $("#user_input").focus();
}
function create_session(input){
    $.post(
        "<?php DynamicalWeb::getRoute('lydia_demo', array('action' => 'create_session'), true); ?>",
        {"rea": "none"},
         function(data, status){
            if(data.status == false)
            {
                switch(data.error_type){
                    case 'authentication_required':
                        ui_bot_authentication_required(current_message);
                        break;
                    case 'session_error':
                        ui_bot_session_error(current_message);
                        break;
                    default:
                        ui_bot_error(isSecureContext);
                        break;
                }
            }
            else
            {
                lydia_session = data.id;
                think_thought(input, true);
            }
    });
}
function get_user(){
    current_message = gen_msgid(64);
    ui_bot_message(current_message);
    $.post(
        "<?php DynamicalWeb::getRoute('lydia_demo', array('action' => 'get_user'), true); ?>",
        function(data, status){
            username = data.username;
            user_avatar = data.user_avatar;
            ui_bot_intro(current_message)
        });
}
function think_thought(input=null, skip_creation=false){
    if(input === null) {
        input = $("#user_input").val();
        $("#user_input").val('');
        $("#user_input").prop("disabled", true);
    }
    if(skip_creation === false) {
        ui_user_message(
            username,
            input, user_avatar
        );
        setTimeout(function() {
            current_message = gen_msgid(64);
            ui_bot_message(current_message);
        }, 1500);
    }
    if(lydia_session == null) {
        create_session(input);
        return;
    }
    $.post(
        "<?php DynamicalWeb::getRoute('lydia_demo', array('action' => 'think_thought'), true); ?>",
        {"input": input},
        function(data, status){
            if(data.status == false)
            {
                switch(data.error_type){
                    case 'session_expired':
                        ui_bot_authentication_required(current_message);
                        break;
                    case 'session_error':
                        ui_bot_session_error(current_message);
                        break;
                    default:
                        ui_bot_error(isSecureContext);
                        break;
                }
            }
            else
            {
                ui_bot_response(current_message, data.response);
            }
        });

    return false;
}
function ehtml(html){
    var text = document.createTextNode(html);
    var p = document.createElement('p');
    p.appendChild(text);
    return p.innerHTML;
}
function formatDate(date) {
    const h = "0" + date.getHours();
    const m = "0" + date.getMinutes();

    return `${h.slice(-2)}:${m.slice(-2)}`;
}
function gen_msgid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

$('#input_form').submit(function () {
    think_thought();
    return false;
});
get_user();