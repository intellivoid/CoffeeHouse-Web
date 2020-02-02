<?PHP

use DynamicalWeb\HTML;

HTML::importScript('require_auth');

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>CoffeeHouse Dashboard</title>
    </head>
    <body>
        <?PHP HTML::importSection('navigation'); ?>

        <div class="wrapper">
            <div class="container-fluid">


            </div>
        </div>

        <?PHP HTML::importSection('footer'); ?>
    </body>
    <?PHP HTML::importSection('jquery'); ?>
    <script src="/assets/vendors/morris/morris.min.js"></script>
    <script src="/assets/vendors/raphael/raphael-min.js"></script>
</html>
