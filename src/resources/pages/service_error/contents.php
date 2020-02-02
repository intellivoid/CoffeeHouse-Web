<?php
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/loader.css" rel="stylesheet">
        <title>CoffeeHouse - Service Error</title>
    </head>

    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section error">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="error-title m-2">Service Error</h1>
                        <h1 class="error-subtitle mb-5">There was an error while trying to handle your request</h1>
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
                                <h3 class="mt-3">What is this?</h3>
                                <p>There was an error while trying to handle your request, please send this page's URL to Intellivoid Support</p>
                                <h3 class="mt-5">Why?</h3>
                                <p>Well, it's optional. But the URL contains important information which allows a programmer to determine the issue</p>
                                <h3 class="mt-5">Like what?</h3>
                                <p>Nothing personal of course, just ask the programmer.</p>
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