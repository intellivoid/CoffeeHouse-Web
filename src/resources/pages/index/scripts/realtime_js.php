<?PHP
    use DynamicalWeb\HTML;
?>
<script>
    setInterval(function()
    {
        $.ajax({
            type: "get",
            url: "/?action=get_info",
            dataType: 'json',
            success:function(response)
            {
                tr_clm = "<?PHP HTML::print(TEXT_WIDGET_MONTHLY_CALLS_SUB); ?>";
                cm_label = "<?PHP HTML::print(TEXT_USAGE_CURRENT_MONTH_LABEL); ?>";
                lm_label = "<?PHP HTML::print(TEXT_USAGE_LAST_MONTH_LABEL); ?>";
                $('#calls_current_month').text(response['usage']['current_month']);
                $('#calls_last_month').text(tr_clm.replace('%s', response['usage']['last_month']));

                $('#api-usage-chart').empty();
                if(response['analytics']['last_month_available'] === false)
                {
                    Morris.Line({
                        element: 'api-usage-chart',
                        parseTime: false,
                        resize: true,
                        redraw: true,
                        gridLineColor: '#2f3e47',
                        lineColors: ['#5468da', '#ffbb44', '#67a8e4'],
                        lineWidth: 2,
                        hideHover: 'auto',
                        data: response['analytics']['data'],
                        xkey: "day",
                        ykeys: ['current_month'],
                        labels: [cm_label]
                    });
                }
                else
                {
                    Morris.Line({
                        element: 'api-usage-chart',
                        parseTime: false,
                        resize: true,
                        redraw: true,
                        gridLineColor: '#2f3e47',
                        lineColors: ['#5468da', '#ffbb44', '#67a8e4'],
                        lineWidth: 2,
                        hideHover: 'auto',
                        data: response['analytics']['data'],
                        xkey: "day",
                        ykeys: ['current_month', 'last_month'],
                        labels: [cm_label, lm_label]
                    });
                }

            }
        });
    }, 10000);
</script>