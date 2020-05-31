/**
 * DeepAnalytics.js
 * Version 1.0.0.0
 * Copyright Intellivoid Technologies 2017-<?php print(date('Y')); ?>
 *
 * This DynamicalWeb Plugin interacts with Intellivoid's DeepAnalytics Library
 * which is housed internally on the server. This plugin enables both a front-end
 * interface and back-end interface, the front-end component interacts with the
 * backend component, the backend component is executes instructions to the
 * DeepAnalytics library to retrieve data and send format it to a JSON document.
 *
 * The front-end component takes this data and renders a user-friendly interface
 * which allows the user to view monthly and hourly analytical data.
 *
 * This plugin will be generated upon use by DynamicalWeb. This also supports
 * DynamicalCompression. No further setup or configuration is required, just
 * add the following files to DynamicalWeb's base.
 *
 * src/resources/plugins/frontend/dynamicalweb.js
 * src/resources/plugins/backend/dynamicalweb.go
 * src/resources/plugins/dynamicalweb.conf
 */

var deepanalytics = {
    display_id: null,
    instance_id: null,
    api_endpoint: null,
    chart_colors: null,
    selected_date: null,
    selected_day: null,
    selected_data: null,
    hourly_range: {},
    data_labels: {},
    loaded_data_range: null,
    loaded_monthly_data: null,
    loaded_hourly_data: null,

    /**
     * Initialize DeepAnalytics.js
     *
     * @param display_id
     * @param api_endpoint
     * @param chart_colors
     */
    init: function(display_id, api_endpoint, chart_colors){
        this.display_id = display_id;
        this.api_endpoint = api_endpoint;
        this.chart_colors = chart_colors;
        this.instance_id = this.make_instance_id();
        
        this.ui.render_preloader();
        this.api.get_range(function(){
            if(deepanalytics.utilities.check_if_empty(deepanalytics.loaded_data_range)) {
                $(`#${deepanalytics.display_id}`).empty();
                $('<div/>', {
                    'id': `${deepanalytics.instance_id}_deepanalytics_errors`,
                    'class': 'd-flex flex-column justify-content-center align-items-center',
                    'style': 'height:50vh;',
                    'html': $('<div/>', {
                        'class': 'p-2 my-flex-item fa-3x',
                        'html': $('<h4/>', {
                            'html': `No Data`
                        })
                    })
                }).appendTo(`#${deepanalytics.display_id}`);
            } else {
                deepanalytics.ui.render();
            }
        });
    },

    make_instance_id: function(){
        var result = '';
        var characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < 8; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    },

    /**
     * Main UI
     */
    ui: {
        /**
         * Renders the preloader animation
         */
        render_preloader: function(){
            $(`#${deepanalytics.display_id}`).empty();
            $('<div/>', {
                'id': `${deepanalytics.instance_id}_deepanalytics_init`,
                'class': 'd-flex flex-column justify-content-center align-items-center',
                'style': 'height:50vh;',
                'html': $('<div/>', {
                    'class': 'p-2 my-flex-item fa-3x',
                    'html': $('<i/>', {
                        'class': 'fa fa-circle-o-notch fa-spin'
                    })
                })
            }).appendTo(`#${deepanalytics.display_id}`);
        },

        /**
         * Displays an error message followed by the appropiate error code
         * @param error_code
         */
        error: function (error_code) {
            $(`#${deepanalytics.display_id}`).empty();
            $('<div/>', {
                'id': `${deepanalytics.instance_id}_deepanalytics_errors`,
                'class': 'd-flex flex-column justify-content-center align-items-center',
                'style': 'height:50vh;',
                'html': $('<div/>', {
                    'class': 'p-2 my-flex-item fa-3x',
                    'html': $('<h4/>', {
                        'html': `DeepAnalytics Error (${error_code})`
                    })
                })
            }).appendTo(`#${deepanalytics.display_id}`);
        },

        render: function() {
            $(`#${deepanalytics.display_id}`).empty();
            $('<div/>', {
                'class': 'row mt-4',
                'html': [
                    $('<label/>', {
                        'for': `${deepanalytics.instance_id}_deepanalytics_data_selector`,
                        'class': 'col-2 col-form-label',
                        'html': 'Data'
                    }),
                    $('<div/>', {
                        'class': 'col-10',
                        'html': $('<select/>', {
                            'name': `${deepanalytics.instance_id}_deepanalytics_data_selector`,
                            'id': `${deepanalytics.instance_id}_deepanalytics_data_selector`,
                            'class': 'form-control',
                            'change': function(){
                                deepanalytics.selected_data = $(this).children(":selected").attr("id");
                                deepanalytics.ui.reload();
                            }
                        })
                    })
                ]
            }).appendTo(`#${deepanalytics.display_id}`);

            $('<div/>', {
                'class': 'row mt-3',
                'html': [
                    $('<label/>', {
                        'for': `${deepanalytics.instance_id}_deepanalytics_date_selector`,
                        'class': 'col-2 col-form-label',
                        'html': 'Date'
                    }),
                    $('<div/>', {
                        'class': 'col-10',
                        'html': $('<select/>', {
                            'name': `${deepanalytics.instance_id}_deepanalytics_date_selector`,
                            'id': `${deepanalytics.instance_id}_deepanalytics_date_selector`,
                            'class': 'form-control',
                            'change': function(){
                                deepanalytics.selected_date = $(this).children(":selected").attr("id");
                                deepanalytics.ui.reload();
                            }
                        })
                    })
                ]
            }).appendTo(`#${deepanalytics.display_id}`);

            $('<option/>', {
                'html': "All",
                'id': "all"
            }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_data_selector`);
            deepanalytics.selected_data = 'all';

            var all_dates = [];
            for (var range_property in deepanalytics.loaded_data_range) {
                $('<option/>', {
                    'html': deepanalytics.loaded_data_range[range_property].text,
                    'id': range_property
                }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_data_selector`)
                deepanalytics.data_labels[range_property] = deepanalytics.loaded_data_range[range_property].text;

                console.log(deepanalytics.utilities.ab_get_last_item(deepanalytics.loaded_data_range[range_property]['monthly']));
                var selected_date = deepanalytics.utilities.ab_get_last_item(deepanalytics.loaded_data_range[range_property]['monthly']);
                if(typeof selected_date != "undefined") {
                    deepanalytics.selected_date = selected_date;
                }

                var selected_day = deepanalytics.utilities.ab_get_last_item(deepanalytics.loaded_data_range[range_property]['hourly']);
                if(typeof selected_day != "undefined"){
                    deepanalytics.selected_day = selected_day;
                }

                for (var month in deepanalytics.loaded_data_range[range_property]['monthly']) {

                    if (deepanalytics.utilities.push_unique(all_dates, month)) {
                        $('<option/>', {
                            'html': month,
                            'id': month
                        }).appendTo(`#${deepanalytics.instance_id}_deepanalytics_date_selector`)
                    }
                }
            }

            if(deepanalytics.ui.tab_view.render()){
                deepanalytics.utilities.load_hourly_range(deepanalytics.loaded_data_range);
                deepanalytics.chart_handler.monthly_chart.init();
                deepanalytics.chart_handler.hourly_chart.init();
            }

        },

        tab_view: {
            render: function(){
                this.render_tabs();
                this.render_tab_pages();
                return true;
            },

            render_tabs: function(){
                $('<ul/>', {
                    'class': 'nav nav-tabs nav-tabs-custom mt-3',
                    'role': 'tablist',
                    'html': [
                        $('<li/>', {
                            'class': 'nav-item',
                            'html': $('<a/>', {
                                'class': 'nav-link active',
                                'data-toggle': 'tab',
                                'id:': 'deepanalytics_monthly_tab',
                                'href': '#deepanalytics_monthly_tab',
                                'role': 'tab',
                                'aria-selected': 'true',
                                'html': [
                                    $('<span/>', {
                                        'class': 'd-none d-md-block',
                                        'html': 'Monthly Usage'
                                    }),
                                    $('<span/>', {
                                        'class': 'd-block d-md-none',
                                        'html': $('<i/>', {
                                            'class': 'mdi mdi-calendar-blank h5'
                                        })
                                    })
                                ]
                            })
                        }),
                        $('<li/>', {
                            'class': 'nav-item',
                            'html': $('<a/>', {
                                'class': 'nav-link',
                                'data-toggle': 'tab',
                                'id:': 'deepanalytics_hourly_tab',
                                'href': '#deepanalytics_hourly_tab',
                                'role': 'tab',
                                'aria-selected': 'false',
                                'html': [
                                    $('<span/>', {
                                        'class': 'd-none d-md-block',
                                        'html': 'Daily Usage'
                                    }),
                                    $('<span/>', {
                                        'class': 'd-block d-md-none',
                                        'html': $('<i/>', {
                                            'class': 'mdi mdi-calendar-clock h5'
                                        })
                                    })
                                ]
                            })
                        })
                    ]
                }).appendTo(`#${deepanalytics.display_id}`);
            },

            render_tab_pages: function(){
                $('<div/>', {
                    'class': 'tab-content',
                    'html': [
                        $('<div/>', {
                            'class': 'tab-pane p-3 active',
                            'id': 'deepanalytics_monthly_tab',
                            'role': 'tabpanel',
                            'html': $('<div/>', {
                                'id': 'deepanalytics_monthly_line_chart',
                                'class': 'morris-chart',
                                'style': 'height: 300px;'
                            })
                        }),
                        $('<div/>', {
                            'class': 'tab-pane p-3',
                            'id': 'deepanalytics_hourly_tab',
                            'role': 'tabpanel',
                            'html': [
                                $('<div/>', {
                                    'id': 'deepanalytics_hourly_line_chart',
                                    'class': 'morris-chart',
                                    'style': 'height: 270px;'
                                }),
                                $('<div/>', {
                                    'id': 'deepanalytics_hourly_selector',
                                    'style': 'height: 30px;'
                                })
                            ]
                        })
                    ]
                }).appendTo(`#${deepanalytics.display_id}`);

                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $(window).trigger('resize');
                });
            }
        },

        reload: function(){
            deepanalytics.chart_handler.monthly_chart.init();
            deepanalytics.chart_handler.hourly_chart.init();
        }
    },

    api: {
        get_range: function (callback) {
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.get_range`,
                type: "GET",
                success: function (data) {
                    deepanalytics.loaded_data_range = data;
                    callback();
                },
                error: function () {
                    deepanalytics.error(-10)
                }
            });
        },

        get_monthly_data: function(callback){
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.get_monthly_data`,
                type: "POST",
                data: {
                    "year": deepanalytics.selected_date.split('-')[0],
                    "month": deepanalytics.selected_date.split('-')[1]
                },
                success: function (data) {
                    if(data['status'] === false)
                    {
                        deepanalytics.error(-20);
                    }
                    else
                    {
                        deepanalytics.loaded_monthly_data = data;
                        callback();
                    }
                },
                error: function () {
                    deepanalytics.error(-10);
                }
            });
        },

        get_hourly_data: function(callback){
            $.ajax({
                url: `${deepanalytics.api_endpoint}?action=deepanalytics.get_hourly_data`,
                type: "POST",
                data: {
                    "year": deepanalytics.selected_day.split('-')[0],
                    "month": deepanalytics.selected_day.split('-')[1],
                    "day": deepanalytics.selected_day.split('-')[2]
                },
                success: function (data) {
                    if(data['status'] === false)
                    {
                        deepanalytics.error(-20);
                    }
                    else
                    {
                        deepanalytics.loaded_hourly_data = data;
                        callback();
                    }
                },
                error: function () {
                    deepanalytics.error(-10);
                }
            });
        }
    },

    utilities: {
        get_key_labels: function(exclude) {
            var data_keys = [];
            var data_labels = [];

            for(var label in deepanalytics.data_labels)
            {
                if(exclude.indexOf(label) < 0)
                {
                    deepanalytics.utilities.push_unique(data_keys, label);
                    deepanalytics.utilities.push_unique(data_labels, deepanalytics.data_labels[label]);
                }
            }

            return {
                keys: data_keys,
                labels: data_labels
            }
        },

        get_single_label: function(label) {
            var data_keys = [];
            var data_labels = [];

            deepanalytics.utilities.push_unique(data_keys, label);
            deepanalytics.utilities.push_unique(data_labels, deepanalytics.data_labels[label]);

            return {
                keys: data_keys,
                labels: data_labels
            }
        },

        check_if_empty: function(data){
            var is_empty = true;

            for(var data_range in data) {
                if(typeof data[data_range]["hourly"].length == "undefined") {
                    is_empty = false;
                }
            }

            return is_empty;
        },

        load_hourly_range: function(data){
            hourly_range = {};
            for(var data_range in data) {
                var hourly_range = data[data_range]["hourly"];
                for(var stamp in hourly_range)
                {
                    var formatted_stamp = stamp.split('-')[2];
                    if(deepanalytics.selected_date == `${stamp.split('-')[0]}-${stamp.split('-')[1]}`)
                    {
                        deepanalytics.hourly_range[formatted_stamp] = {
                            id: hourly_range[stamp]["id"],
                            date: hourly_range[stamp]["date"]
                        }
                    }

                }
            }
        },

        ab_get_last_item: function(obj){
            return Object.keys(obj)[Object.keys(obj).length - 1];
        },

        remove_unique: function (obj, item){
            const index = obj.indexOf(item);
            if (index > -1) {
                obj.splice(index, 1);
                return true;
            }
            return false;
        },

        push_unique: function (obj, item){
            if (obj.indexOf(item) == -1) {
                obj.push(item);
                return true;
            }
            return false;
        }
    },

    chart_handler: {

        hourly_chart: {

            line_chart: null,

            init: function() {
                this.navigation.render();
            },

            ui: {
                render_preloader: function(){
                    $("#deepanalytics_hourly_line_chart").empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:40vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<i/>', {
                                'class': 'fa fa-circle-o-notch fa-spin'
                            })
                        })
                    }).appendTo("#deepanalytics_hourly_line_chart");

                    deepanalytics.chart_handler.hourly_chart.navigation.disable();
                }

            },

            chart: {
                createLineChart: function (element, data, xkey, ykeys, labels, lineColors) {
                    deepanalytics.chart_handler.hourly_chart.line_chart = Morris.Line({
                        element: element,
                        data: data,
                        xkey: xkey,
                        ykeys: ykeys,
                        labels: labels,
                        hideHover: 'auto',
                        gridLineColor: '#2f3e47',
                        resize: true, //defaulted to true
                        lineColors: lineColors,
                        lineWidth: 2
                    });
                },

                no_data_render: function(){
                    $("#deepanalytics_hourly_line_chart").empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:40vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<h4/>', {
                                'html': 'No Data Available'
                            })
                        })
                    }).appendTo("#deepanalytics_hourly_line_chart");
                },

                render: function(){
                    $("#deepanalytics_hourly_line_chart").empty();

                    var exclude = [];
                    var labels = deepanalytics.utilities.get_key_labels(exclude);
                    var $data = [];
                    var working_data = {};

                    if(deepanalytics.selected_data == "all")
                    {
                        for(var data_entry in deepanalytics.loaded_hourly_data['results']) {
                            var data_entry_object = deepanalytics.loaded_hourly_data['results'][data_entry];

                            if(data_entry_object == null)
                            {
                                deepanalytics.utilities.push_unique(exclude, data_entry);
                                labels = deepanalytics.utilities.get_key_labels(exclude);
                            }
                            else
                            {
                                for(var stamp in data_entry_object['data']){
                                    if(typeof working_data[stamp] == "undefined"){
                                        working_data[stamp] = {}
                                    }
                                    working_data[stamp][data_entry] =
                                        data_entry_object['data'][stamp]
                                }
                            }
                        }
                    }
                    else
                    {
                        var data_entry_object = deepanalytics.loaded_hourly_data['results'][deepanalytics.selected_data];

                        if(data_entry_object == null)
                        {
                            this.no_data_render();
                            return;
                        }
                        else
                        {
                            labels = deepanalytics.utilities.get_single_label(deepanalytics.selected_data);
                            for(var stamp in data_entry_object['data']){
                                if(typeof working_data[stamp] == "undefined"){
                                    working_data[stamp] = {}
                                }
                                working_data[stamp][deepanalytics.selected_data] =
                                    data_entry_object['data'][stamp]
                            }
                        }
                    }

                    for(var entry in working_data){
                        $data.push(
                            Object.assign(
                                {y: entry},
                                working_data[entry]
                            )
                        )
                    }

                    if($data.length == 0){
                        this.no_data_render();
                        return;
                    }

                    deepanalytics.chart_handler.hourly_chart.chart.createLineChart(
                        'deepanalytics_hourly_line_chart', $data, 'y',
                        labels.keys, labels.labels, deepanalytics.chart_colors
                    );
                }
            },

            navigation: {

                range: null,
                minimum: null,
                maximum: null,
                selected_index: null,

                update: function() {
                    deepanalytics.chart_handler.hourly_chart.ui.render_preloader();
                    deepanalytics.selected_day = `${deepanalytics.selected_date}-${this.range[this.selected_index]}`;
                    deepanalytics.api.get_hourly_data(function(){
                        deepanalytics.chart_handler.hourly_chart.chart.render();
                        deepanalytics.chart_handler.hourly_chart.navigation.update_ui();
                    });
                },

                disable: function(){

                    $("#deepanalytics_hourly_pg_previous").unbind("click");
                    $("#deepanalytics_hourly_pg_next").unbind("click");

                    for (var current_range in this.range) {
                        if(typeof(this.range[current_range]) == "number"){
                            $(`#deepanalytics_hourly_pg_${this.range[current_range]}`).unbind("click");
                        }
                    }
                },

                update_ui: function() {
                    var selected = this.range[this.selected_index];

                    // Unbind existing events
                    $("#deepanalytics_hourly_pg_previous").unbind("click");
                    $("#deepanalytics_hourly_pg_next").unbind("click");

                    // Update previous button event
                    if(selected == this.minimum)
                    {
                        $("#deepanalytics_hourly_pg_previous").addClass("disabled");
                    }
                    else
                    {
                        $("#deepanalytics_hourly_pg_previous").removeClass("disabled");
                        $("#deepanalytics_hourly_pg_previous").click(function(){
                            deepanalytics.chart_handler.hourly_chart.navigation.selected_index -= 1;
                            deepanalytics.chart_handler.hourly_chart.navigation.update();
                        });
                    }

                    // Update next button event
                    if(selected == this.maximum)
                    {
                        $("#deepanalytics_hourly_pg_next").addClass("disabled");
                    }
                    else
                    {
                        $("#deepanalytics_hourly_pg_next").removeClass("disabled");
                        $("#deepanalytics_hourly_pg_next").click(function(){
                            deepanalytics.chart_handler.hourly_chart.navigation.selected_index += 1;
                            deepanalytics.chart_handler.hourly_chart.navigation.update();
                        });
                    }

                    for (var current_range in this.range) {
                        if(typeof(this.range[current_range]) == "number"){

                            $(`#deepanalytics_hourly_pg_${this.range[current_range]}`).removeClass("active");
                            $(`#deepanalytics_hourly_pg_${this.range[current_range]}`).unbind("click");

                            if(this.range[current_range] == selected){
                                $(`#deepanalytics_hourly_pg_${this.range[current_range]}`).addClass("active");
                            }

                            $(`#deepanalytics_hourly_pg_${this.range[current_range]}`).removeClass("disabled");
                            $(`#deepanalytics_hourly_pg_${this.range[current_range]}`).click(function() {
                                var value = parseInt($(this).attr("id").match(/\d+/g)[0]);
                                deepanalytics.chart_handler.hourly_chart.navigation.selected_index = deepanalytics.chart_handler.hourly_chart.navigation.range.indexOf(value);
                                deepanalytics.chart_handler.hourly_chart.navigation.update();
                            });
                        }
                    }
                },

                render: function() {
                    deepanalytics.chart_handler.hourly_chart.navigation.minimum = null;
                    deepanalytics.chart_handler.hourly_chart.navigation.maximum = null;
                    deepanalytics.chart_handler.hourly_chart.navigation.selected = null;
                    deepanalytics.chart_handler.hourly_chart.navigation.range = [];

                    $("<nav/>", {
                        "html": $("<ul/>", {
                            "class": "pagination pagination-sm justify-content-center",
                            "id": "deepanalytics_hourly_pg",
                            "html": $("<li/>", {
                                "class": "page-item disabled",
                                "id": "deepanalytics_hourly_pg_previous",
                                "html": $("<a/>", {
                                    "class": "page-link",
                                    "href": "#/",
                                    "html": $("<i/>", {
                                        "class": "mdi mdi-chevron-left"
                                    })
                                })
                            })
                        })
                    }).appendTo("#deepanalytics_hourly_selector");

                    for(var day in deepanalytics.hourly_range){

                        deepanalytics.chart_handler.hourly_chart.navigation.maximum = parseInt(day);
                        deepanalytics.utilities.push_unique(deepanalytics.chart_handler.hourly_chart.navigation.range, parseInt(day));

                        if(deepanalytics.chart_handler.hourly_chart.navigation.minimum == null){
                            deepanalytics.chart_handler.hourly_chart.navigation.minimum = parseInt(day);
                            deepanalytics.chart_handler.hourly_chart.navigation.selected = parseInt(day);
                            deepanalytics.chart_handler.hourly_chart.navigation.selected_index = deepanalytics.chart_handler.hourly_chart.navigation.range.indexOf(parseInt(day));
                        }


                        $("<li/>", {
                            "class": "page-item disabled",
                            "id": `deepanalytics_hourly_pg_${day}`,
                            "html": $("<a/>", {
                                "class": "page-link",
                                "href": "#/",
                                "html": day
                            })
                        }).appendTo("#deepanalytics_hourly_pg");
                    }

                    $("<li/>", {
                        "class": "page-item disabled",
                        "id": "deepanalytics_hourly_pg_next",
                        "html": $("<a/>", {
                            "class": "page-link",
                            "href": "#/",
                            "html": $("<i/>", {
                                "class": "mdi mdi-chevron-right"
                            })
                        })
                    }).appendTo("#deepanalytics_hourly_pg");

                    $("#deepanalytics_hourly_selector").rPage();
                    deepanalytics.chart_handler.hourly_chart.navigation.update();
                }
            }
        },

        monthly_chart: {
            line_chart: null,

            init: function()
            {
                this.ui.render_preloader();
                deepanalytics.api.get_monthly_data(this.chart.render);
            },

            ui: {
                render_preloader: function(){
                    $("#deepanalytics_monthly_line_chart").empty();
                    $("#deepanalytics_hourly_selector").empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:50vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<i/>', {
                                'class': 'fa fa-circle-o-notch fa-spin'
                            })
                        })
                    }).appendTo("#deepanalytics_monthly_line_chart");
                }
            },

            chart: {
                createLineChart: function (element, data, xkey, ykeys, labels, lineColors) {
                    deepanalytics.chart_handler.monthly_chart.line_chart = Morris.Line({
                        element: element,
                        data: data,
                        xkey: xkey,
                        ykeys: ykeys,
                        labels: labels,
                        hideHover: 'auto',
                        gridLineColor: '#2f3e47',
                        resize: true,
                        lineColors: lineColors,
                        lineWidth: 2
                    });
                },

                no_data_render: function(){
                    $("#deepanalytics_monthly_line_chart").empty();
                    $('<div/>', {
                        'class': 'd-flex flex-column justify-content-center align-items-center',
                        'style': 'height:40vh;',
                        'html': $('<div/>', {
                            'class': 'p-2 my-flex-item fa-3x',
                            'html': $('<h4/>', {
                                'html': 'No Data Available'
                            })
                        })
                    }).appendTo("#deepanalytics_monthly_line_chart");
                },

                render: function(){
                    $("#deepanalytics_monthly_line_chart").empty();

                    var exclude = [];
                    var labels = deepanalytics.utilities.get_key_labels(exclude);
                    var $data = [];
                    var working_data = {};

                    if(deepanalytics.selected_data == "all") {
                        for(var data_entry in deepanalytics.loaded_monthly_data['results']) {
                            var data_entry_object = deepanalytics.loaded_monthly_data['results'][data_entry];

                            if(data_entry_object == null) {
                                deepanalytics.utilities.push_unique(exclude, data_entry);
                                labels = deepanalytics.utilities.get_key_labels(exclude);
                            }
                            else {
                                for(var stamp in data_entry_object['data']){
                                    if(typeof working_data[stamp] == "undefined"){
                                        working_data[stamp] = {}
                                    }
                                    working_data[stamp][data_entry] =
                                        data_entry_object['data'][stamp]
                                }
                            }
                        }
                    }
                    else
                    {
                        var data_entry_object = deepanalytics.loaded_monthly_data['results'][deepanalytics.selected_data];

                        if(data_entry_object == null) {
                            this.deepanalytics.chart_handler.monthly_chart.chart.no_data_render();
                            return;
                        } else {
                            labels = deepanalytics.utilities.get_single_label(deepanalytics.selected_data);
                            for(var stamp in data_entry_object['data']){
                                if(typeof working_data[stamp] == "undefined"){
                                    working_data[stamp] = {}
                                }
                                working_data[stamp][deepanalytics.selected_data] =
                                    data_entry_object['data'][stamp]
                            }
                        }
                    }

                    for(var entry in working_data){
                        $data.push(
                            Object.assign(
                                {y: entry},
                                working_data[entry]
                            )
                        )
                    }

                    if($data.length == 0){
                        this.deepanalytics.chart_handler.monthly_chart.chart.no_data_render();
                        return;
                    }

                    deepanalytics.chart_handler.monthly_chart.chart.createLineChart(
                        'deepanalytics_monthly_line_chart', $data, 'y',
                        labels.keys, labels.labels, deepanalytics.chart_colors
                    );
                }
            }
        }
    }
}

$(document).ready(function () {
    deepanalytics.init(
        "deepanalytics_viewer", "<?php \DynamicalWeb\DynamicalWeb::getRoute('dashboard', [], true); ?>",
        ['#5468da', '#ffbb44', '#67a8e4', '#4ac18e', '#ea553d', '#3bc3e9']);
});