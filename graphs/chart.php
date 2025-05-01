<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }
</style>

<!-- Resources (Local) -->
<script src="index.js"></script>
<script src="xy.js"></script>
<script src="animated.js"></script>

<?php
// Database connection
require_once '../cedric_dbConnection.php'; // Adjust to your connection file path

// Get current month and year information
$currentDate = new DateTime();
$currentMonth = intval($currentDate->format('n')); // 1-12
$currentYear = intval($currentDate->format('Y'));
$currentMonthName = $currentDate->format('M');
$currentPeriod = $currentYear . '-' . $currentMonthName;

// Query to get data grouped by year and month - limited to last 6 months
$sql = "SELECT 
          YEAR(dateOfOrder) AS year,
          MONTH(dateOfOrder) AS month,
          SUM(totalCost) AS revenue
        FROM 
          orderedhistory
        GROUP BY 
          YEAR(dateOfOrder), MONTH(dateOfOrder)
        ORDER BY 
          year DESC, month DESC
        LIMIT 6";

$result = $connection->query($sql);
$chartData = [];
$currentMonthIndex = -1;
$index = 0;

if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    // Reverse to get chronological order (oldest first)
    $rows = array_reverse($rows);

    foreach ($rows as $row) {
        $monthName = date("M", mktime(0, 0, 0, $row['month'], 1));
        $period = $row['year'] . '-' . $monthName;

        $chartData[] = [
            'period' => $period,
            'year' => intval($row['year']),
            'month' => intval($row['month']),
            'revenue' => floatval($row['revenue'])
        ];

        // Check if this is the current month
        if (intval($row['year']) === $currentYear && intval($row['month']) === $currentMonth) {
            $currentMonthIndex = $index;
            $chartData[$index]['isCurrent'] = true;
        }

        $index++;
    }
} else {
    // Add dummy data if no results to prevent errors
    $chartData[] = [
        'period' => 'No Data',
        'year' => $currentYear,
        'month' => $currentMonth,
        'revenue' => 0
    ];
}

// If current month isn't in our results and we have fewer than 6 months, add it
if ($currentMonthIndex === -1 && count($chartData) < 6) {
    $chartData[] = [
        'period' => $currentPeriod,
        'year' => $currentYear,
        'month' => $currentMonth,
        'revenue' => 0,
        'isCurrent' => true
    ];
    $currentMonthIndex = count($chartData) - 1;
}

// Convert PHP array to JSON for JavaScript
$chartDataJSON = json_encode($chartData);
$currentMonthIndexJSON = json_encode($currentMonthIndex);
?>

<!-- Chart code -->
<script>
    am5.ready(function () {
        // Create root element
        var root = am5.Root.new("chartdiv");

        // Set themes
        root.setThemes([am5themes_Animated.new(root)]);

        // Create chart
        var colors = {
            lightBlue: am5.color("#D4EBF8"),
            mediumBlue: am5.color("#60B5FF"),
            darkBlue: am5.color("#0A3981"),
            orange: am5.color("#E38E49"),
            background: am5.color("#FFFFFF")
        };

        // Create chart
        var chart = root.container.children.push(
            am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                paddingLeft: 20,
                paddingRight: 20,
                layout: root.verticalLayout,
                background: am5.Rectangle.new(root, {
                    fillGradient: am5.LinearGradient.new(root, {
                        stops: [{
                            color: am5.color("#FFFFFF")
                        }, {
                            color: am5.color(
                                "#EAF4FB") // Manually lightened version
                        }],
                        rotation: 90
                    })
                })
            })
        );

        // Get data from PHP
        var data = <?php echo $chartDataJSON; ?>;
        var currentMonthIndex = <?php echo $currentMonthIndexJSON; ?>;
        var currentPeriod = "<?php echo $currentPeriod; ?>";

        // Create axes
        var xRenderer = am5xy.AxisRendererX.new(root, {
            minGridDistance: 50
        });

        var xAxis = chart.xAxes.push(
            am5xy.CategoryAxis.new(root, {
                categoryField: "period",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            })
        );

        xRenderer.grid.template.setAll({
            location: 1
        });

        xAxis.data.setAll(data);

        var yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
                min: 0,
                extraMax: 0.1,
                renderer: am5xy.AxisRendererY.new(root, {
                    strokeOpacity: 0.1
                })
            })
        );

        // Format y-axis labels with peso sign
        yAxis.get("renderer").labels.template.setAll({
            fontSize: 12,
            text: "₱{value}"
        });

        // Add only the revenue series
        var series1 = chart.series.push(
            am5xy.ColumnSeries.new(root, {
                name: "Total Sales (Monthly)",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "revenue",
                categoryXField: "period",
                tooltip: am5.Tooltip.new(root, {
                    pointerOrientation: "horizontal",
                    labelText: "₱{valueY}"
                })
            })
        );

        // Highlight current month
        series1.columns.template.adapters.add("fill", function (fill, target) {
            if (target.dataItem && target.dataItem.dataContext.isCurrent) {
                return colors.orange;
            }
            return colors.mediumBlue;
        });

        series1.columns.template.adapters.add("stroke", function (stroke, target) {
            if (target.dataItem && target.dataItem.dataContext.isCurrent) {
                return colors.darkBlue;
            }
            return colors.darkBlue;
        });

        series1.columns.template.setAll({
            cornerRadiusTL: 5,
            cornerRadiusTR: 5,
            strokeOpacity: 0,
            tooltipY: am5.percent(10),
            width: am5.percent(70)
        });

        series1.data.setAll(data);

        chart.set("cursor", am5xy.XYCursor.new(root, {
            behavior: "none"
        }));

        // Add a vertical range to highlight current month
        if (currentMonthIndex >= 0) {
            var rangeDataItem = xAxis.makeDataItem({
                category: data[currentMonthIndex].period,
                endCategory: data[currentMonthIndex].period
            });

            var range = xAxis.createAxisRange(rangeDataItem);
            range.get("grid").setAll({
                stroke: am5.color(""),
                strokeOpacity: 1,
                strokeWidth: 2,
                location: 0
            });
        }

        // Make stuff animate on load
        chart.appear(1000, 100);
        series1.appear();
    });
</script>

<!-- HTML -->
<div id="chartdiv"></div>