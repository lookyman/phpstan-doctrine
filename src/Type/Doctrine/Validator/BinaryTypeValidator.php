<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Validator;

use PHPStan\Rules\RuleErrors\RuleErrorWithMessageAndLineAndFile;
use PHPStan\Type\ResourceType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class BinaryTypeValidator implements Validator
{

	/**
	 * @return (string|\PHPStan\Rules\RuleError)[]
	 */
	public function validate(string $class, string $property, string $file, int $line, Type $type, array $mapping): array
	{
		return isset($mapping['type'])
			&& $mapping['type'] === 'binary'
			&& !$type->accepts(new ResourceType(), true)->yes()
			? [new RuleErrorWithMessageAndLineAndFile(
				sprintf(
					'Property $%s is a binary field and should be resource, but is %s.',
					$property,
					$type->describe(VerbosityLevel::typeOnly())
				),
				$line,
				$file
			)]
			: [];
	}

}
