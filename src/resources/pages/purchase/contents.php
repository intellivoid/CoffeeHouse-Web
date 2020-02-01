<?php
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <title>CoffeeHouse - Lydia Demo</title>
    </head>

    <body data-spy="scroll" data-target="#ch-navbar" data-offset="0">
        <?PHP HTML::importSection('landing_navbar'); ?>

        <section class="section demo" id="demo">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <img src="/assets/images/lydia_white_transparent.svg" class="img-fluid lydia_logo">
                        <p class="mt-4 demo-subtitle mb-5 mb-5">Lydia is a advanced chat bot that actively learns from conversations and is capable of speaking in many languages without having to hard-configure anything about it.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="section" id="chat_demo">
            <div class="container">

            </div>
        </section>

        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>