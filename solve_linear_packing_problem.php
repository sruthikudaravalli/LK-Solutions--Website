<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        session_start();
         $arr = $_SESSION["array"];
        $cap = $_SESSION["capacity"];
        class BinPacking{
            public static $solution; //solution array (which cointain will each object go)
            public static $wcontainer; //container weight array
            public static $totalcontainer; //no. of containers in the first instance
            public static $tabu; //tabu array
            
            public function __construct()
            {
                $this->arr = explode(" ", $_SESSION["array"]);
                $this->cap = $_SESSION["capacity"];
            }

            public function bpp(){
               $_SESSION["weightTooLarge"] = 0;
                for($i=0; $i<sizeof($this->arr);$i++){
                  if ((int) $this->arr[$i] >= (int) $this->cap) {
                        if(isset($_SESSION["array"])) {
                       echo "Please check your input!  One of your objects has a weight higher than the weight limit.";
                       echo "<br />";
                        }
                       header("location:index.php#linear_packing_problem");
                  }
                }
                self::$solution = new SplFixedArray(sizeof($this->arr));
                self::$totalcontainer = round(sizeof($this->arr)*.80);
                self::$wcontainer = new SplFixedArray(self::$totalcontainer);
                self::$tabu = new SplFixedArray(sizeof($this->arr));
                $this->fill();
                $this->firstSolution();
              //  echo "got firstSolution\n";
              //  $this->tabuSearch();
              //  echo "got tabluSearch\n";
                
            }
             
            public function fill(){
                for($i=0; $i<sizeof($this->arr);$i++){
                    self::$solution[$i] = 0;
                    self::$tabu[$i] = 0;
                }
                
                for($i=0; $i<self::$totalcontainer;$i++){
                    self::$wcontainer[$i] = 0;
                }                               
            }
            
            public function firstSolution(){
              //  echo "size of thisarray: ", sizeof($this->arr);
                for($i=0; $i<sizeof($this->arr);$i++){
                    $r = rand(0,(self::$totalcontainer-1));
                    if(($this->arr[$i]+self::$wcontainer[$r])>$this->cap){
                        $i--;
                    }
                    else{
                        self::$wcontainer[$r]=self::$wcontainer[$r]+$this->arr[$i];
                        self::$solution[$i]=$r;
                    }
                }
             //   echo "out of this array\n";
				if(isset($_SESSION["array"])) {
               echo "<table width='20%' >\n";
               echo "</table>\n	
               <table width='20%'  border='1' cellspacing='0' cellpadding='0' >
                  <tr>
                     <td align=center>Object</td>
                     <td align=center>Weight</td>
                     <td align=center>Container</td>
                  </tr>\n";
                  
                  $objects = array();
                  $weights = array();
                  $containers = array();

                  for($i=0; $i<sizeof($this->arr);$i++){
                     array_push($objects, $i);
                     array_push($weights, $this->arr[$i]);
                     array_push($containers, self::$solution[$i]);
                     echo("<tr>
                        <td height='40' align=center><p style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #CC6600;'>&nbsp;".$i."&nbsp;</td>
                        <td height='40' align=center><p style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #CC6600;'>&nbsp;".$this->arr[$i]."&nbsp;</td>
                        <td height='40' align=center><p style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #CC6600;'>&nbsp;".self::$solution[$i]."&nbsp;</td>
                     </tr>");
                  }
                  $_SESSION['objects'] = $objects;
                  $_SESSION['weights_bpp'] = $weights;
                  $_SESSION['containers'] = $containers;

               echo "</table>\n";
            }
            header("location:index.php#linear_packing_problem");  
            }

            public function tabuSearch(){
                $tenure = 2; //TabuTenure
                $actual_container = 0; //Actual container of the object
                $weight_dif = 0; //TotalCap - Weight of actual_container
                $weight_calc = 0; //TotalCap - Weight of the comparison container + object[j]'s weight
                $w = 0;                                
                $discarded = new SplFixedArray(self::$totalcontainer);                               
                for($i=0; $i<8;$i++){//Instances
                    //First we check if a cointainer is completely full or empty, and discard them from the search
                    for($i=0; $i<sizeof($discarded);$i++){
                        if(self::$wcontainer[$i] == $this->cap || self::$wcontainer[$i] == 0)
                            $discarded[$i] = 1;
                        else
                            $discarded[$i] = 0;
                    }
                    
                    for($j=0; $j<sizeof($this->arr);$j++){ //Check every object
                        if(self::$tabu[$j]<=$i){
                            $weight_dif = $this->cap-self::$wcontainer[self::$solution[$j]];
                            $actual_container = self::$solution[$j];
                            for($k=0; $k<self::$totalcontainer;$k++){//Check every container
                                if((self::$solution[$j]!=$k) && $discarded[$k] == 0){
                                    $weight_calc = $this->cap-self::$wcontainer[$k]-$this->arr[$j];
                                    if($weight_calc>=0 && $weight_calc<$weight_dif){//if the container doesn't overload and the change is better
                                        self::$wcontainer[$k] = $this->cap-$weight_calc;
                                        self::$wcontainer[self::$solution[$j]] = self::$wcontainer[self::$solution[$j]]-$this->arr[$j];
                                        self::$solution[$j] = $k;
                                        self::$tabu[$j] = $i+$tenure;
                                    }
                                }
                            }
                        }
                    } //end for j
                }
				
				echo("<table width='20%' ><tr><td align=center style='color:#456789;font-size:300%;' >Tabu Search</td></tr></table>");
                echo("<table width='20%'  border='1' cellspacing='0' cellpadding='0' ><tr><td align=center>Object</td><td align=center>Weight</td><td align=center>Container</td></tr>");
                for($i=0; $i<sizeof($this->arr);$i++){
					echo("<tr>
						<td height='40' align=center><p style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #CC6600;'>&nbsp;".$i."&nbsp;</td>
						<td height='40' align=center><p style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #CC6600;'>&nbsp;".$this->arr[$i]."&nbsp;</td>
						<td height='40' align=center><p style='font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12px;color: #CC6600;'>&nbsp;".self::$solution[$i]."&nbsp;</td>
					</tr>");
                }
				echo("</table>");
            }
        }        
             
        ?>
    </body>
</html>
