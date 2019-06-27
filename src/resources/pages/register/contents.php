<?PHP
    use DynamicalWeb\HTML;


    HTML::importScript('recaptcha');
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <?PHP HTML::print(re_import(), false); ?>
        <title>CoffeeHouse - Register</title>
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
                        <p class="text-muted text-center">Create a new Intellivoid Account</p>

                        <form class="form-horizontal m-t-30" action="index.html">

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username or Email">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Register</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <?PHP HTML::print(re_render(), false); ?>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <p class="font-14 text-muted mb-0">By registering you agree to the <a href="https://intellivoid.info/tos">Terms of Service</a> and understand the <a href="https://intellivoid.info/privacy">Privacy Policies</a></p>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p class="">Already have an account?
                    <a href="/login" class="font-500 font-14 font-secondary"> Login</a>
                </p>
            </div>

        </div>

    </body>
    <?PHP HTML::importSection('jquery'); ?>
</html>
