<?php
declare(strict_types=1);

namespace ReadModel;

class InvalidArgumentException extends \InvalidArgumentException
{
    public static function filterDoesNotExist(string $name): self
    {
        return new self("Filter '$name' doesn't exist");
    }

    public static function binaryUuidTransformerMustBeProvided(): self
    {
        return new self('You have to provide BinaryUuidTransformer to use BinaryUuidTransformerWalker');
    }

    public static function invalidType(string $type, string $function): self
    {
        return new self(sprintf(
            'Type "%s" is invalid. There is no "%s" function. Fix your type or add that function to global namespace',
            $type,
            $function
        ));
    }
}
