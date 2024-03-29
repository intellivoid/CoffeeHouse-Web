<?php
    use COASniffle\COASniffle;
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
?>
var compiled_asset_etag = '<?php print(hash('sha256', hash('crc32b', time()))); ?>';
var translation_text = null;
var lydia_session = null;
var current_message = null;
var username = "You";
var user_avatar = "/assets/images/generic_user.svg";
var ready = false;
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
    message_content = `${translation_text.authentication_required_message}<br/><br/>
    <a href="<?php HTML::print($AuthenticationURL, false); ?>" class="text-white">
        <i class="mdi mdi-lock pl-2 pr-1"></i>${translation_text.authentication_action}
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_error(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `${translation_text.generic_error_message}<br/><br/>
    <a href="<?php DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="text-white">
        <i class="mdi mdi-reload pl-2 pr-1"></i>${translation_text.reload_action}
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_session_error(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `${translation_text.session_error_message}<br/><br/>
    <a href="<?php DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="text-white">
        <i class="mdi mdi-reload pl-2 pr-1"></i>${translation_text.reload_action}
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_session_expired(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = `${translation_text.session_expired_message}<br/><br/>
    <a href="<?php DynamicalWeb::getRoute('lydia_demo', array(), true); ?>" class="text-white">
        <i class="mdi mdi-reload pl-2 pr-1"></i>${translation_text.reload_action}
    </a>`;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
}
function ui_bot_intro(msgid){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    message_content = translation_text.introduction_message;
    $(element_target).html(message_content);
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
    ready = true;
}
function ui_bot_response(msgid, response){
    element_target = "#remsg_text_" + msgid;
    $(element_target).empty();
    $(element_target).html(ehtml(response));
    $('#chat_content').scrollTop($('#chat_content')[0].scrollHeight);
    ready = true;
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
    $.post(
        "<?php DynamicalWeb::getRoute('lydia_demo', array('action' => 'get_user'), true); ?>",
        function(data, status){
            username = data.username;
            user_avatar = data.user_avatar;
            get_text();
        });
}
function get_text(){
    current_message = gen_msgid(64);
    ui_bot_message(current_message);
    $.post(
        "<?php DynamicalWeb::getRoute('lydia_demo', array('action' => 'get_text'), true); ?>",
        function(data, status){
            translation_text = data.text;
            ui_bot_intro(current_message)
        });
}
function think_thought(input=null, skip_creation=false){
    if(input === null) {
        input = $("#user_input").val();
        $("#user_input").val('');
        ready = false;
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

    setTimeout(function() {
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
    }, 1500);
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
    if(ready === true){
        if($("#user_input").val().length > 0) {
            think_thought();
        }
    }
    return false;
});
get_user();