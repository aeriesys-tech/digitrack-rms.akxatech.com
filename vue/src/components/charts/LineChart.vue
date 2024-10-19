<template>
    <figure class="highcharts-figure">
        <div id="container"></div>
    </figure>
</template>
<script>
    var Highcharts = require("highcharts");
    require("highcharts/modules/heatmap")(Highcharts);
    require("highcharts/modules/data")(Highcharts);
    require("highcharts/modules/exporting")(Highcharts);
    require("highcharts/modules/export-data")(Highcharts);
    require("highcharts/modules/accessibility")(Highcharts);
    export default {
        name: "LineChart",
        props: {
            container: {
                default: null,
            },
            title: {
                default: null,
            },
            subtitle:{
                default: null,
            },
            xAxis: {
                default: null,
            },
            yAxis: {
                default: null,
            },
            min: {
                default: null,
            },
            max: {
                default: null,
            },
            scrollbar: {
                default: true,
            },
            series: {
                default: null,
            },
            minWidth: {
                default: 0,
            },
            plotOptions:{
                default: null,
            },
            marginTop: {
                default: null,
			},
            height: {
				// default: '78%',
                default: null,
			},
            width: {
				// default: '78%',
                default: null,
			},
            label_font_size: String,
            marginBottom:{
                default: null
            }
        },

        watch: {
            series: {
                handler() {
                    this.loadChart();
                },
                deep: true,
            },
            max: {
                handler() {
                    this.loadChart();
                },
                deep: true,
            },
        },

        mounted() {
            this.loadChart();
        },
        methods: {
            loadChart() {
                let vm = this;

                Highcharts.chart("container", {
                    credits: {
                        enabled: false,
                    },
                    legend:{
                        // enabled:false,
                        layout: 'horizontal',
                        align: 'right',
                        verticalAlign: 'top',
                        x: 10,
                        y: -11,
                        floating: true,
                        // borderWidth: 1,
                        backgroundColor:Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                        // shadow: true,
                        itemStyle: {
                            // color: 'white',
                            // fontWeight: 'bold',
                            fontSize: '9px'
                        }
                    },

                    exporting:{
                        enabled:false,
                    },
                    chart: {
                        type: "spline",
                        marginTop:vm.marginTop,
                        height:vm.height,
                        width:vm.width,
                        marginBottom:vm.marginBottom
                    },
                    title: {
                        text:vm.title,
                        verticalAlign:"bottom",
                        style: {
                            fontSize: vm.label_font_size,
                        }

                    },
                    subtitle: {
                        text:vm.subtitle,
                    },
                    xAxis: {
                        accessibility: {
                            description: "Months of the year",
                        },
                    },
                    yAxis: {
                        title: {
                            text: "Value",
                        },
                        labels: {
                            format: "{value}°",
                        },
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true,
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: "#666666",
                                lineWidth: 1,
                            },
                        },
                        series: ''
                        // {
                        //     pointStart: Date.UTC(2022, 0, 1),
                        //     // pointInterval: 24 * 3600 * 1000 * 30 * 4 // one day
                        // }
                    },
                    series: vm.series,
                    // [
                    //     {
                    //         name: "curve",
                    //         marker: {
                    //             symbol: "square",
                    //         },
                    //         data: [
                    //             5.2,
                    //             5.7,
                    //             8.7,
                    //             13.9,
                    //             18.2,
                    //             21.4,
                    //             25.0,
                    //             // {
                    //             //     y: 26.4,
                    //             //     marker: {
                    //             //         symbol: "url(https://www.highcharts.com/samples/graphics/sun.png)",
                    //             //     },
                    //             //     accessibility: {
                    //             //         description: "Sunny symbol, this is the warmest point in the chart.",
                    //             //     },
                    //             // },
                    //             22.8,
                    //             17.5,
                    //             12.1,
                    //             7.6,
                    //         ],
                    //     },

                    // ],
                });
            },
        },
    };
</script>
