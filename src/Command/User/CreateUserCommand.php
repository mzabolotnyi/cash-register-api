<?php

namespace App\Command\User;

use App\Entity\User\User;
use App\Service\User\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    const NAME = 'user:create';

    /** @var UserManager */
    private $userManager;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(UserManager $userManager, ValidatorInterface $validator)
    {
        parent::__construct();
        $this->userManager = $userManager;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Command for creating user')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'Username'),
                new InputArgument('password', InputArgument::REQUIRED, 'User password'),
                new InputArgument('role', InputArgument::REQUIRED, 'User role'),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');
        $role = $input->getArgument('role');

        if (!in_array($role, User::ROLES)) {
            throw new \InvalidArgumentException('Unknown role');
        }

        $user = $this->userManager->createUser();
        $user->setUsername($username)
            ->setPlainPassword($password)
            ->addRole($role)
            ->setEnabled(true);

        $this->userManager->updateUser($user);

        $io = new SymfonyStyle($input, $output);
        $io->success(
            "\nCreated new user\nUsername: $username\nRole: $role"
        );
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Enter an username:');
            $questions['username'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Enter a password:');
            $question->setValidator(function ($password) {

                $violations = $this->validator->validate(
                    ['password' => $password],
                    new Collection(['password' => [new NotBlank()]])
                );

                if (count($violations) > 0) {
                    throw new \Exception('Invalid password value');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        if (!$input->getArgument('role')) {
            $question = new ChoiceQuestion('Select a role:', User::ROLES, User::ROLE_ADMIN);
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}