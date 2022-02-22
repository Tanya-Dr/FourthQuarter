<?php
//Предположим, что у нас есть приложение, способное отправлять оповещения тремя способами: SMS, Email и Chrome Notification (CN).В качестве базового класса — своеобразного «нулевого пациента» —выберем вариант вовсе без оповещений. А реализации различных способов отправки станут декораторами-обёртками.

interface INotification
{
    public function notify(): string;
}
class BaseNotification implements INotification
{
    private $text;
    public function __construct(string $text)
    {
        $this->text = $text;
    }
    public function notify(): string
    {
        return "Text notification: " . strtoupper($this->text) . "\n";
    }
}

abstract class Decorator implements INotification
{
    protected $content = null;
    public function __construct(INotification $content)
    {
        $this->content = $content;
    }
}
class SMS extends Decorator
{
    public function notify(): string
    {
        return "SMS begin \n" . $this->content->notify() . "SMS end!\n";
    }
}
class Email extends Decorator
{
    public function notify(): string
    {
        return "Email begin \n" . $this->content->notify() . "Email end!\n";
    }
}
class CN extends Decorator
{
    public function notify(): string
    {
        return "Chrome Notification begin \n" . $this->content->notify() . "Chrome Notification end!\n";
    }
}

function clientCode(INotification $component)
{
    echo "RESULT: \n" . $component->notify();
}
$allNotification = 
    new SMS(
        new CN(
            new Email(
                new BaseNotification("All notification!")
            )
        )        
    );
echo "All notification is ON:\n";
clientCode($allNotification);
echo "\n\n";

$SMSNotification = 
    new SMS(
        new BaseNotification("SMS notification!")
    );
echo "SMS notification is ON:\n";
clientCode($SMSNotification);
echo "\n\n";

$SMSandCNNotification = 
    new Email(
        new Sms(
            new BaseNotification("Email and Sms notification!")
        )
    );
echo "SMS and Email notification is ON:\n";
clientCode($SMSandCNNotification);