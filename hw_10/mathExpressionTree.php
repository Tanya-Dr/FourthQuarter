<?php
class Node{
    protected $operation;
    protected $value;
    protected $left;
    protected $right;

    public function setLeft(Node $node){
        $this->left = $node;
    }
    public function setRight(Node $node){
        $this->right = $node;
    }
    public function setOperation($operation){
        $this->operation = $operation;
    }
    public function setValue($val){
        $this->value = $val;
    }

    public function getLeft(){
        return $this->left;
    }
    public function getRight(){
        return $this->right;
    }
    public function getOperation(){
        return $this->operation;
    }
    public function getValue($arVars = []){
        if(isset($this->value)){
            if(is_numeric($this->value)){
                return $this->value;
            }
            foreach($arVars as $key => $val){
                if($key == $this->value){
                    return $val;
                }
            }
            return "Error with variable. You need to set value of the variable " . $this->value . ".";
        }
        return $this->calc($this->getOperation(), $this->getLeft()->getValue($arVars), $this->getRight()->getValue($arVars));
    }
    protected function calc($op, $val1, $val2){
        if(!is_numeric($val1) || !is_numeric($val2)){
            if(preg_match("/Error/",$val1)){
                return $val1;
            }
            if(preg_match("/Error/",$val2)){
                return $val2;
            }
            return "Error";
        }
        $result = 0;
        switch($op){
            case "+":
                $result = $val1 + $val2;
                break;
            case "-":
                $result = $val1 - $val2;
                break;
            case "/":
                if($val2 == 0){
                    return "Error: divider = 0";
                }
                $result = $val1 / $val2;
                break;
            case "*":
                $result = $val1 * $val2;
                break;
            case "^":
                $result = pow($val1, $val2);
                break;
            default:
                $result = "Error: there's no such operation.";
                break;
        }
        return $result;
    }
}

class Expression{
    protected $arStr;
    protected $arOp;
    private $error;
    public $tree;
    protected $str;

    public function setExpression($str){
        $this->str = $str;
    }

    public function builder(){
        $this->arStr = [];
        $this->arOp = [];
        $this->tree = null;
        $this->error = "";
        $this->parse($this->str);
        if(!$this->error){
            $this->tree = $this->setTree($this->arStr, $this->arOp);
        }       
    }

    protected function parse($str){
        $newStr = str_split(str_replace(' ', '', $str));
        for($i = 0; $i < count($newStr); $i++){
            if((isset($newStr[$i + 1]) && preg_match("/^(\+|-|\*|\/|\^|\(|\))$/",$newStr[$i + 1])) || preg_match("/^(\+|-|\*|\/|\^|\(|\))$/",$newStr[$i])){
                $toPush = $newStr[$i];
            }else{
                $toPush = "";
                for($j = $i; $j < count($newStr); $j++){
                    $toPush .= $newStr[$j];
                    $i = $j;
                    if(isset($newStr[$j + 1]) && preg_match("/^(\+|-|\*|\/|\^|\(|\))$/",$newStr[$j + 1])){
                        break;
                    }
                }
                
            }
            $this->arStr[] = $toPush;
        }
        if($this->arStr[0] == ")" || $this->arStr[count($this->arStr) - 1] == "("){
            $this->error = "Error with brackets.";
            return;
        }
        $this->arOp = [];

        $countOp =  preg_match_all("/(\+|-|\*|\/|\^)/", $str);
        $j = 0;
        $bracket = 0;
        for($i = 0; $i < count($this->arStr); $i++){
            if(preg_match("/^(\+|-|\*|\/|\^|\(|\))$/",$this->arStr[$i])){
                if($this->arStr[$i] == "("){
                    $bracket += 1;
                    continue;
                }
                if($this->arStr[$i] == ")"){
                    $bracket -= 1;
                    continue;
                }
                $j += 1;
                $priority = ($this->getPriority($this->arStr[$i]) + $bracket * 3) * 3 * $countOp + 2 * $countOp - $j;
                $this->arOp[] = [
                    'strId' => $i,
                    'op' => $this->arStr[$i],
                    'bracket' => $bracket,
                    'priority' => $priority
                ];
            }
        }
        if($bracket != 0){
            $this->error = "Error with brackets.";
            return;
        }

        usort($this->arOp, function($a, $b){
            if ($a['priority'] == $b['priority']) {
                return 0;
            }
            return ($a['priority'] < $b['priority']) ? -1 : 1;
        });

        foreach($this->arStr as $key => $value){
            $this->arStr[$key] = [
                'strId' => $key,
                'value' => $value
            ];
        }
    }

    protected function getPriority($operation){
        $priority = 0;
        switch($operation){
            case "+":
            case "-":
                $priority = 1;
                break;
            case "/":
            case "*":
                $priority = 2;
                break;
            case "^":
                $priority = 3;
                break;
            default:
                $priority = 0;
                break;
        }
        return $priority;
    }

    protected function setTree($arStr, $arOp){
        $node = new Node();
        if(count($arOp) == 0){
            foreach($arStr as $key=>$value){
                if(!preg_match("/^(\+|-|\*|\/|\^|\(|\))$/",$arStr[$key]['value'])){
                    $leaf = $arStr[$key]['value'];
                    break;
                }
            }
            $node->setValue($leaf);
            return $node;
        }
        $operation = array_shift($arOp);
        $node->setOperation($operation['op']);

        $arStrLeft = array_filter($arStr, function($el) use ($operation) {
            return $el['strId'] < $operation['strId'];
        });
        $arOpLeft = array_filter($arOp, function($el) use ($operation) {
            return $el['strId'] < $operation['strId'];
        });

        $arStrRight = array_filter($arStr, function($el) use ($operation) {
            return $el['strId'] > $operation['strId'];
        });
        $arOpRight = array_filter($arOp, function($el) use ($operation) {
            return $el['strId'] > $operation['strId'];
        });

        $node->setLeft($this->setTree($arStrLeft, $arOpLeft));
        $node->setRight($this->setTree($arStrRight, $arOpRight)); 
        return $node;
    }

    //$arVars = ['x' => valueX, 'y' => valueY] -if there are variables in expression
    public function calc($arVars = []){
        $this->builder();
        if(!$this->error){
            return $this->tree->getValue($arVars);
        }else{
            return $this->error;
        }      
    }
}

$test = new Expression();
$test->setExpression("2.5 + (3 - 1) * (2 + 3 * 4 ^ 2 ) / 10");
echo $test->calc() . PHP_EOL; //answer = 12.5

$test->setExpression("1 + 2 / 0");
echo $test->calc() . PHP_EOL; //answer = error with divider

$test->setExpression("x + 2");
echo $test->calc(['x' => 1]) . PHP_EOL; //answer = 3
echo $test->calc(['y' => 1]) . PHP_EOL; //answer = error with variable

$test->setExpression("2.5 + (x - 1) * (2 + 3 * 4 ^ 2 )) / y");
echo $test->calc(['x' => 3, "y" => 1, "t" => 2]) . PHP_EOL;  //answer = error with brackets

$test->setExpression("2.5 + (x - 1) * (2 + 3 * 4 ^ 2 ) / y");
echo $test->calc(['x' => 3, "y" => 1, "t" => 2]) . PHP_EOL;  //answer = 102.5

$test->setExpression("(x/x)/(x*x)/x");
echo $test->calc(['x' => 0.1]) . PHP_EOL; //answer = 1000

$test->setExpression("((x/x)/(x*x))/x");
echo $test->calc(['x' => 0.1]) . PHP_EOL; //answer = 1000