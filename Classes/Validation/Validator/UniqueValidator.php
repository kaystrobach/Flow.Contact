<?php

namespace KayStrobach\Contact\Validation\Validator;

use Doctrine\ORM\EntityManager;
use Neos\Flow\Persistence\Doctrine\Query;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Flow\Reflection\ClassSchema;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Validation\Exception\InvalidValidationOptionsException;
use Neos\Flow\Validation\Validator\AbstractValidator;
use Neos\Utility\TypeHandling;

class UniqueValidator extends AbstractValidator
{
    /**
     * @Flow\Inject
     * @var ReflectionService
     */
    protected $reflectionService;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @var array
     */
    protected $supportedOptions = [
        'valueProperty' => [null, 'property to be unique', 'string'],
        'staticProperties' => [null, 'additional static property values', 'array'],
        'entityClass' => [null, 'entity class name', 'string']
    ];

    protected function isValid($value)
    {
        $classSchema = $this->reflectionService->getClassSchema($this->options['entityClass']);
        if ($classSchema === null || $classSchema->getModelType() !== ClassSchema::MODELTYPE_ENTITY) {
            throw new InvalidValidationOptionsException('The object supplied for the UniqueEntityValidator must be an entity.', 1358454284);
        }

        // query builder is not needed here
        $query = new Query($this->options['entityClass']);
        $demands = [
            $query->equals($this->options['valueProperty'], $value)
        ];

        $query->matching(
            $query->logicalAnd(
                $demands
            )
        );

        $result = $query->execute();
        if ($result->count() > 0) {
            $this->addError('Another entity with the same unique identifiers already exists', 13557857674);
        }

    }
}
