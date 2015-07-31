<!doctype html>

<html>

    <head>
        <meta charset="UTF-8">
        <title>Knapsack Packing</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

        <style type="text/css">
            td{
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Knapsack Packing Problem</h1>
                    <hr>
                    <pre>
<span class="glyphicon glyphicon-ok"></span> Two arrangements for entry, one of weights and other values are required.<br/>
<span class="glyphicon glyphicon-ok"></span> The algorithm randomly determines the objects that best fit in the container, based on its weight.<br />
<span class="glyphicon glyphicon-ok"></span> When in the solution there is a (1), it means that the object is within the container and otherwise will be (0).
                    </pre>
                    <br />
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12">
                    
                    <?php
                        session_start();
                        
                        // Crashes without it
                        date_default_timezone_set('America/Monterrey');
                        
                        // Needed PHP Doc (include in it Backpack.php and Item.php)
                        include 'Knapsack.php';

                        // Inputs
                        $weights = explode(" ", $_POST['item_weights']);
                        $values = explode(" ", $_POST['item_costs']);
                        $capacity = $_POST['weight_limit'];
                        $_SESSION["weights"] = $weights;
                        $_SESSION["values"] = $values;
                        $_SESSION["capacity"] = $capacity;
                        
                        // Object
                        $backpack = new Knapsack($weights, $values, $capacity);
                        
                        //Show table
                        $backpack->show();
                        
                        // Results
                        echo("<div class='panel panel-info'>");
                        echo("<div class='panel-heading'>Results</div>");
                        echo("<div class='panel-body'>");
                        echo("<p><b>Capacity of the bag: </b>" . $backpack->getCapacity() . "</p>");
                        echo("<p><b>Total weight: </b>" . $backpack->getTotalWeight() . "</p>");
                        echo("<p><b>Total value: </b>" . $backpack->getTotalValue() . "</p>");
                        echo("<p><b>Total items: </b>" . $backpack->getTotalItems() . "</p>");
                        
                        echo("<p><b>Items: </b>");
                        print_r($backpack->getItems());
                        echo("</p>");
                        
                        echo("<p><b>Weights: </b>");
                        print_r($backpack->getWeights());
                        echo("</p>");
                        
                        echo("<p><b>Values: </b>");
                        print_r($backpack->getValues());
                        echo("</p>");
                        echo ("</div></div>");
                    
                        header("location:index.php#knapsack_problem");
                    ?>
                    <br />
                    <hr />
                </div>
            </div>
        </div>

        <!-- Latest compiled and minified JavaScript -->
        <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

    </body>

</html>