<?PHP
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;
    use DynamicalWeb\Runtime;
    use Example\ExampleLibrary;

    Runtime::import('Example');

?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('landing_headers'); ?>
    </head>

    <body data-spy="scroll" data-target="#navbar-example" data-offset="20">
        <?PHP HTML::importSection('landing_nav'); ?>

        <!--START HOME-->
        <section class="section home" id="home">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 text-white text-center">
                        <h1 class="home-title">A Responsive Bootstrap 4 Admin Dashboard</h1>
                        <p class="mt-4 home-subtitle">Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit. Porttitor sagittis, nascetur molestie, venenatis mus id dapibus tempus, mus nam faucibus.</p>
                        <a href="#" class="btn btn-custom mt-4">Purchase Now</a>

                        <img src="/assets/images/showcase.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>
        <!--END HOME-->

        <?PHP HTML::importSection('landing_js'); ?>
    </body>
</html>
