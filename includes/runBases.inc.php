<?php

    function runBases($_batResult, $_runner1, $_runner2, $_runner3, $_outs){
      
        $_runs = 0; // track runs
        $_new_outs = $_outs;

        // if home run
        if($_batResult == 4){
          
            // score a run for the hitter
            $_runs = $_runs + 1; 
  
            // if runner is on base, runner scores and base count is reset
            $_runners = array($_runner1, $_runner2, $_runner3);
            foreach($_runners as &$_runner){
                if ($_runner > 0){
                    $_runs = $_runs + 1;
                    $_runner = 0;
                }
            }

            // append runs, outs to runners array
            array_push($_runners, $_runs, $_new_outs);

            // return runners, runs, outs
            return $_runners;
     
        }
        // if single, double, or triple
        if($_batResult > 0 && $_batResult < 4){
                
            // if no runners on bases
            if (($_runner1 == 0) && ($_runner2 == 0) && $_runner3 == 0){
              $_runner1 = $_runner1 + $_batResult;
            }

            // if runner(s) on base
            else{
                // "&" operator modifies the variable in place
                foreach(array(&$_runner1, &$_runner2, &$_runner3) as &$_runner){
                    if ($_runner > 0){
                    $_runner = $_runner + $_batResult;
                        
                        // if runner has exceed bases, runner scores and base count is reset
                        if($_runner > 3){
                            $_runner = 0;
                            $_runs = $_runs + 1; 
                        }
                    }
                }

                // find the new runner slot
                foreach(array(&$_runner1, &$_runner2, &$_runner3) as &$_runner){
                    if ($_runner == 0){
                        $_runner = $_runner + $_batResult;
                        break;
                    }
                }
            }
            // return runners and runs
            return array($_runner1, $_runner2, $_runner3, $_runs, $_new_outs);
        }
        
        // if out (could add an out system here later)
        else{
            if($_outs >= 2){
                $_new_outs = 0;
                $_runner1 = 0;
                $_runner2 = 0;
                $_runner3 = 0;
            }else{
                $_new_outs = $_outs + 1; 
            }

            // return runners and runs
            return array($_runner1, $_runner2, $_runner3, $_runs, $_new_outs);
        } 
    }
        

