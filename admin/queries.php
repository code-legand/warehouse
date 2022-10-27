<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    if(isset($_POST['query'])){
        require_once 'connect.php';
        $query = $_POST['query'];
        try {
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return;
        }
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $columns = array_keys($result[0]);
        $rows = array_values($result);
        $num_rows = count($rows);
        $num_columns = count($columns);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/warehouse/css/styles.css">
    <link rel="font" href="">
    <link rel="apple-touch-icon" sizes="180x180" href="/warehouse/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/warehouse/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/warehouse/img/favicon-16x16.png">
    <link rel="manifest" href="/warehouse/img/site.webmanifest">
    <script src="#"></script>
    <title>Warehouse Management system</title>
</head>
<body>
    <header>Order Management</header>
    <div id="msg">
        <?php 
           if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            } 
        ?>
    </div>
    <div>
        <button>Query Log</button>
        <form action="queries.php" method="post" autocomplete="off">
            <label for="query">Query : </label>
            <input type="text" name="query" id="query">
            <input type="submit" value="RUN">
            <button onclick="location.href='queries.php'; return false;">Clear</button>
        </form>
        <div>
            <?php
                if(isset($error)){
                    echo $error;
                }
                else{
                    echo "<table>";
                    echo "<tr>";
                    for($i=0; $i<$num_columns; $i++){
                        echo "<th>".$columns[$i]."</th>";
                    }
                    echo "</tr>";
                    for($i=0; $i<$num_rows; $i++){
                        echo "<tr>";
                        for($j=0; $j<$num_columns; $j++){
                            echo "<td>".$rows[$i][$columns[$j]]."</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            ?>
        </div>
    </div>
    <div>
        <button onclick="location.href='dashboard.php'; return false;">Back to Home</button>
    </div> 

</body>
</html>