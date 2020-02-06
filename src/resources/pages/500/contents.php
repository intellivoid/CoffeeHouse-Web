<?php
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/loader.css" rel="stylesheet">
        <title>CoffeeHouse - 500 Internal Server Error</title>
    </head>

    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section error">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="error-title m-2">500</h1>
                        <h1 class="error-subtitle mb-5">Server made an oopsy, but we can't show you why. Let our staff know instead!</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="card">
                    <div class="card-body">

                        <div class="ex-page-content text-center mt-4">
                            <h1 class="mb-4">What can i do?</h1>

                            <a class="btn btn-outline-primary mb-5 waves-effect waves-light" href="<?PHP DynamicalWeb::getRoute('index', array(), true); ?>">Go Home</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>


        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>