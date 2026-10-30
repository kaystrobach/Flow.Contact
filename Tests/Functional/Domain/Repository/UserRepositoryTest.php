<?php

namespace KayStrobach\Contact\Tests\Functional\Domain\Repository;

use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Model\UserInstitutionRelationship;
use KayStrobach\Contact\Domain\Repository\InstitutionRepository;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use Neos\Flow\Tests\FunctionalTestCase;
use Neos\Party\Domain\Model\PersonName;

class UserRepositoryTest extends FunctionalTestCase
{
    /**
     * @var bool
     */
    protected static $testablePersistenceEnabled = true;

    protected UserRepository $userRepository;

    protected InstitutionRepository $institutionRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->objectManager->get(UserRepository::class);
        $this->institutionRepository = $this->objectManager->get(InstitutionRepository::class);
    }

    /**
     * @test
     */
    public function findByInstitutionReturnsUsersRelatedToTheInstitution(): void
    {
        $school = $this->createInstitution('Schule');
        $otherSchool = $this->createInstitution('Andere Schule');

        $primary = $this->createUser('Primär', $school, true);
        $secondary = $this->createUser('Zusätzlich', $school, false);
        $this->createUser('Fremd', $otherSchool, true);

        $this->persistenceManager->persistAll();
        $this->persistenceManager->clearState();

        $school = $this->institutionRepository->findByIdentifier($this->persistenceManager->getIdentifierByObject($school));
        $result = $this->userRepository->findByInstitution($school);

        self::assertSame(2, $result->count());
        $lastNames = array_map(
            static fn (User $user) => $user->getName()->getLastName(),
            $result->toArray()
        );
        sort($lastNames);
        self::assertSame(
            [$primary->getName()->getLastName(), $secondary->getName()->getLastName()],
            $lastNames
        );
    }

    /**
     * @test
     */
    public function findByInstitutionReturnsEmptyResultForInstitutionWithoutUsers(): void
    {
        $institution = $this->createInstitution('Leer');
        $this->persistenceManager->persistAll();

        self::assertSame(0, $this->userRepository->findByInstitution($institution)->count());
    }

    protected function createInstitution(string $name): Institution
    {
        $institution = new Institution();
        $institution->setName($name);
        $this->institutionRepository->add($institution);
        return $institution;
    }

    protected function createUser(string $lastName, Institution $institution, bool $primary): User
    {
        $user = new User();
        $user->setName(new PersonName('', 'Test', '', $lastName));

        $relationship = new UserInstitutionRelationship();
        $relationship->setUser($user);
        $relationship->setInstitution($institution);
        $user->getInstutionRelationships()->add($relationship);
        if ($primary) {
            $user->setPrimaryInstitutionRelationship($relationship);
        }

        $this->userRepository->add($user);
        return $user;
    }
}
