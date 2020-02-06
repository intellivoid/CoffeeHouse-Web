<?php
    use COASniffle\Utilities\ErrorResolver;

    if(isset($_GET['callback']))
    {
        switch((int)$_GET['callback'])
        {
            case 100:
                render_alert(TEXT_CALLBACK_100, 'danger', 'alert-circle');
                break;

            case 101:
                render_alert(TEXT_CALLBACK_101, 'warning', 'alert-circle');
                break;

            case 102:
                $ErrorCode = 0;

                if(isset($_GET['coa_error']))
                {
                    $ErrorCode = (int)$_GET['coa_error'];
                }

                $ErrorMessage = ErrorResolver::resolve_error_code($ErrorCode);

                render_alert(str_ireplace('%s', $ErrorMessage, TEXT_CALLBACK_102), 'warning', 'alert-circle');
                break;

            case 103:
                render_alert(TEXT_CALLBACK_103, 'warning', 'alert-circle');
                break;

            case 104:
                render_alert(TEXT_CALLBACK_104, 'danger', 'alert-circle');
                break;

            case 105:
                render_alert(TEXT_CALLBACK_105, 'success', 'check-circle-outline');
                break;

            case 106:
                render_alert(TEXT_CALLBACK_106, 'warning', 'alert-circle');
                break;

            case 107:
                render_alert(TEXT_CALLBACK_107, 'warning', 'alert-circle');
                break;

        }
    }