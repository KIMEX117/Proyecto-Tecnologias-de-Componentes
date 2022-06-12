<script>
    <?php
        include_once('logica/baseDatos.php');
    ?>

    //-------------------------------------------------------------------
    //GRÁFICA CIRCULAR O PASTEL - "RELACIÓN MENSUAL COSTO/BENEFICIO"
    var pieChartCanvas = $('#graficaPie').get(0).getContext('2d');
    var pieData = {
        labels: ['Ganancias mensuales','Costos mensuales'],
        datasets: [{
            data: 
                <?php
                    $sql="SELECT (SUM(Monto_Total)-SUM(Costo_Total)) FROM viewResumenVentas";
                    $result=mysqli_query($conexion,$sql);
                    $ganancias = mysqli_fetch_array($result);
                        
                    $sql="SELECT SUM(Costo_Total) FROM viewResumenVentas";
                    $result=mysqli_query($conexion,$sql);
                    $costos = mysqli_fetch_array($result);
                    echo "[".$ganancias[0].",".$costos[0]."]";
                ?>,
            backgroundColor : ['#68c683', '#f6838d']
        }]
    };
    var pieOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
            labels: {
                fontColor: "black",
                fontSize: 14,
            }
        },
        plugins: {
            labels: [
                {
                    render: (args) => {
                        return `Dinero:\n\n$${args.value}`
                    },
                    fontColor: "#FFFFFF",
                    fontSize: 14,
                    fontStyle: 'bold'
                }, 
                {   
                    render: 'percentage', 
                    position: 'outside',
                    fontColor: "black",
                    fontSize: 14,
                    fontStyle: 'bold'
                }
            ]
        }
    };
    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    });

    //-------------------------------------------------------------------
    //GRÁFICA LINEAL - "VENTAS DE LOS ÚLTIMOS 7 DÍAS"
    <?php
        $query = $conexion->query("SELECT SUM(Monto_Total), Fecha_Venta FROM Ventas WHERE (Fecha_Venta BETWEEN (NOW()-INTERVAL 7 DAY) AND NOW()) GROUP BY Fecha_Venta ");
        foreach($query as $data){
            $montos[] = $data['SUM(Monto_Total)'];
            $fechas[] = $data['Fecha_Venta'];
        }
    ?>
    var lineChartCanvas = $('#graficaLineal').get(0).getContext('2d')
    var lineChartData = {
      labels  : <?php echo json_encode($fechas) ?>,
        
      datasets: [
        {
            label       : 'Ventas',
            borderColor : 'rgba(60,141,188,0.8)',
            pointColor  : '#3b8bba',
            data        : <?php echo json_encode($montos) ?>,
            tension     : 0.1,
            fill        : false
        }]
    };
    var lineChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    autoSkip: true,
                    fontColor: "black",
                    fontSize: 14,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Ingresos',
                    fontColor: "black",
                    fontSize: 14,
                    fontStyle: 'bold'
                },
            }],
            xAxes: [{
                ticks: {
                    autoSkip: true,
                    fontColor: "black",
                    fontSize: 14,
                }
            }]
        }
    };
    new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
    });

    //------------------------------------------------------------------
    //GRÁFICA DE BARRAS - "PRODUCTOS MÁS VENDIDOS"      
    <?php 
        $query = $conexion->query("SELECT Productos.Nombre AS Nombre, SUM(detalleVentas.Cantidad) Cantidad, Productos.Tipo Tipo FROM Productos INNER JOIN detalleVentas ON detalleVentas.ID_Producto = Productos.ID_Producto GROUP BY Productos.Nombre ORDER BY Cantidad DESC LIMIT 10");
        foreach($query as $data){
            $productos1[] = $data['Nombre'];
            $cantidad1[] = $data['Cantidad'];
        }
    ?>
    var barChartCanvas1 = $('#graficaBarras1').get(0).getContext('2d')
    const dataBarChart1 = {
        labels: <?php echo json_encode($productos1) ?>,
        datasets: [{
            data: <?php echo json_encode($cantidad1) ?>,
            backgroundColor: [
                'rgba(255, 112, 118 , 0.2)',
                'rgba(128, 112, 245 , 0.2)',
                'rgba(128, 239, 118 , 0.2)',
                'rgba(255, 239, 118 , 0.2)',
                'rgba(255, 112, 245 , 0.2)',
                'rgba(128, 239, 245 , 0.2)',
                'rgba(255, 179, 185 , 0.2)',
                'rgba(159, 144, 150 , 0.2)',
                'rgba(199, 55, 55 , 0.2)',
                'rgba(57, 55, 197 , 0.2)'
            ],
            borderColor: [
                'rgba(255, 112, 118)',
                'rgba(128, 112, 245)',
                'rgba(128, 239, 118)',
                'rgba(255, 239, 118)',
                'rgba(255, 112, 245)',
                'rgba(128, 239, 245)',
                'rgba(255, 179, 185)',
                'rgba(159, 144, 150)',
                'rgba(199, 55, 55)',
                'rgba(57, 55, 197)'
                ],
            borderWidth: 1
        }]
    };
    var barChartOptions1 = {
        responsive          : true,
        maintainAspectRatio : false,
        datasetFill         : false,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    autoSkip: true,
                    fontColor: "black",
                    fontSize: 14,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Piezas vendidas',
                    fontColor: "black",
                    fontSize: 14,
                    fontStyle: 'bold'
                },
            }],
            xAxes: [{
                ticks: {
                    autoSkip: true,
                    fontColor: "black",
                    fontSize: 14,
                }
            }], 
        },
        plugins: {
            labels: {
                render: 'value',
                fontStyle: 'bold'
            }  
        }
    };
    new Chart(barChartCanvas1, {
        type: 'bar',
        data: dataBarChart1,
        options: barChartOptions1
    });

    //------------------------------------------------------------------
    //GRÁFICA DE BARRAS - "PRODUCTOS MENOS VENDIDOS"
    <?php 
        $query = $conexion->query("SELECT Productos.Nombre AS Nombre, SUM(detalleVentas.Cantidad) Cantidad, Productos.Tipo Tipo FROM Productos INNER JOIN detalleVentas ON detalleVentas.ID_Producto = Productos.ID_Producto GROUP BY Productos.Nombre ORDER BY Cantidad ASC LIMIT 5");
        foreach($query as $data){
            $productos2[] = $data['Nombre'];
            $cantidad2[] = $data['Cantidad'];
        }
    ?>
    var barChartCanvas2 = $('#graficaBarras2').get(0).getContext('2d')
    const dataBarChart2 = {
        labels: <?php echo json_encode($productos2) ?>,
        datasets: [{
            data: <?php echo json_encode($cantidad2) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            borderColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
                ],
            borderWidth: 1
        }]
    };
    var barChartOptions2 = {
        responsive          : true,
        maintainAspectRatio : false,
        datasetFill         : false,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    autoSkip: true,
                    fontColor: "black",
                    fontSize: 14
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Piezas vendidas',
                    fontColor: "black",
                    fontSize: 14,
                    fontStyle: 'bold'
                },
            }],
            xAxes: [{
                ticks: {
                    autoSkip: true,
                    fontColor: "black",
                    fontSize: 14,
                }
            }]
        },
        plugins: {
            labels: {
                render: (args) => {
                    return ``
                },
            }  
        }   
    };
    new Chart(barChartCanvas2, {
        type: 'bar',
        data: dataBarChart2,
        options: barChartOptions2
    });
</script>