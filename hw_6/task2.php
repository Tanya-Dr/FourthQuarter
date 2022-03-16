<?php
class Checkout
{
    private $payStrategy;
    private $totalSum;
    private $phoneNumber;

    public function __construct(PayStrategy $payStrategy)
    {
        $this->payStrategy = $payStrategy;
    }

    public function setStrategy(PayStrategy $payStrategy)
    {
        $this->payStrategy = $payStrategy;
    }

    public function setTotalSum(int $total)
    {
        $this->totalSum = $total;
    }

    public function setphoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function payment() : void
    {
        echo "Order: make payment using the strategy\n";
        $result = $this->payStrategy->makePayment($this->totalSum, $this->phoneNumber);
        echo $result . "\n";
    }
}

interface PayStrategy
{
    public function makePayment(int $total, string $phone) : string;
}

class QiwiStrategy implements PayStrategy
{
    public function makePayment(int $total, string $phone) : string
    {
        $res = "Qiwi System: \nTotal sum = $total \nPhone number = $phone";
        return $res;
    }
}

class YandexStrategy implements PayStrategy
{   
    private $yaCommission;
    public function __construct(int $yaCommission)
    {
        $this->yaCommission = $yaCommission;
    }
    public function makePayment(int $total, string $phone) : string
    {
        $sum = $total + $this->yaCommission;
        $res = "Yandex System: \nTotal sum = $sum \nComission = {$this->yaCommission} \nPhone number = $phone";
        return $res;
    }
}

class WebMoneyStrategy implements PayStrategy
{
    private $WMDiscount;
    public function __construct(int $WMDiscount = 10)
    {
        $this->WMDiscount = $WMDiscount;
    }
    public function makePayment(int $total, string $phone) : string
    {
        $sum = $total * (1 - $this->WMDiscount/100);
        $res = "WebMoney System: \nTotal sum = $sum \nDiscount = {$this->WMDiscount}%\nPhone number = $phone";
        return $res;
    }
}

/**
 * Клиентский код.
 */
$order = new Checkout(new QiwiStrategy());
$order->setTotalSum(1000);
$order->setphoneNumber('+7999-999-99-99');
echo "Client: Qiwi Strategy.\n";
$order->payment();

echo "\n";

echo "Client: Yandex Strategy.\n";
$order->setStrategy(new YandexStrategy(200));
$order->payment();

echo "\n";

echo "Client: WebMoney Strategy.\n";
$order->setStrategy(new WebMoneyStrategy());
$order->payment();

echo "\n";

echo "Client: WebMoney Strategy.\n";
$order->setStrategy(new WebMoneyStrategy(20));
$order->payment();