<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Validator;

use PHPStan\Rules\RuleErrors\RuleErrorWithMessageAndLineAndFile;
use PHPStan\Type\BooleanType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class BooleanTypeValidator implements Validator
{

	/**
	 * @return (string|\PHPStan\Rules\RuleError)[]
	 */
	public function validate(string $class, string $property, string $file, int $line, Type $type, array $mapping): array
	{
		return isset($mapping['type'])
			&& $mapping['type'] === 'boolean'
			&& !$type->accepts(new BooleanType(), true)->yes()
			? [new RuleErrorWithMessageAndLineAndFile(
				sprintf(
					'Property $%s is a boolean field and should be boolean, but is %s.',
					$property,
					$type->describe(VerbosityLevel::typeOnly())
				),
				$line,
				$file
			)]
			: [];
	}

}
