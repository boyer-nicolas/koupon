<?php

namespace Niwee\Php;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenEnv extends Command
{
    protected static $defaultName = 'gen:env';
    private string|false $current_dir;

    public function __construct()
    {
        $this->current_dir = getcwd();
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Creates a .env file');
        $this->setHelp('Run this command before launching any other command. It will create a .env file with the required environment variables.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->createEnvFile($io);

        $io->success('Done!');
        return Command::SUCCESS;
    }

    private function createEnvFile($io): bool
    {
        $io->title('Creating the .env');

        // Server Index
        $jwt_secret = $this->gen_password();

        // Copy the .env.example file to .env
        $io->text('Copying the .env.example file to .env...');
        copy('.env.example', '.env');

        // Replace the variables in the .env file
        $io->text('Replacing the variables in the .env file...');

        $env_file = file_get_contents('.env');
        $env_file = str_replace('jwt_secret', $jwt_secret, $env_file);
        file_put_contents('.env', $env_file);
        file_put_contents('app/api/.env', $env_file);
        file_put_contents('app/frontend/.env', $env_file);
        $io->success("The .env file has been created.");

        return true;
    }

    protected function gen_password()
    {
        $length = 128;
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789123456789?;:/=+!@#$%^&*()_{}[]<>~`';
        $count = mb_strlen($chars);
        for ($i = 0, $result = ''; $i < $length; $i++)
        {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }
}
