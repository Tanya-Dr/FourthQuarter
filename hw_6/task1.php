<?php
class HandHunter implements SplSubject
{
    public $state;
    private $candidates;

    public function __construct()
    {
        $this->candidates = new SplObjectStorage();
    }

    public function attach(SplObserver $candidate) : void
    {
        echo "HandHunter: Attached an candidate.\n";
        $this->candidates->attach($candidate);
    }

    public function detach(SplObserver $candidate) : void
    {
        $this->candidates->detach($candidate);
        echo "HandHunter: Detached an candidate.\n";
    }

    public function notify() : void
    {
        echo "HandHunter: Notifying candidates...\n";
        foreach ($this->candidates as $candidate) {
            $candidate->update($this);
        }
    }

    public function vacancyOccurs() : void
    {
        echo "\nHandHunter: a vacancy occurs.\n";
        $vacancies = ['web-developer','engineer','analyst'];
        $rand_keys = array_rand($vacancies);
        $experience = rand(0, 10);
        $this->state = ['vacancy' => $vacancies[$rand_keys], 'experience' => $experience];

        echo "HandHunter: My state has just changed to: {$this->state['vacancy']} with experience {$this->state['experience']}.\n";
        $this->notify();
    }
}

abstract class Candidate implements SplObserver
{
    protected $name;
    protected $experience;
    protected $email;
    protected $vacancy;

    public function __construct(string $name, string $email, string $vacancy,int $experience)
    {
        $this->name = $name;
        $this->experience = $experience;
        $this->email = $email;
        $this->vacancy = $vacancy;
    }
}

class ActiveSearchingCandidate extends Candidate
{
    public function update(SplSubject $handHunter) : void
    {
        if ($handHunter->state['vacancy'] == $this->vacancy && $this->experience >= $handHunter->state['experience']){
            echo "The letter was send on email {$this->email} to the candidate {$this->name}.\n";
        }
    }
}

class PassiveSearchingCandidate extends Candidate
{
    public function update(SplSubject $handHunter) : void
    {
        if ($handHunter->state['vacancy'] == $this->vacancy && $this->experience >= $handHunter->state['experience']){
            echo "The notification was send to the candidate {$this->name}.\n";
        }
    }
}

/**
 * Клиентский код.
 */
$handHunter = new HandHunter();

$candidateA = new ActiveSearchingCandidate('Tanya','tanya@email.ru','web-developer',7);
$handHunter->attach($candidateA);

$candidateB = new PassiveSearchingCandidate('Kostya','kostya@email.ru','engineer',5);
$handHunter->attach($candidateB);

$candidateC = new PassiveSearchingCandidate('Spider','spider@email.ru','analyst',10);
$handHunter->attach($candidateC);

$handHunter->vacancyOccurs();
$handHunter->vacancyOccurs();

$handHunter->detach($candidateB);

$handHunter->vacancyOccurs();