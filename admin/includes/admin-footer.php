<!--   Core JS Files   -->
<script src="../assets/js/core/jquery.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<script src="../assets/js/core/javascript.js"></script>

<!--  Google Maps Plugin    -->
<!-- Place this tag in your head or just before your close body tag. -->
<!-- Chart JS -->
<script src="../assets/js/plugins/chartjs.min.js"></script>
<!--  Notifications Plugin    -->
<script src="../assets/js/plugins/bootstrap-notify.js"></script>
<!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/black-dashboard.min.js?v=1.0.0"></script><!-- Black Dashboard DEMO methods, don't include it in your project! -->
<script src="../assets/demo/demo.js"></script>

<script>
    $(document).ready(function() {
        $().ready(function() {
            $sidebar = $('.sidebar');
            $navbar = $('.navbar');
            $main_panel = $('.main-panel');

            $full_page = $('.full-page');

            $sidebar_responsive = $('body > .navbar-collapse');
            sidebar_mini_active = true;
            white_color = false;

            window_width = $(window).width();

            fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();



            $('.fixed-plugin a').click(function(event) {
                if ($(this).hasClass('switch-trigger')) {
                    if (event.stopPropagation) {
                        event.stopPropagation();
                    } else if (window.event) {
                        window.event.cancelBubble = true;
                    }
                }
            });

            $('.fixed-plugin .background-color span').click(function() {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');

                var new_color = $(this).data('color');

                if ($sidebar.length != 0) {
                    $sidebar.attr('data', new_color);
                }

                if ($main_panel.length != 0) {
                    $main_panel.attr('data', new_color);
                }

                if ($full_page.length != 0) {
                    $full_page.attr('filter-color', new_color);
                }

                if ($sidebar_responsive.length != 0) {
                    $sidebar_responsive.attr('data', new_color);
                }
            });

            $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
                var $btn = $(this);

                if (sidebar_mini_active == true) {
                    $('body').removeClass('sidebar-mini');
                    sidebar_mini_active = false;
                    blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
                } else {
                    $('body').addClass('sidebar-mini');
                    sidebar_mini_active = true;
                    blackDashboard.showSidebarMessage('Sidebar mini activated...');
                }

                // we simulate the window Resize so the charts will get updated in realtime.
                var simulateWindowResize = setInterval(function() {
                    window.dispatchEvent(new Event('resize'));
                }, 180);

                // we stop the simulation of Window Resize after the animations are completed
                setTimeout(function() {
                    clearInterval(simulateWindowResize);
                }, 1000);
            });

            $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
                var $btn = $(this);

                if (white_color == true) {

                    $('body').addClass('change-background');
                    setTimeout(function() {
                        $('body').removeClass('change-background');
                        $('body').removeClass('white-content');
                    }, 900);
                    white_color = false;
                } else {

                    $('body').addClass('change-background');
                    setTimeout(function() {
                        $('body').removeClass('change-background');
                        $('body').addClass('white-content');
                    }, 900);

                    white_color = true;
                }


            });

            $('.light-badge').click(function() {
                $('body').addClass('white-content');
            });

            $('.dark-badge').click(function() {
                $('body').removeClass('white-content');
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Javascript method's body can be found in assets/js/demos.js
        demo.initDashboardPageCharts();

    });
</script>
<script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
<script>
    window.TrackJS &&
        TrackJS.install({
            token: "ee6fab19c5a04ac1a32a645abde4613a",
            application: "black-dashboard-free"
        });
</script>
<script>
    function showImageHereFuncAddPost() {
        $("#showImageHereAddPost").empty();
        var total_file = document.getElementById("uploadImageFileAddPost").files
            .length;
        for (var i = 0; i < total_file; i++) {
            $("#showImageHereAddPost").append(

                "<img src='" +
                URL.createObjectURL(event.target.files[i]) +
                "' height='200px' width=' 400px' style='border-style: solid; border-color:rgb(225,78,202);border-width:1px ' class='mr-2 mb-2'>"
            );
        }
    }

    function showImageHereFuncAddImageToPost(id) {

        $("#showImageHereFuncAddImageToPost" + id).empty();
        var total_file = document.getElementById("uploadImageFileEditPost" + id).files
            .length;
        for (var i = 0; i < total_file; i++) {
            $("#showImageHereFuncAddImageToPost" + id).append(

                "<img src='" +
                URL.createObjectURL(event.target.files[i]) +
                "' height='200px' width=' 400px' style='border-style: solid; border-color:rgb(225,78,202);border-width:1px ' class='mr-2 mb-2'>"
            );
        }
    }
</script>
<script>
    // $('.modal-content').css('margin-top', '-' + (Math.floor((window.innerHeight - $('.modal-content')[0].innerHeight) / 4) + 'px'));
</script>

<script>
    type = ["primary", "info", "success", "warning", "danger"];

    demo = {
        initPickColor: function() {
            $(".pick-class-label").click(function() {
                var new_class = $(this).attr("new-class");
                var old_class = $("#display-buttons").attr("data-class");
                var display_div = $("#display-buttons");
                if (display_div.length) {
                    var display_buttons = display_div.find(".btn");
                    display_buttons.removeClass(old_class);
                    display_buttons.addClass(new_class);
                    display_div.attr("data-class", new_class);
                }
            });
        },

        initDocChart: function() {
            chartColor = "#FFFFFF";

            // General configuration for the charts with Line gradientStroke
            gradientChartOptionsConfiguration = {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },
                tooltips: {
                    bodySpacing: 4,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    xPadding: 10,
                    yPadding: 10,
                    caretPadding: 10,
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    }, ],
                    xAxes: [{
                        display: 0,
                        gridLines: 0,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            zeroLineColor: "transparent",
                            drawTicks: false,
                            display: false,
                            drawBorder: false,
                        },
                    }, ],
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 15,
                        bottom: 15,
                    },
                },
            };

            ctx = document.getElementById("lineChartExample").getContext("2d");

            gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, "#80b6f4");
            gradientStroke.addColorStop(1, chartColor);

            gradientFill = ctx.createLinearGradient(0, 170, 0, 50);
            gradientFill.addColorStop(0, "rgba(128, 182, 244, 0)");
            gradientFill.addColorStop(1, "rgba(249, 99, 59, 0.40)");

            myChart = new Chart(ctx, {
                type: "line",
                responsive: true,
                data: {
                    labels: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec",
                    ],
                    datasets: [{
                        label: "Active Users",
                        borderColor: "#f96332",
                        pointBorderColor: "#FFF",
                        pointBackgroundColor: "#f96332",
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 1,
                        pointRadius: 4,
                        fill: true,
                        backgroundColor: gradientFill,
                        borderWidth: 2,
                        data: [542, 480, 430, 550, 530, 453, 380, 434, 568, 610, 700, 630],
                    }, ],
                },
                options: gradientChartOptionsConfiguration,
            });
        },

        initDashboardPageCharts: function() {
            gradientChartOptionsConfigurationWithTooltipBlue = {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },

                tooltips: {
                    backgroundColor: "#f5f5f5",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.0)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 60,
                            suggestedMax: 125,
                            padding: 20,
                            fontColor: "#2380f7",
                        },
                    }, ],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.1)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#2380f7",
                        },
                    }, ],
                },
            };

            gradientChartOptionsConfigurationWithTooltipPurple = {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },

                tooltips: {
                    backgroundColor: "#f5f5f5",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.0)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 60,
                            suggestedMax: 125,
                            padding: 20,
                            fontColor: "#9a9a9a",
                        },
                    }, ],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(225,78,202,0.1)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9a9a9a",
                        },
                    }, ],
                },
            };

            gradientChartOptionsConfigurationWithTooltipOrange = {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },

                tooltips: {
                    backgroundColor: "#f5f5f5",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.0)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 50,
                            suggestedMax: 110,
                            padding: 20,
                            fontColor: "#ff8a76",
                        },
                    }, ],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(220,53,69,0.1)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#ff8a76",
                        },
                    }, ],
                },
            };

            gradientChartOptionsConfigurationWithTooltipGreen = {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },

                tooltips: {
                    backgroundColor: "#f5f5f5",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.0)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 50,
                            suggestedMax: 125,
                            padding: 20,
                            fontColor: "#9e9e9e",
                        },
                    }, ],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(0,242,195,0.1)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9e9e9e",
                        },
                    }, ],
                },
            };

            gradientBarChartConfiguration = {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                },

                tooltips: {
                    backgroundColor: "#f5f5f5",
                    titleFontColor: "#333",
                    bodyFontColor: "#666",
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.1)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 60,
                            suggestedMax: 120,
                            padding: 20,
                            fontColor: "#9e9e9e",
                        },
                    }, ],

                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: "rgba(29,140,248,0.1)",
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9e9e9e",
                        },
                    }, ],
                },
            };
            // Until here
            /*
            var ctx = document.getElementById("chartLinePurple").getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, "rgba(72,72,176,0.2)");
            gradientStroke.addColorStop(0.2, "rgba(72,72,176,0.0)");
            gradientStroke.addColorStop(0, "rgba(119,52,169,0)"); //purple colors

            var data = {
                labels: ["JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
                datasets: [{
                    label: "Data",
                    fill: true,
                    backgroundColor: gradientStroke,
                    borderColor: "#d048b6",
                    borderWidth: 2,
                    borderDash: [],
                    borderDashOffset: 0.0,
                    pointBackgroundColor: "#d048b6",
                    pointBorderColor: "rgba(255,255,255,0)",
                    pointHoverBackgroundColor: "#d048b6",
                    pointBorderWidth: 20,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 15,
                    pointRadius: 4,
                    data: [80, 100, 70, 80, 120, 80],
                }, ],
            };

            var myChart = new Chart(ctx, {
                type: "line",
                data: data,
                options: gradientChartOptionsConfigurationWithTooltipPurple,
            });

            var ctxGreen = document.getElementById("chartLineGreen").getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, "rgba(66,134,121,0.15)");
            gradientStroke.addColorStop(0.4, "rgba(66,134,121,0.0)"); //green colors
            gradientStroke.addColorStop(0, "rgba(66,134,121,0)"); //green colors

            var data = {
                labels: ["JUL", "AUG", "SEP", "OCT", "NOV"],
                datasets: [{
                    label: "My First dataset",
                    fill: true,
                    backgroundColor: gradientStroke,
                    borderColor: "#00d6b4",
                    borderWidth: 2,
                    borderDash: [],
                    borderDashOffset: 0.0,
                    pointBackgroundColor: "#00d6b4",
                    pointBorderColor: "rgba(255,255,255,0)",
                    pointHoverBackgroundColor: "#00d6b4",
                    pointBorderWidth: 20,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 15,
                    pointRadius: 4,
                    data: [90, 27, 60, 12, 80],
                }, ],
            };

            var myChart = new Chart(ctxGreen, {
                type: "line",
                data: data,
                options: gradientChartOptionsConfigurationWithTooltipGreen,
            });
            */

            // Prvi veliki chart
            <?php
            $mjeseci = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
            $proizvodaci = array("AMD", "Nvidia", "Intel");
            $podaciObjave = array();
            $podaciKorisnici = array();
            $podaciKomentari = array();
            $podaciProizvodaci = array();
            for ($i = 1; $i <= count($mjeseci); $i++) {
                $sql = $conn->prepare("SELECT * FROM posts WHERE MONTH(post_date) = ? AND YEAR(post_date) = 2021");
                $sql->bind_param("i", $i);
                $sql->execute();
                $postResults = $sql->get_result();
                array_push($podaciObjave, mysqli_num_rows($postResults));
            }
            for ($i = 1; $i <= count($mjeseci); $i++) {
                $sql = $conn->prepare("SELECT * FROM comments WHERE MONTH(comment_date) = ? AND YEAR(comment_date) = 2021");
                $sql->bind_param("i", $i);
                $sql->execute();
                $postResults = $sql->get_result();
                array_push($podaciKomentari, mysqli_num_rows($postResults));
            }

            for ($i = 1; $i <= count($mjeseci); $i++) {
                $sql = $conn->prepare("SELECT * FROM users WHERE MONTH(user_date) = ? AND YEAR(user_date) = 2021");
                $sql->bind_param("i", $i);
                $sql->execute();
                $postResults = $sql->get_result();
                array_push($podaciKorisnici, mysqli_num_rows($postResults));
            }

            foreach ($proizvodaci as $proizvodac) {
                $sql = $conn->prepare("SELECT * FROM posts WHERE manufacturer = ?");
                $sql->bind_param("s", $proizvodac);
                $sql->execute();
                $postResults = $sql->get_result();
                array_push($podaciProizvodaci, mysqli_num_rows($postResults));
            }




            ?>
            var chart_labels = [
                "SIJ",
                "VELJ",
                "OŽU",
                "TRA",
                "SVI",
                "LIP",
                "SRP",
                "KOL",
                "RUJ",
                "LIS",
                "STU",
                "PRO",
            ];
            var chart_data = [
                <?php echo $podaciObjave['0'] ?>, <?php echo $podaciObjave['1'] ?>, <?php echo $podaciObjave['2'] ?>, <?php echo $podaciObjave['3'] ?>, <?php echo $podaciObjave['4'] ?>, <?php echo $podaciObjave['5'] ?>, <?php echo $podaciObjave['6'] ?>, <?php echo $podaciObjave['7'] ?>, <?php echo $podaciObjave['8'] ?>, <?php echo $podaciObjave['9'] ?>, <?php echo $podaciObjave['10'] ?>, <?php echo $podaciObjave['11'] ?>
            ];

            var ctx = document.getElementById("chartBig1").getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, "rgba(0, 214, 180, 0.4)");
            gradientStroke.addColorStop(0.4, "rgba(72,72,176,0.0)");
            gradientStroke.addColorStop(0, "rgba(119,52,169,0)"); //purple colors
            var config = {
                type: "line",
                data: {
                    labels: chart_labels,
                    datasets: [{
                        label: "Broj u ovom mjesecu",
                        fill: true,
                        backgroundColor: gradientStroke,
                        borderColor: "#00d6b4",
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        pointBackgroundColor: "#00d6b4",
                        pointBorderColor: "rgba(255,255,255,0)",
                        pointHoverBackgroundColor: "#00d6b4",
                        pointBorderWidth: 20,
                        pointHoverRadius: 4,
                        pointHoverBorderWidth: 15,
                        pointRadius: 4,
                        data: chart_data,
                    }, ],
                },
                options: gradientChartOptionsConfigurationWithTooltipGreen,
            };
            var myChartData = new Chart(ctx, config);
            $("#0").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = chart_data;
                data.labels = chart_labels;
                myChartData.update();
            });
            $("#1").click(function() {
                var chart_data = [
                    <?php echo $podaciKomentari['0'] ?>, <?php echo $podaciKomentari['1'] ?>, <?php echo $podaciKomentari['2'] ?>, <?php echo $podaciObjave['3'] ?>, <?php echo $podaciKomentari['4'] ?>, <?php echo $podaciKomentari['5'] ?>, <?php echo $podaciKomentari['6'] ?>, <?php echo $podaciKomentari['7'] ?>, <?php echo $podaciKomentari['8'] ?>, <?php echo $podaciKomentari['9'] ?>, <?php echo $podaciKomentari['10'] ?>, <?php echo $podaciKomentari['11'] ?>
                ];
                var data = myChartData.config.data;
                data.datasets[0].data = chart_data;
                data.labels = chart_labels;
                myChartData.update();
            });

            $("#2").click(function() {
                var chart_data = [
                    <?php echo $podaciKorisnici['0'] ?>, <?php echo $podaciKorisnici['1'] ?>, <?php echo $podaciKorisnici['2'] ?>, <?php echo $podaciKorisnici['3'] ?>, <?php echo $podaciKorisnici['4'] ?>, <?php echo $podaciKorisnici['5'] ?>, <?php echo $podaciKorisnici['6'] ?>, <?php echo $podaciKorisnici['7'] ?>, <?php echo $podaciKorisnici['8'] ?>, <?php echo $podaciKorisnici['9'] ?>, <?php echo $podaciKorisnici['10'] ?>, <?php echo $podaciKorisnici['11'] ?>
                ];
                var data = myChartData.config.data;
                data.datasets[0].data = chart_data;
                data.labels = chart_labels;
                myChartData.update();
            });

            var ctx = document.getElementById("CountryChart").getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, "rgba(29,140,248,0.2)");
            gradientStroke.addColorStop(0.4, "rgba(29,140,248,0.0)");
            gradientStroke.addColorStop(0, "rgba(29,140,248,0)"); //blue colors

            var myChart = new Chart(ctx, {
                type: "bar",
                responsive: true,
                legend: {
                    display: false,
                },
                data: {
                    labels: ["AMD", "Nvidia", "Intel"],
                    datasets: [{
                        label: "Broj objava",
                        fill: true,
                        backgroundColor: gradientStroke,
                        hoverBackgroundColor: gradientStroke,
                        borderColor: "#1f8ef1",
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        data: [<?php echo $podaciProizvodaci['0'] ?>, <?php echo $podaciProizvodaci['1'] ?>, <?php echo $podaciProizvodaci['2'] ?>],
                    }, ],
                },
                options: gradientBarChartConfiguration,
                scales: {
                    yAxes: [{
                        display: true,
                        ticks: {
                            suggestedMin: 0, //min
                            suggestedMax: 100 //max 
                        }
                    }]
                },

            });
            /*
            var ctx = document.getElementById("CountryChart1").getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, "rgba(29,140,248,0.2)");
            gradientStroke.addColorStop(0.4, "rgba(29,140,248,0.0)");
            gradientStroke.addColorStop(0, "rgba(29,140,248,0)"); //blue colors

            var myChart = new Chart(ctx, {
                type: "bar",
                responsive: true,
                legend: {
                    display: false,
                },
                data: {
                    labels: ["USA", "GER", "AUS", "UK", "RO", "BR"],
                    datasets: [{
                        label: "Countries",
                        fill: true,
                        backgroundColor: gradientStroke,
                        hoverBackgroundColor: gradientStroke,
                        borderColor: "#1f8ef1",
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        data: [53, 20, 10, 80, 100, 45],
                    }, ],
                },
                options: gradientBarChartConfiguration,
            });
            */
        },

        initGoogleMaps: function() {
            var myLatlng = new google.maps.LatLng(40.748817, -73.985428);
            var mapOptions = {
                zoom: 13,
                center: myLatlng,
                scrollwheel: false, //we disable de scroll over the map, it is a really annoing when you scroll through page
                styles: [{
                        elementType: "geometry",
                        stylers: [{
                            color: "#1d2c4d",
                        }, ],
                    },
                    {
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#8ec3b9",
                        }, ],
                    },
                    {
                        elementType: "labels.text.stroke",
                        stylers: [{
                            color: "#1a3646",
                        }, ],
                    },
                    {
                        featureType: "administrative.country",
                        elementType: "geometry.stroke",
                        stylers: [{
                            color: "#4b6878",
                        }, ],
                    },
                    {
                        featureType: "administrative.land_parcel",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#64779e",
                        }, ],
                    },
                    {
                        featureType: "administrative.province",
                        elementType: "geometry.stroke",
                        stylers: [{
                            color: "#4b6878",
                        }, ],
                    },
                    {
                        featureType: "landscape.man_made",
                        elementType: "geometry.stroke",
                        stylers: [{
                            color: "#334e87",
                        }, ],
                    },
                    {
                        featureType: "landscape.natural",
                        elementType: "geometry",
                        stylers: [{
                            color: "#023e58",
                        }, ],
                    },
                    {
                        featureType: "poi",
                        elementType: "geometry",
                        stylers: [{
                            color: "#283d6a",
                        }, ],
                    },
                    {
                        featureType: "poi",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#6f9ba5",
                        }, ],
                    },
                    {
                        featureType: "poi",
                        elementType: "labels.text.stroke",
                        stylers: [{
                            color: "#1d2c4d",
                        }, ],
                    },
                    {
                        featureType: "poi.park",
                        elementType: "geometry.fill",
                        stylers: [{
                            color: "#023e58",
                        }, ],
                    },
                    {
                        featureType: "poi.park",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#3C7680",
                        }, ],
                    },
                    {
                        featureType: "road",
                        elementType: "geometry",
                        stylers: [{
                            color: "#304a7d",
                        }, ],
                    },
                    {
                        featureType: "road",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#98a5be",
                        }, ],
                    },
                    {
                        featureType: "road",
                        elementType: "labels.text.stroke",
                        stylers: [{
                            color: "#1d2c4d",
                        }, ],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "geometry",
                        stylers: [{
                            color: "#2c6675",
                        }, ],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "geometry.fill",
                        stylers: [{
                            color: "#9d2a80",
                        }, ],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "geometry.stroke",
                        stylers: [{
                            color: "#9d2a80",
                        }, ],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#b0d5ce",
                        }, ],
                    },
                    {
                        featureType: "road.highway",
                        elementType: "labels.text.stroke",
                        stylers: [{
                            color: "#023e58",
                        }, ],
                    },
                    {
                        featureType: "transit",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#98a5be",
                        }, ],
                    },
                    {
                        featureType: "transit",
                        elementType: "labels.text.stroke",
                        stylers: [{
                            color: "#1d2c4d",
                        }, ],
                    },
                    {
                        featureType: "transit.line",
                        elementType: "geometry.fill",
                        stylers: [{
                            color: "#283d6a",
                        }, ],
                    },
                    {
                        featureType: "transit.station",
                        elementType: "geometry",
                        stylers: [{
                            color: "#3a4762",
                        }, ],
                    },
                    {
                        featureType: "water",
                        elementType: "geometry",
                        stylers: [{
                            color: "#0e1626",
                        }, ],
                    },
                    {
                        featureType: "water",
                        elementType: "labels.text.fill",
                        stylers: [{
                            color: "#4e6d70",
                        }, ],
                    },
                ],
            };

            var map = new google.maps.Map(document.getElementById("map"), mapOptions);

            var marker = new google.maps.Marker({
                position: myLatlng,
                title: "Hello World!",
            });

            // To add the marker to the map, call setMap();
            marker.setMap(map);
        },

        showNotification: function(from, align) {
            color = Math.floor(Math.random() * 4 + 1);

            $.notify({
                icon: "tim-icons icon-bell-55",
                message: "Welcome to <b>Black Dashboard</b> - a beautiful freebie for every web developer.",
            }, {
                type: type[color],
                timer: 8000,
                placement: {
                    from: from,
                    align: align,
                },
            });
        },
    };
</script>

</body>

</html>