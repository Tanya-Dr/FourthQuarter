<?php
interface Command
{
    public function execute(Editor $editor) : void;
}

abstract class EditBuffer implements Command{
    protected $start;
    protected $end;
    protected $receiver;
    public function __construct(Receiver $receiver, int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->receiver = $receiver;
    }
}
class Copy extends EditBuffer
{
    private $name = 'copy';

    public function execute(Editor $editor) : void
    {
        $this->receiver->log($this->name);
        $editor->buffer = substr($editor->curText, $this->start, $this->end - $this->start);
        echo "Copied to buffer '{$editor->buffer}'.\n";
    }
}
class Cut extends EditBuffer
{
    private $name = 'cut';

    public function execute(Editor $editor) : void
    {
        $this->receiver->log($this->name);
        $editor->buffer = substr($editor->curText, $this->start, $this->end - $this->start);
        $editor->curText = substr_replace($editor->curText, '', $this->start, $this->end - $this->start);
        echo "Cut to buffer '{$editor->buffer}'.\n";
    }
}
class Paste implements Command
{
    private $start;
    private $receiver;
    private $name = 'paste';

    public function __construct(Receiver $receiver, int $start)
    {
        $this->start = $start;
        $this->receiver = $receiver;
    }

    public function execute(Editor $editor) : void
    {
        $this->receiver->log($this->name);
        $editor->curText = substr_replace($editor->curText, $editor->buffer, $this->start, 0);
        echo "Paste from buffer '{$editor->buffer}' to text.\n";
    }    
}

class Receiver
{
    public function log(string $operationName) : void
    {
        echo "Logging operation $operationName. \n";
    }
}

class Editor
{
    public $curText;
    public $buffer = '';

    public function __construct(string $text)
    {
        $this->curText = $text;
    }

    public function doCommand(Command $command) : void
    {
        echo "Do command: \nCurrent text: '{$this->curText}'\n";
        $command->execute($this);
        echo "Current text: '{$this->curText}'\n\n";
    }
}

/**
 * Клиентский код.
 */
$msWorld = new Editor('hello');
$receiver = new Receiver();
$msWorld->doCommand(new Copy($receiver, 0, 2));
$msWorld->doCommand(new Cut($receiver, 0, 2));
$msWorld->doCommand(new Paste($receiver, 2));
$msWorld->doCommand(new Paste($receiver, 0));

$msWorld->doCommand(new Copy($receiver, 2, 4));
$msWorld->doCommand(new Paste($receiver, 0));