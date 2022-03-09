<?php
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

function binarySearch($myArray, $num){
    $left = 0;
    $right = count($myArray) - 1;
    while($left <= $right){
        $middle = floor(($right + $left)/2);
        if($myArray[$middle] == $num){
            return $middle;
        }
        elseif($myArray[$middle] > $num){
            $right = $middle - 1;
        }
        elseif($myArray[$middle] < $num){
            $left = $middle + 1;
        }
    }
    return null;
}

function deleteEl(&$arr, $num){
    $res = binarySearch($arr, $num);
    if(is_numeric($res)){
        array_splice($arr, $res, 1);
        deleteEl($arr, $num);
    }
}

$arr = [10,2,3,3,4,10,5];
quickSort($arr, 0, count($arr) - 1);
print_r($arr);
deleteEl($arr, 3);
print_r($arr);
