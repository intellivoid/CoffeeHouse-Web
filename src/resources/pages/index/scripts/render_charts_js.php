<?PHP

    use DynamicalWeb\DynamicalWeb;
use DynamicalWeb\Runtime;
use ModularAPI\Abstracts\AccessKeySearchMethod;
    use ModularAPI\ModularAPI;
use ModularAPI\Objects\AccessKey;

    /** @var AccessKey $AccessKey */
    $AccessKeyObject = DynamicalWeb::getMemoryObject('ACCESS_KEY');

    $Javascript = "$(function() { 'use strict'; if ($('#api-usage-chart').length) { Morris.Line({";
    $Javascript .= "element: 'api-usage-chart',";
    $Javascript .= "parseTime: false,";
    $Javascript .= "resize: true,";
    $Javascript .= "redraw: true,";
    $Javascript .= "gridLineColor: '#2f3e47',";
    $Javascript .= "lineColors: ['#5468da', '#ffbb44', '#67a8e4'],";
    $Javascript .= "lineWidth: 2,";
    $Javascript .= "hideHover: 'auto',";

    if($AccessKeyObject->Analytics->LastMonthAvailable == true)
    {
        $data = [];

        foreach($AccessKeyObject->Analytics->CurrentMonthUsage as $key => $value)
        {
            $data[$key]['day'] = $key +1;
            $data[$key]['current_month'] = $value;
        }

        foreach($AccessKeyObject->Analytics->LastMonthUsage as $key => $value)
        {
            $data[$key]['day'] = $key +1;
            $data[$key]['last_month'] = $value;
        }

        $Javascript .= "data: " . json_encode($data) . ",";
        $Javascript .= "xkey: \"day\",";
        $Javascript .= "ykeys: ['current_month', 'last_month'],";
        $Javascript .= "labels: ['" . TEXT_USAGE_CURRENT_MONTH_LABEL . "', '" . TEXT_USAGE_LAST_MONTH_LABEL .  "']";
    }
    else
    {
        $data = [];

        foreach($AccessKeyObject->Analytics->CurrentMonthUsage as $key => $value)
        {
            $data[$key]['day'] = $key +1;
            $data[$key]['current_month'] = $value;
        }

        $Javascript .= "data: " . json_encode($data) . ",";
        $Javascript .= "xkey: \"day\",";
        $Javascript .= "ykeys: ['current_month'],";
        $Javascript .= "labels: ['" . TEXT_USAGE_CURRENT_MONTH_LABEL . "']";
    }


    $Javascript .= "});}})";

    print("<script>$Javascript</script>");
