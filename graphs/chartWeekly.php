<!-- Styles -->
<style>
    #chartdiv {
        width: 100%;
        height: 500px;
    }

    .current-week-indicator {
        background-color: rgba(76, 175, 80, 0.2);
        border-left: 2px solid #E38E49;
        padding: 5px 10px;
        position: absolute;
        font-size: 12px;
        color: white;
        font-weight: bold;
        z-index: 100;
    }
</style>

<!-- Resources -->
<script src="index.js"></script>
<script src="xy.js"></script>
<script src="animated.js"></script>

<?php
// Database connection
require_once '../cedric_dbConnection.php'; // Adjust to your connection file path

// Get current week information
$currentDate = new DateTime();
$currentWeekNumber = intval($currentDate->format('W'));
$currentYear = intval($currentDate->format('Y'));
$currentWeekLabel = "Week $currentWeekNumber, $currentYear";

// Check if the table has the weekly columns
$checkColumnsQuery = "SHOW COLUMNS FROM orderedhistory LIKE 'weekNumber'";
$columnsResult = $connection->query($checkColumnsQuery);
$hasWeekColumns = ($columnsResult->num_rows > 0);

if ($hasWeekColumns) {
    // Use the weekly columns for grouping (more efficient)
    $sql = "SELECT 
              weekYear AS year,
              weekNumber AS week,
              weekLabel AS periodLabel,
              SUM(totalCost) AS revenue
            FROM 
              orderedhistory
            GROUP BY 
              weekYear, weekNumber, weekLabel
            ORDER BY 
              year DESC, week DESC
            LIMIT 5"; // Limit to 5 most recent weeks
} else {
    // Use MySQL's WEEK function if weekly columns don't exist
    $sql = "SELECT 
              YEAR(dateOfOrder) AS year,
              WEEK(dateOfOrder, 1) AS week,
              CONCAT('Week ', WEEK(dateOfOrder, 1), ', ', YEAR(dateOfOrder)) AS periodLabel,
              SUM(totalCost) AS revenue
            FROM 
              orderedhistory
            GROUP BY 
              YEAR(dateOfOrder), WEEK(dateOfOrder, 1)
            ORDER BY 
              YEAR(dateOfOrder) DESC, WEEK(dateOfOrder, 1) DESC
            LIMIT 5"; // Limit to 5 most recent weeks
}

$result = $connection->query($sql);
$chartData = [];
$currentWeekIndex = -1;
$index = 0;

if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    // Reverse to get chronological order (oldest first)
    $rows = array_reverse($rows);

    foreach ($rows as $row) {
        $chartData[] = [
            'period' => $row['periodLabel'],
            'year' => intval($row['year']),
            'week' => intval($row['week']),
            'revenue' => floatval($row['revenue'])
        ];

        // Check if this is the current week
        if (intval($row['year']) === $currentYear && intval($row['week']) === $currentWeekNumber) {
            $currentWeekIndex = $index;
        }
        $index++;
    }
} else {
    // Add dummy data if no results to prevent errors
    $chartData[] = [
        'period' => 'No Data',
        'year' => $currentYear,
        'week' => $currentWeekNumber,
        'revenue' => 0
    ];
}

// If current week isn't in our results, add it with zero values 
// (only if we have less than 5 weeks of data to maintain our limit)
if ($currentWeekIndex === -1 && count($chartData) < 5) {
    $chartData[] = [
        'period' => $currentWeekLabel,
        'year' => $currentYear,
        'week' => $currentWeekNumber,
        'revenue' => 0,
        'isCurrent' => true
    ];
    $currentWeekIndex = count($chartData) - 1;
} else if ($currentWeekIndex !== -1) {
    // Mark the current week in the existing data
    $chartData[$currentWeekIndex]['isCurrent'] = true;
}

// Convert PHP array to JSON for JavaScript
$chartDataJSON = json_encode($chartData);
$currentWeekIndexJSON = json_encode($currentWeekIndex);
?>

<!-- Chart code -->
<script>
    am5.ready(function () {
        // Create root element
        var root = am5.Root.new("chartdiv");

        // Set themes
        root.setThemes([am5themes_Animated.new(root)]);

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
        var currentWeekIndex = <?php echo $currentWeekIndexJSON; ?>;
        var currentWeekLabel = "<?php echo $currentWeekLabel; ?>";

        // Create axes
        var xAxis = chart.xAxes.push(
            am5xy.CategoryAxis.new(root, {
                categoryField: "period",
                renderer: am5xy.AxisRendererX.new(root, {
                    minGridDistance: 50, // Increased distance between labels
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9
                }),
                tooltip: am5.Tooltip.new(root, {})
            })
        );

        // Adjust x-axis labels for better readability
        xAxis.get("renderer").labels.template.setAll({
            rotation: 0, // No rotation needed since we only have 5 weeks
            centerY: am5.p50,
            centerX: am5.p50,
            paddingTop: 10,
            fontSize: 12
        });

        xAxis.data.setAll(data);

        var yAxis = chart.yAxes.push(
            am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {
                    minGridDistance: 30
                })
            })
        );

        // Format y-axis labels with peso sign
        yAxis.get("renderer").labels.template.setAll({
            fontSize: 12,
            // Add peso sign to y-axis labels
            text: "₱{value}"
        });

        // Add revenue series only
        var series1 = chart.series.push(
            am5xy.ColumnSeries.new(root, {
                name: "Total Sales (Weekly)",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "revenue",
                categoryXField: "period",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "₱{valueY}"
                })
            })
        );

        // Customize columns - highlight current week
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
            width: am5.percent(70) // Make columns wider
        });

        series1.data.setAll(data);

        // Add cursor
        chart.set("cursor", am5xy.XYCursor.new(root, {
            behavior: "none" // Disable cursor behavior since we're not zooming or panning
        }));

        // Add a label for current week
        if (currentWeekIndex >= 0) {
            // Add a vertical range to highlight current week
            var rangeDataItem = xAxis.makeDataItem({
                category: data[currentWeekIndex].period,
                endCategory: data[currentWeekIndex].period
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
        series1.appear(1000);

        // After chart is fully loaded, add a "You are here" indicator for current week
        if (currentWeekIndex >= 0) {
            setTimeout(function () {
                const container = document.getElementById("chartdiv");
                const indicator = document.createElement("div");
                indicator.className = "current-week-indicator";
                indicator.textContent = " ";
                container.appendChild(indicator);

                // Position the indicator - need to wait for rendering
                setTimeout(function () {
                    const columns = document.querySelectorAll("#chartdiv .am5-column");
                    if (columns.length > currentWeekIndex) {
                        const column = columns[currentWeekIndex];
                        const rect = column.getBoundingClientRect();
                        const containerRect = container.getBoundingClientRect();

                        indicator.style.top = "60px";
                        indicator.style.left = (rect.left - containerRect.left + rect.width / 2) +
                            "px";
                    }
                }, 1500);
            }, 1500);
        }
    });
</script>

<!-- HTML -->
<div id="chartdiv"></div>