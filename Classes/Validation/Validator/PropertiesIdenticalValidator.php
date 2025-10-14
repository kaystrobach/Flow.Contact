<?php

namespace KayStrobach\Contact\Validation\Validator;

use Neos\Error\Messages\Error;
use Neos\Flow\Validation\Validator\AbstractValidator;
use Neos\Utility\ObjectAccess;

class PropertiesIdenticalValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    /**
     * @var array<string, array{0:mixed,1:string,2:string,3:bool}>
     */
    protected $supportedOptions = [
        'property1' => [null, 'First property path to compare', 'string', true],
        'property2' => [null, 'Second property path to compare', 'string', true]
    ];

    /**
     * @param object|array $value The object/array holding both properties
     */
    protected function isValid($value): void
    {
        if (!is_object($value) && !is_array($value)) {
            $this->addError('Validation expects an object or array as value.', 169900001);
            return;
        }

        $left  = ObjectAccess::getPropertyPath($value, $this->options['property1']);
        $right = ObjectAccess::getPropertyPath($value, $this->options['property2']);

        if ($left !== $right) {
            // Attach the error to the second property for nicer form feedback
            $this->getResult()
                ->forProperty($this->options['property2'])
                ->addError(
                    new Error(
                        'The two fields "%s" and "%s" need to be identical',
                        169900002,
                        [
                            $this->options['property1'],
                            $this->options['property2'],
                        ]
                    )
                );
        }
    }
}
