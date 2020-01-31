<?php
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <title>CoffeeHouse</title>
    </head>

    <body data-spy="scroll" data-target="#ch-navbar" data-offset="20">
        <?PHP HTML::importSection('landing_navbar'); ?>
        <section class="section home" id="home">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="home-title">CoffeeHouse</h1>
                        <p class="mt-4 home-subtitle">Multi-purpose cloud based artificial intelligence & machine learning service for all</p>

                        <img src="/assets/images/lydia_showcase.svg" alt="CoffeeHouse's Lydia being used in Python" class="img-fluid mt-4">
                    </div>
                </div>
            </div>
        </section>

        <?PHP HTML::importSection('landing_footer'); ?>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>