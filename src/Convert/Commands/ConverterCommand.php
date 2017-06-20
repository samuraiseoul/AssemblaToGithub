<?php
namespace Convert\Commands;

use Convert\Readers\AssemblaReader;
use Convert\Writers\GithubWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConverterCommand extends Command
{
    const FILENAME_ARG = 'filename';
    const USERNAME = 'username';
    const TOKEN = 'token';
    const REPOSITORY = 'repository';
    const NAME_MAP = 'nameMap';
    
    /** @var  AssemblaReader */
    private $reader;
    /** @var  GithubWriter */
    private $writer;
    
    public function __construct(AssemblaReader $reader, GithubWriter $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
        parent::__construct("assembla:github");
    }
    
    protected function configure()
    {
        $this->setDescription("Converts assembla tickets to github issues and adds them to the project.")
        ->addArgument(self::FILENAME_ARG, InputArgument::REQUIRED, 'The filename exported from assembla.')
            ->addArgument(self::USERNAME, InputArgument::REQUIRED, "Github username")
            ->addArgument(self::TOKEN, InputArgument::REQUIRED, "Github token")
            ->addArgument(self::REPOSITORY, InputArgument::REQUIRED, "Github repository")
            ->addOption(self::NAME_MAP, 'm', InputArgument::OPTIONAL, 'A list of assignees with their assembla id and github username delimited by an ":" e.g. djio_039458:github,cjdhs_293ek9:username', '');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $usermap = $this->getUserMap($input);
        $return = $this->reader->read($input->getArgument(self::FILENAME_ARG), $usermap);
        $username = $input->getArgument(self::USERNAME);
        $token = $input->getArgument(self::TOKEN);
        $repository = $input->getArgument(self::REPOSITORY);
        $this->writer->write($username, $token, $repository, $return);
    }
    
    private function getUserMap(InputInterface $input) {
        $users = explode(',', $input->getOption(self::NAME_MAP));
        $map = [];
        foreach($users as $user){
            list($assembla, $github) = explode(':', $user);
            $map[$assembla] = $github;
        }
        return $map;
    }
}