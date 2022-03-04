<?php
class SquareAreaLib
{
    public function getSquareArea(float $diagonal)
    {
        $area = ($diagonal**2)/2;
        return $area;
    }
}
class CircleAreaLib
{
    public function getCircleArea(float $diameter)
    {
        $area = (M_PI * ($diameter**2))/4;
        return $area;
    }
}

interface ISquare
{
    function squareArea(float $sideSquare);
}
class SquareAdapter implements ISquare
{
    private $square;
    public function __construct(SquareAreaLib $square)
    {
        $this->square = $square;
    }
    public function squareArea(float $sideSquare)
    {
        $diagonal = $sideSquare * sqrt(2);
        return $this->square->getSquareArea($diagonal);
    }
}

interface ICircle
{
    function circleArea(float $circumference);
}
class CircleAdapter implements ICircle
{
    private $circle;
    public function __construct(CircleAreaLib $circle)
    {
        $this->circle = $circle;
    }
    public function circleArea(float $circumference)
    {
        $diameter = $circumference / M_PI;
        return $this->circle->getCircleArea($diameter);
    }
}

function testAdapters(float $sideSquare, float $circumference)
{
    $squareAdapter  = new SquareAdapter(new SquareAreaLib());
    echo "Square area (with side = " . $sideSquare . ") is equal " . $squareAdapter->squareArea($sideSquare) . "\n";
    $circleAdapter  = new CircleAdapter(new CircleAreaLib());
    echo "Circle area (with circumference = " . $circumference . ") is equal " . $circleAdapter->circleArea($circumference). "\n";
}

testAdapters(1, sqrt(M_PI));
testAdapters(4, sqrt(M_PI)*4);
testAdapters(0.5, sqrt(M_PI)*3);