<?php
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/loader.css" rel="stylesheet">
        <title><?PHP HTML::print(TEXT_PAGE_TITLE); ?></title>
    </head>
    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section error">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="error-title m-2"><?PHP HTML::print(TEXT_PAGE_HEADER); ?></h1>
                        <h1 class="error-subtitle mb-5"><?PHP HTML::print(TEXT_PAGE_DESC); ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="card">
                    <div class="card-body">
                        <div class="ex-page-content text-center mt-4">
                            <h1 class="mb-4"><?PHP HTML::print(TEXT_CARD_CONTENT_HEADER); ?></h1>
                            <a class="btn btn-outline-primary mb-5 waves-effect waves-light" href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>">
                                <?PHP HTML::print(TEXT_HOME_BUTTON); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>