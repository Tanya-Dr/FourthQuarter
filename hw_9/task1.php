<?php
function createArray(int $lenght){
    $arr = [];
    for($i = 0; $i < $lenght; $i++){
        $arr[] = rand(0, $lenght);
    }
    return $arr;
}

function bubbleSort(&$array){
    for($i = 0; $i < count($array); $i++){
        $count = count($array);
        for($j = $i + 1; $j < $count; $j++){
            if($array[$i] > $array[$j]){
                $temp = $array[$j];
                $array[$j] = $array[$i];
                $array[$i] = $temp;
            }
        }
    }
}

function shakerSort(&$array){
    $n = count($array);
    $left = 0;
    $right = $n - 1;
    do{
        for($i = $left; $i < $right; $i++){
            if($array[$i] > $array[$i + 1]){
                // list($array[$i], $array[$i + 1]) = array($array[$i + 1],$array[$i]);
                $temp = $array[$i];
                $array[$i] = $array[$i + 1];
                $array[$i + 1] = $temp;
            }
        }
        $right--;
        for($i = $right; $i > $left; $i--){
            if($array[$i] < $array[$i - 1]){
                // list($array[$i], $array[$i - 1]) = array($array[$i - 1],$array[$i]);
                $temp = $array[$i];
                $array[$i] = $array[$i - 1];
                $array[$i - 1] = $temp;
            }
        }
        $left++;
    }while($left <= $right);
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

function heapify(&$arr, $countArr, $i)
{
    $largest = $i;
    $left = 2 * $i + 1;
    $right = 2 * $i + 2;
    if($left < $countArr && $arr[$left] > $arr[$largest])
        $largest = $left;
    if($right < $countArr && $arr[$right] > $arr[$largest])
        $largest = $right;
    if($largest != $i){
        $swap = $arr[$i];
        $arr[$i] = $arr[$largest];
        $arr[$largest] = $swap;
        heapify($arr, $countArr, $largest);
    }
}
function heapSort(&$arr){
    $countArr = count($arr);
    for($i = $countArr / 2 - 1; $i >= 0; $i--)
        heapify($arr, $countArr, $i);
    for($i = $countArr-1; $i >= 0; $i--){
        $temp = $arr[0];
        $arr[0] = $arr[$i];
        $arr[$i] = $temp;
        heapify($arr, $i, 0);
    }
}

$array = createArray(10000);
$bubbleSortArray = $array;
$shakerSortArray = $array;
$quickSortArray = $array;
$heapSortArray = $array;

// echo "unsorted array:\n"; 
// print_r($array);

$start_time = microtime(true);
bubbleSort($bubbleSortArray);
$end_time = microtime(true);
echo "start time = $start_time \nend time = $end_time \n";
echo "Bubble sort time: " . ($end_time-$start_time) . "\n\n";
// echo "Bubble sorted array:\n"; 
// print_r($bubbleSortArray);

$start_time = microtime(true);
shakerSort($shakerSortArray);
$end_time = microtime(true);
echo "start time = $start_time \nend time = $end_time \n";
echo "Shaker sort time: " . ($end_time-$start_time) . "\n\n";
// echo "Shaker sorted array:\n";
// print_r($shakerSortArray);

$start_time = microtime(true);
quickSort($quickSortArray, 0, count($quickSortArray) - 1);
$end_time = microtime(true);
echo "start time = $start_time \nend time = $end_time \n";
echo "Quick sort time: " . ($end_time-$start_time) . "\n\n";
// echo "Quick sorted array:\n";
// print_r($quickSortArray);

$start_time = microtime(true);
heapSort($heapSortArray);
$end_time = microtime(true);
echo "start time = $start_time \nend time = $end_time \n";
echo "Heap sort time: " . ($end_time-$start_time) . "\n\n";
// echo "Heap sorted array:\n";
// print_r($heapSortArray);