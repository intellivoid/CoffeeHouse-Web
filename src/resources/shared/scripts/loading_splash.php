<?PHP
    /** @noinspection PhpUnhandledExceptionInspection */

    use DynamicalWeb\HTML;
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
        <link href="/assets/css/loader.css" rel="stylesheet">
        <style>
            .loader_section{
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                transform: -webkit-translate(-50%, -50%);
                transform: -moz-translate(-50%, -50%);
                transform: -ms-translate(-50%, -50%);
            }
        </style>
        <title>CoffeeHouse</title>
    </head>
    <body>
        <div class="loader_section">
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
        </div>
        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>