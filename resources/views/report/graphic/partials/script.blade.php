<script>
    $(function () {

    // Set paths
    // ------------------------------

    require.config({
    paths: {
    echarts: '/assets/js/plugins/visualization/echarts'
    }
    });
    // Configuration
    // ------------------------------

    require(
            // Add necessary charts
            [
                    'echarts',
                    'echarts/theme/limitless',
                    'echarts/chart/line',
                    'echarts/chart/scatter',
                    'echarts/chart/k',
                    'echarts/chart/radar',
                    'echarts/chart/gauge'
            ],
            // Charts setup
                    function (ec, limitless) {

                    // Initialize charts
                    // ------------------------------
                    var type = '{{$type}}';
                    
                    if(type == 1){
                        var id = 'graphic_sucursal_'+'{{$idGraph}}';
                    }else{
                        var id = 'graphic_user_'+'{{$idGraphUser}}'+'_'+'{{$idGraph}}';
                    }
                    
                    var gauge_basic = ec.init(document.getElementById(id), limitless);
                    
                    // Charts options
                    // ------------------------------

                    //
                    // Basic gauge
                    //

                    // Setup chart
                    gauge_basic_options = {

                    // Add title
                    title: {
                    text: 'Porcentaje Logrado',
                            subtext: 'Rango Gráfico',
                            x: 'center'
                    },
                            // Add tooltip
                            tooltip: {
                            formatter: "{a} <br/>{b}: {c}%"
                            },
                            toolbox: {
                                show: true,
                                x: 'right',
                                y: 35,
                                orient: 'vertical',
                                feature: {
                                    saveAsImage: {
                                        show: true,
                                        title: 'Descargar Imagen',
                                        lang: ['Save']
                                    }
                                }
                            },
                            // Add series
                            series: [
                            {
                            name: 'Porcentaje Logrado',
                                    type: 'gauge',
                                    center: ['50%', '55%'],
                                    detail: {formatter:'{value}%'},
                                    data: [{value: <?=$percentage?>, name: ''}]
                            }
                            ]
                    };
                    // Apply options
                    // ------------------------------

                    gauge_basic.setOption(gauge_basic_options);
                    // Resize charts
                    // ------------------------------

                    window.onresize = function () {
                    setTimeout(function (){
                    gauge_basic.resize();
                    }, 200);
                    }
                    }
            );
            });
</script>