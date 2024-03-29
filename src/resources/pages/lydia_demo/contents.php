<?php

    use DynamicalWeb\HTML;
    use DynamicalWeb\Javascript;

    HTML::importScript('lydia_internal_api');

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/chatbox.css" rel="stylesheet">
        <title><?PHP HTML::print(TEXT_PAGE_TITLE); ?></title>
    </head>
    <body data-spy="scroll" data-target="#ch-navbar" data-offset="0">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section demo" id="demo">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center animated fadeIn slow">
                        <img alt="Lydia Logo" src="/assets/images/lydia_white_transparent.svg" class="img-fluid lydia_logo">
                        <p class="mt-4 demo-subtitle mb-5 mb-5"><?PHP HTML::print(TEXT_HEADER_DESCRIPTION); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="lydiachat mb-5 animated fadeInUp">
                        <main class="lydiachat-chat" id="chat_content"></main>
                        <form class="lydiachat-inputarea" id="input_form">
                            <input class="form-control bg-dark text-white" autocomplete="off" type="text" id="user_input" placeholder="<?PHP HTML::print(TEXT_INPUT_PLACEHOLDER); ?>" style="height: 40px;">
                            <button type="submit" class="btn btn-info btn-xs ml-2">
                                <i class="mdi mdi-send pl-0 pb-0 pt-0"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
        <?PHP Javascript::importScript('chat'); ?>
        <script src="/assets/js/ziproto.js"></script>
    </body>
</html>