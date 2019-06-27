<?PHP
    use DynamicalWeb\HTML;

    HTML::importScript('recaptcha');
    HTML::importScript('login_account');
    HTML::importScript('alert');
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <?PHP HTML::print(re_import(), false); ?>
        <title>CoffeeHouse - Dashboard</title>
    </head>
    <body class="fixed-left">

        <div class="accountbg" style="background: url('/assets/images/login_background.jpg');background-size: cover;"></div>
        <div class="wrapper-page account-page-full">

            <div class="card">
                <div class="card-body">

                    <h3 class="text-center m-0">
                        <a href="index.html" class="logo logo-admin"><img src="/assets/images/logo.png" height="30" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <h4 class="font-18 m-b-5 text-center">Welcome to CoffeeHouse</h4>
                        <p class="text-muted text-center">Sign in using a Intellivoid Account</p>
                        <?PHP HTML::importScript('callbacks'); ?>

                        <form class="form-horizontal m-t-30" action="/login" method="POST">

                            <div class="form-group">
                                <label for="username_email">Username or Email</label>
                                <input type="text" class="form-control" name="username_email" id="username_email" placeholder="Enter Username or Email">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <?PHP HTML::print(re_render(), false); ?>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p>Don't have an account?
                    <a href="/register" class="font-500 font-14 font-secondary">Create one</a>
                </p>
            </div>

        </div>

    </body>
    <?PHP HTML::importSection('jquery'); ?>
</html>
