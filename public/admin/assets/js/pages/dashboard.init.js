// Dashboard initialization script for content statistics chart
$(document).ready(function () {
    $.ajax({
        url: '/dashboard/content-statistics', // Update with your actual route
        method: 'GET',
        success: function (data) {
            // Remove any existing chart instance if needed
            if (window.contentHistoryChart) {
                window.contentHistoryChart.destroy();
            }

            var ctx = $("#content-history").get(0).getContext("2d");
            window.contentHistoryChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.last_ten_days,
                    datasets: [{
                        label: "Content Statistics",
                        backgroundColor: "#3ec396",
                        borderColor: "#3ec396",
                        borderWidth: 1,
                        hoverBackgroundColor: "#3ec396",
                        hoverBorderColor: "#3ec396",
                        data: data.last_ten_days_content
                    }]
                },
                options: {
                    maintainAspectRatio: false, // <-- Add this line
                    scales: {
                        yAxes: [{
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: "#fff"
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                color: "rgba(0,0,0,0.1)"
                            }
                        }]
                    }
                }
            });
        }
    });
});