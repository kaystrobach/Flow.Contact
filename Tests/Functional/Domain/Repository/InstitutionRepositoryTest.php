<?php

namespace KayStrobach\Contact\Tests\Functional\Domain\Repository;

use KayStrobach\Contact\Domain\Model\Institution;
use KayStrobach\Contact\Domain\Model\User;
use KayStrobach\Contact\Domain\Repository\InstitutionRepository;
use KayStrobach\Contact\Domain\Repository\UserRepository;
use KayStrobach\VisualSearch\Domain\Session\QueryDto;
use Neos\Flow\Tests\FunctionalTestCase;
use Neos\Party\Domain\Model\PersonName;

/**
 * soft delete of institutions
 */
class InstitutionRepositoryTest extends FunctionalTestCase
{
    /**
     * @var bool
     */
    protected static $testablePersistenceEnabled = true;

    protected InstitutionRepository $institutionRepository;

    protected UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->institutionRepository = $this->objectManager->get(InstitutionRepository::class);
        $this->userRepository = $this->objectManager->get(UserRepository::class);
    }

    /**
     * @test
     */
    public function findByQueryHidesDeletedInstitutions(): void
    {
        $this->createInstitution('Aktiv', false);
        $this->createInstitution('Gelöscht', true);
        $this->persistenceManager->persistAll();

        $query = new QueryDto();
        $query->setIdentifier('KayStrobach_Contact_Institution');

        self::assertSame(['Aktiv'], $this->names($this->institutionRepository->findByQuery($query)->toArray()));
    }

    /**
     * @test
     */
    public function findBySearchTermDoesNotSuggestDeletedInstitutions(): void
    {
        $this->createInstitution('Schule A', false);
        $this->createInstitution('Schule B', true);
        $this->persistenceManager->persistAll();

        $result = $this->institutionRepository->findBySearchTerm([], 'Schule', ['labelProperty' => 'name']);

        self::assertSame(['Schule A'], $this->names($result->toArray()));
    }

    /**
     * @test
     */
    public function findSelectableHidesDeletedInstitutionsExceptTheCurrentOne(): void
    {
        $this->createInstitution('Aktiv', false);
        $deleted = $this->createInstitution('Gelöscht', true);
        $this->createInstitution('Auch gelöscht', true);
        $this->persistenceManager->persistAll();

        self::assertSame(['Aktiv'], $this->names($this->institutionRepository->findSelectable()));
        self::assertSame(['Aktiv', 'Gelöscht'], $this->names($this->institutionRepository->findSelectable($deleted)));
    }

    /**
     * @test
     */
    public function findDeletedReturnsOnlyDeletedInstitutions(): void
    {
        $this->createInstitution('Aktiv', false);
        $this->createInstitution('Gelöscht', true);
        $this->persistenceManager->persistAll();

        self::assertSame(['Gelöscht'], $this->names($this->institutionRepository->findDeleted()->toArray()));
    }

    /**
     * @test
     */
    public function deletingAndRestoringKeepsTheRelationshipsToUsers(): void
    {
        $institution = $this->createInstitution('Schule', false);
        $user = new User();
        $user->setName(new PersonName('', 'Test', '', 'Lehrkraft'));
        $user->setInstitution($institution);
        $this->userRepository->add($user);
        $this->persistenceManager->persistAll();

        $institution->delete();
        $this->institutionRepository->update($institution);
        $this->persistenceManager->persistAll();
        $this->persistenceManager->clearState();

        $institution = $this->institutionRepository->findByIdentifier($this->persistenceManager->getIdentifierByObject($institution));
        self::assertTrue($institution->isDeleted());
        self::assertSame('Schule (gelöscht)', $institution->getLabel());
        self::assertSame(1, $this->userRepository->findByInstitution($institution)->count());

        $institution->restore();
        self::assertFalse($institution->isDeleted());
        self::assertSame('Schule', $institution->getLabel());
    }

    protected function createInstitution(string $name, bool $deleted): Institution
    {
        $institution = new Institution();
        $institution->setName($name);
        if ($deleted) {
            $institution->delete();
        }
        $this->institutionRepository->add($institution);
        return $institution;
    }

    /**
     * @param Institution[] $institutions
     * @return string[]
     */
    protected function names(array $institutions): array
    {
        $names = array_map(static fn (Institution $institution) => $institution->getName(), $institutions);
        sort($names);
        return $names;
    }
}
