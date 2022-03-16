<?php
function createArray(int $lenght){
    $arr = [];
    for($i = 0; $i < $lenght; $i++){
        $arr[] = rand(0, $lenght);
    }
    return $arr;
}
function quickSort(&$arr, $low, $high){
    $i = $low;
    $j = $high;
    $middle = $arr[( $low + $high ) / 2];
    do{
        while($arr[$i] < $middle) ++$i;
        while($arr[$j] > $middle) --$j;
        if($i <= $j){
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            $i++; $j--;
        }
    }while($i < $j);
    if($low < $j){
        quickSort($arr, $low, $j);
    }
    if($i < $high){
        quickSort($arr, $i, $high);
    }
}

function linearSearch($myArray, $num){
    $iter = 0;
    $count = count($myArray);
    for($i = 0; $i < $count; $i++){
        $iter += 1;
        if($myArray[$i] == $num){
            echo "linear search iteration number = " . $iter . PHP_EOL;
            return $i;
        }            
        elseif($myArray[$i] > $num){
            echo "linear search iteration number = " . $iter . PHP_EOL;
            return null;
        }
    }
    echo "linear search iteration number = " . $iter . PHP_EOL;
    return null;
}

function binarySearch($myArray, $num){
    $iter = 0;
    $left = 0;
    $right = count($myArray) - 1;
    while($left <= $right){
        $iter += 1;
        $middle = floor(($right + $left)/2);
        if($myArray[$middle] == $num){
            echo "binary search iteration number = " . $iter . PHP_EOL;
            return $middle;
        }
        elseif($myArray[$middle] > $num){
            $right = $middle - 1;
        }
        elseif($myArray[$middle] < $num){
            $left = $middle + 1;
        }
    }
    echo "binary search iteration number = " . $iter . PHP_EOL;
    return null;
}

function interpolationSearch($myArray, $num){
    $iter = 0;
    $start = 0;
    $last = count($myArray) - 1;
    while(($start <= $last) && ($num >= $myArray[$start]) && ($num <= $myArray[$last])){
        $iter += 1;
        $pos = floor($start + ((($last - $start) / ($myArray[$last] - $myArray[$start])) * ($num - $myArray[$start])));
        if($myArray[$pos] == $num){
            echo "interpolation search iteration number = " . $iter . PHP_EOL;
            return $pos;
        }
        if($myArray[$pos] < $num){
            $start = $pos + 1;
        } else {
            $last = $pos - 1;
        }
    }
    echo "interpolation search iteration number = " . $iter . PHP_EOL;
    return null;
}

$arr = createArray(100);
quickSort($arr, 0, count($arr) - 1);
print_r($arr);
$linearRes = linearSearch($arr, 50);
$binaryRes = binarySearch($arr, 50);
$interpolationRes = interpolationSearch($arr, 50);