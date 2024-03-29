<?php
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
                        <h1 class="error-title m-2"><?PHP HTML::print(TEXT_HEADER_TITLE); ?></h1>
                        <h1 class="error-subtitle mb-5"><?PHP HTML::print(TEXT_HEADER_DESCRIPTION); ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-b-20">
                            <div class="card-body">
                                <h3 class="mt-3"><?PHP HTML::print(TEXT_HEADER_1); ?></h3>
                                <p><?PHP HTML::print(TEXT_DESCRIPTION_1); ?></p>
                                <h3 class="mt-5"><?PHP HTML::print(TEXT_HEADER_2); ?></h3>
                                <p><?PHP HTML::print(TEXT_DESCRIPTION_2); ?></p>
                                <h3 class="mt-5"><?PHP HTML::print(TEXT_HEADER_3); ?></h3>
                                <p><?PHP HTML::print(TEXT_DESCRIPTION_3); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>