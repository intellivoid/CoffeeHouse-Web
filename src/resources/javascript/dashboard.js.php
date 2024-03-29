/*
 Template Name: Admiria - Bootstrap 4 Admin Dashboard
 Author: Themesbrand
 File: Dashboard 2 Init
 */

!function ($) {
    "use strict";

    var Dashboard2 = function () {
    };


    //creates area chart
    Dashboard2.prototype.createAreaChart = function (element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
        Morris.Area({
            element: element,
            pointSize: 0,
            lineWidth: 0,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            resize: true,
            hideHover: 'auto',
            lineColors: lineColors,
            fillOpacity: .6,
            behaveLikeLine: true,
            gridLineColor: '#2f3e47'
        });
    },

        //creates Donut chart
        Dashboard2.prototype.createDonutChart = function (element, data, colors) {
            Morris.Donut({
                element: element,
                data: data,
                resize: true,
                colors: colors,
                backgroundColor: '#2f3e47',
                labelColor: '#fff'
            });
        },


        //creates Stacked chart
        Dashboard2.prototype.createStackedChart  = function(element, data, xkey, ykeys, labels, lineColors) {
            Morris.Bar({
                element: element,
                data: data,
                xkey: xkey,
                ykeys: ykeys,
                stacked: true,
                labels: labels,
                hideHover: 'auto',
                resize: true, //defaulted to true
                gridLineColor: '#2f3e47',
                barColors: lineColors
            });
        },

        Dashboard2.prototype.init = function () {

            //creating area chart
            var $areaData = [
                {y: '2007', a: 0, b: 0, c:0},
                {y: '2008', a: 150, b: 45, c:15},
                {y: '2009', a: 60, b: 150, c:195},
                {y: '2010', a: 180, b: 36, c:21},
                {y: '2011', a: 90, b: 60, c:360},
                {y: '2012', a: 75, b: 240, c:120},
                {y: '2013', a: 30, b: 30, c:30}
            ];

            //Peity pie
            $('.peity-pie').each(function() {
                $(this).peity("pie", $(this).data());
                console.log($(this));
            });

            //Peity donut
            $('.peity-donut').each(function() {
                $(this).peity("donut", $(this).data());
            });

        },
        //init
        $.Dashboard2 = new Dashboard2, $.Dashboard2.Constructor = Dashboard2
}(window.jQuery),

//initializing
    function ($) {
        "use strict";
        $.Dashboard2.init();
    }(window.jQuery);