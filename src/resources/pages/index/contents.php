<?PHP
    use DynamicalWeb\HTML;
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <title>CoffeeHouse - Dashboard</title>
    </head>
    <body>
        <?PHP HTML::importSection('navigation'); ?>

        <div class="wrapper">
            <div class="container-fluid">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="btn-group pull-right">
                                <ol class="breadcrumb hide-phone p-0 m-0">
                                    <li class="breadcrumb-item"><a href="#">Admiria</a></li>
                                    <li class="breadcrumb-item active">Starter</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Starter</h4>
                        </div>
                    </div>
                </div>
                <!-- end page title end breadcrumb -->

            </div> <!-- end container -->
        </div>

        <?PHP HTML::importSection('footer'); ?>
    </body>
    <?PHP HTML::importSection('jquery'); ?>
</html>
