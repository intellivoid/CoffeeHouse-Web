<?PHP

    if(isset($_GET['callback']))
    {
        switch((int)$_GET['callback'])
        {
            case 100:
                render_alert("There was an error while trying to process your request", 'danger', 'alert-circle');
                break;

            case 101:
                render_alert("A new access key has been generated successfully", 'success', 'check-circle-outline');
                break;

        }
    }