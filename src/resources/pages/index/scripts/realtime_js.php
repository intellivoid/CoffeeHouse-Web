<script>
    setInterval(function()
    {
        $.ajax({
            type: "get",
            url: "/?action=get_info",
            dataType: 'json',
            success:function(data)
            {
                tr_clm = "<?PHP \DynamicalWeb\HTML::print(TEXT_WIDGET_MONTHLY_CALLS_SUB); ?>";
                $('#calls_current_month').text(data['usage']['current_month']);
                $('#calls_last_month').text(tr_clm.replace('%s', data['usage']['last_month']));
            }
        });
    }, 10000);
</script>