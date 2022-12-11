<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }

    if(isset($_GET['query'])){
      $_SESSION['query']=$_GET['query'];
      header('Location: reports.php');
      return;
    }

    if (isset($_SESSION['query'])) {
      require_once 'connect.php';
      if($_SESSION['query']==1){
        // Average price of all products in each category
        $query = "SELECT category, avg(price) as avg_price FROM storage GROUP BY category";
      }
      else if($_SESSION['query']==2){
        // Number of orders for each product
        $query = "SELECT s.product_name, count(o.quantity) as count FROM storage AS s JOIN orders AS o ON s.storage_id = o.storage_id GROUP BY s.product_name";
      }
      else if($_SESSION['query']==3){
        // Customers who have bought more than 50,000 worth products
        $query = "SELECT u.user_name, sum(s.price*o.quantity) as total FROM storage AS s JOIN orders AS o ON s.storage_id = o.storage_id JOIN users AS u ON o.user_id = u.user_id GROUP BY u.user_name HAVING total > 50000";
      }
      else if($_SESSION['query']==4){
        // Customers who have ordered atleast 2 products of same category
        $query = "SELECT u.user_name, s.category, count(s.category) as count FROM storage AS s JOIN orders AS o ON s.storage_id = o.storage_id JOIN users AS u ON o.user_id = u.user_id GROUP BY u.user_name, s.category HAVING count > 1";
      }
      else if($_SESSION['query']==5){
        // Top 5 products with highest sales
        $query = "SELECT s.product_name, sum(s.price*o.quantity) as total FROM storage AS s JOIN orders AS o ON s.storage_id = o.storage_id GROUP BY s.product_name, o.status HAVING o.status = 'C' ORDER BY total DESC LIMIT 5";
      }
      else{
        $_SESSION['message'] = 'Invalid query number';
        unset($_SESSION['query']);
        header('Location: reports.php');
        return;
      }
      $stmt = $pdo->prepare($query);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); 
      $colnames = array_keys($rows[0]);
      $xlabel = $colnames[0];
      $ylabel = $colnames[1];
      $data = array();
      array_push($data, $colnames);
      foreach ($rows as $row) {
        array_push($data, array_values($row));
      }
      $data = json_encode($data);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/warehouse/css/bootstrap.min.css">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
    <link rel="stylesheet" href="/warehouse/css/userlogin.css">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="/warehouse/js/bootstrap.min.js"></script>
    <script src="/warehouse/js/scripts.js"></script>

    <script src="/warehouse/js/charts.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      var data = <?= $data ?>;
    </script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>Reports</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            }
        ?>
    </div>
    <div>
      <div>
        Select query to run:
        <p>
          <button onclick="location.href='reports.php?query=1';">Average price of all products in each category</button>
        </p>
        <p>
          <button onclick="location.href='reports.php?query=2';">Number of orders for each product</button>
        </p>
        <p>
          <button onclick="location.href='reports.php?query=3';">Customers who have bought more than 50,000 worth products</button>
        </p>
        <p>
          <button onclick="location.href='reports.php?query=4';">Customers who have ordered atleast 2 products of same category</button>
        </p>
        <p>
          <button onclick="location.href='reports.php?query=5';">Top 5 products with highest sales</button>
        </p>
      </div>
      <div>
        Select a chart to display:
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawAreaChart(data));">Area chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawBarChart(data));">Bar chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawColumnChart(data));">Column chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawPieChart(data));">Pie chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawLineChart(data));">Line chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawScatterChart(data));">Scatter chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawHistogramChart(data));">Histogram chart</button>
        </p>
        <p>
          <button onclick="google.charts.setOnLoadCallback(drawCandlestickChart(data));">Candlestick chart</button>
        </p>

      </div>
      <div>
          <?php
            if (isset($rows)){
                echo '<table>
                        <tr>
                        <th>'. $xlabel .'</th>
                        <th>'. $ylabel .'</th>
                        </tr>';
                foreach ($rows as $row) {
                    echo "<tr>";
                    foreach ($row as $value) {
                      echo "<td>$value</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }  
          ?>
        
      </div>
      <div>
        <div id="chart_div" style="width: 100%; height: 500px;"></div>
      </div>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Back to Home</button>
    </div> 
</body>
</html>