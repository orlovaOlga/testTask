<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:parse-users',
    description: 'Fetch user data from an external API and store it in the database',
)]
class ParseUsersCommand extends Command
{
    private const USERS_COUNT_FOR_PARSING = 10;
    private const API_URL = 'https://randomuser.me/api/';
    private const API_METHOD = 'GET';

    private HttpClientInterface $httpClient;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface        $logger,
        private ValidatorInterface     $validator,
        string                         $name = null
    )
    {
        $this->httpClient = HttpClient::create();
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 0; $i < self::USERS_COUNT_FOR_PARSING; $i++) {
            $response = $this->requestUserData();
            if (!$response) {
                continue;
            }

            $validResponse = $this->getValidDataFromResponse($response);
            if (!$validResponse) {
                continue;
            }

            $this->createUserFromResponse($validResponse);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    private function requestUserData(): ?array
    {
        try {
            return $this->httpClient->request(self::API_METHOD, self::API_URL)->toArray();
        } catch (TransportExceptionInterface $e) {
            $this->logger->error("Error: " . $e->getMessage());
        }
        return null;
    }

    private function getValidDataFromResponse($response): array
    {
        if (array_key_exists('results', $response) && !empty($response['results'])) {
            $userData = array_shift($response['results']);
        }

        if (empty($userData) || !array_key_exists('dob', $userData)
            || !array_key_exists('age', $userData['dob'])
            || !array_key_exists('email', $userData)
            || !array_key_exists('location', $userData) || !array_key_exists('country', $userData['location'])
            || !array_key_exists('picture', $userData) || !array_key_exists('medium', $userData['picture'])
            || !array_key_exists('name', $userData) || !array_key_exists('first', $userData['name'])
            || !array_key_exists('last', $userData['name'])
        ) {
            $this->logger->error('Format of response with user metadata was changed');
            throw new DomainException('Format of response from 3rd API with user metadata was changed');
        }

        return $userData;
    }

    private function getUserName($nameData): string
    {
        $firstName = $nameData['first'];
        $lastName = $nameData['last'];
        return "$firstName $lastName";
    }

    private function createUserFromResponse($userMetadata): void
    {
        $name = "{$userMetadata['name']['first']} {$userMetadata['name']['last']}";
        $age = $userMetadata['dob']['age'];
        $country = $userMetadata['location']['country'];
        $email = $userMetadata['email'];
        $profilePic = $userMetadata['picture']['medium'];

        $user = new User();
        $user->setName($name)
            ->setAge($age)
            ->setCountry($country)
            ->setEmail($email)
            ->setProfilePic($profilePic);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $this->logger->error("Error. Can't create user: " . $errors);
        } else {
            $this->entityManager->persist($user);
        }
    }
}
