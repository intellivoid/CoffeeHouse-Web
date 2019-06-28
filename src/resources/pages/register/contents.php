<?PHP
    use DynamicalWeb\HTML;

    HTML::importScript('auto_redirect');
    HTML::importScript('recaptcha');
    HTML::importScript('register_account');
    HTML::importScript('alert');
?>
<!doctype html>
<html lang="<?PHP HTML::print(APP_LANGUAGE_ISO_639); ?>">
    <head>
        <?PHP HTML::importSection('header'); ?>
        <?PHP HTML::print(re_import(), false); ?>
        <title><?PHP HTML::print(TEXT_PAGE_TITLE); ?></title>
    </head>
    <body class="fixed-left">

        <div class="accountbg" style="background: url('/assets/images/login_background.jpg');background-size: cover;"></div>
        <div class="wrapper-page account-page-full">

            <div class="card">
                <div class="card-body">
                    <h3 class="text-center m-0">
                        <a href="/register" class="logo logo-admin"><img src="/assets/images/logo.png" height="30" alt="logo"></a>
                    </h3>

                    <div class="p-3">
                        <p class="text-muted text-center"><?PHP HTML::print(TEXT_CARD_SUB_HEADER); ?></p>

                        <?PHP HTML::importScript('callbacks'); ?>
                        <form class="form-horizontal m-t-30" action="/register" method="POST">

                            <div class="form-group">
                                <label for="email"><?PHP HTML::print(TEXT_EMAIL_LABEL); ?></label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="<?PHP HTML::print(TEXT_EMAIL_PLACEHOLDER); ?>">
                            </div>

                            <div class="form-group">
                                <label for="username"><?PHP HTML::print(TEXT_USERNAME_LABEL); ?></label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="<?PHP HTML::print(TEXT_USERNAME_PLACEHOLDER); ?>">
                            </div>

                            <div class="form-group">
                                <label for="password"><?PHP HTML::print(TEXT_PASSWORD_LABEL); ?></label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="<?PHP HTML::print(TEXT_PASSWORD_PLACEHOLDER); ?>">
                            </div>

                            <div class="form-group">
                                <?PHP HTML::print(re_render(), false); ?>
                            </div>

                            <div class="form-group m-t-10 mb-0 row">
                                <div class="col-12 m-t-20">
                                    <p class="font-14 text-muted mb-0">By registering you agree to the <a href="https://intellivoid.info/tos">Terms of Service</a> and understand the <a href="https://intellivoid.info/privacy">Privacy Policies</a></p>
                                </div>
                            </div>

                            <div class="form-group row m-t-20">
                                <div class="col-sm-12 text-right">
                                    <button class="btn btn-primary w-md waves-effect waves-light" type="submit"><?PHP HTML::print(TEXT_SUBMIT_BUTTON); ?></button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

            <div class="m-t-40 text-center">
                <p><?PHP HTML::print(TEXT_EXISTING_ACCOUNT_LABEL); ?>
                    <a href="/login" class="font-500 font-14 font-secondary"> <?PHP HTML::print(TEXT_LOGIN_LABEL); ?></a>
                </p>
            </div>

        </div>

    </body>
    <?PHP HTML::importSection('jquery'); ?>
</html>
