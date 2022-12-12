<?php
    session_start();
    if(!(isset($_SESSION['adminname']))) {
        $_SESSION['message'] = 'You must be logged in to view this page';
        header('Location: login.php');
        return;
    }
    require_once 'querylog.php';
    if(isset($_POST['query'])){
        $_SESSION['query'] = $_POST['query'];
        header('Location: queries.php');
        return;
    }
    if(isset($_SESSION['query'])){
        require_once 'connect.php';
        try {
            $stmt = $pdo->prepare($_SESSION['query']);
            $stmt->execute();
            
            // echo $stmt->debugDumpParams();

            $_SESSION['query_status'] = 'success';
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $columns = array_keys($result[0]);
            $rows = array_values($result);
            $num_rows = count($rows);
            $num_columns = count($columns);
            storequery($_SESSION['query']);
        } 
        catch (\Throwable $th) {
            // $error = $th->getMessage();          //removed for security reasons
            $_SESSION['query_status'] = 'error';
        }
    }

    $logs = printquery();
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
    <title>Warehouse Management system</title>
</head>

<body class="text-center d-flex justify-content-center">
<main class="p-5 m-auto scroll-enable">
    <?php 
        if (isset($_SESSION['message'])) {
        echo ('<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">'.
                $_SESSION['message'].
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'.
            '</div>');      
        unset($_SESSION['message']);
        } 
    ?>
    <div>
        <h1 class="h3 my-3 fw-normal">Query Manager</h1>
    </div>
    <div>
        <div class="container px-0">
            <button class="w-100 btn btn-lg btn-dark mt-2 mb-4" id="log-trigger" onclick="resize();">Show Log</button>
            <!-- <div id="log-tab" style="display: none;"> -->
            <div class="form-floating" id="log-tab" style="display: none;">
                <textarea class="form-control" cols="100" style="height: 200px" readonly
                ><?php 
                    try {
                        foreach($logs as $timestamp => $query){
                            echo '['.$timestamp.']' . ' : ' . $query . "\n";
                        }
                    }
                    catch (\Throwable $th) {
                        $_SESSION['message'] = 'No queries logged';
                    }
                ?>
                </textarea>
            </div>            
        </div>
        <div class="container px-0">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-10 px-0">
                    <form action="queries.php" method="post" autocomplete="off">
                        <div class="form-floating">
                            <input type="text" class="form-control mt-2" name="query" id="query" placeholder="Query" autofocus required>
                            <label for="query">Query</label>
                        </div>

                        <input class="w-100 btn btn-lg btn-dark mt-4" type="submit" value="RUN">
                        <button class="w-100 btn btn-lg btn-dark mt-2 mb-4" onclick="location.href='queries.php'; return false;">Clear</button>
                    </form>
                </div>
            </div>
            <div class="scroll-enable">
                <?php
                    if(isset($_SESSION['query_status'])){
                        if($_SESSION['query_status'] == 'success'){
                            echo '<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Query executed successfully
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                            echo '<table class="table p-2 table-striped table-hover">';
                            echo '<thead class="table-dark"><tr>';
                            for($i = 0; $i < $num_columns; $i++){
                                echo '<th>'.$columns[$i].'</th>';
                            }
                            echo '</tr></thead><tbody class="align-middle">';
                            for($i = 0; $i < $num_rows; $i++){
                                echo '<tr>';
                                for($j = 0; $j < $num_columns; $j++){
                                    echo '<td>'.$rows[$i][$columns[$j]].'</td>';
                                }
                                echo '</tr>';
                            }
                            echo '</tbody></table>';
                        } 
                        else {
                            echo '<div id="msg" class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Query failed
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                        }
                        unset($_SESSION['query_status']);
                        unset($_SESSION['query']);   
                    }              
                ?>
            </div>
        </div>        
    </div>
    <div class="pt-5">
        <button class="fixed-bottom w-100 btn btn-lg btn-dark mt-4" onclick="location.href='dashboard.php'; return false;">Back to Home</button>
    </div> 
</main>
</body>
</html>