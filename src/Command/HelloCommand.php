<?php


namespace App\Command;


use App\Service\GreetingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    /**
     * @var GreetingService
     */
    private $greetingService;

    /**
     * HelloCommand constructor.
     * @param GreetingService $greetingService
     */
    public function __construct(GreetingService $greetingService)
    {
        $this->greetingService = $greetingService;
        parent::__construct();
    }

    protected function configure()
    {
        $this   ->setName("app:greet")
                ->setDescription("Saluer l'utilisateur")
                ->addArgument("name", InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $message = $this->greetingService->greet($name);
        $output->write($message);
    }

}