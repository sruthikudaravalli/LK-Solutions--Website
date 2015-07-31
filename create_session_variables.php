<?php
        session_start();
        $_SESSION["array"] = $_POST['item_weights'];
        $_SESSION["capacity"] = $_POST['weight_limit'];
        header("location:index.php#linear_packing_problem");   
        ?>