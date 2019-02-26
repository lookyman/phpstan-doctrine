<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Validator;

use PHPStan\Rules\RuleErrors\RuleErrorWithMessageAndLineAndFile;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\VerbosityLevel;

final class ArrayTypeValidator implements Validator
{

	/**
	 * @return (string|\PHPStan\Rules\RuleError)[]
	 */
	public function validate(string $class, string $property, string $file, int $line, Type $type, array $mapping): array
	{
		return isset($mapping['type'])
			&& $mapping['type'] === 'array'
			&& !$type->accepts(new ArrayType(new MixedType(), new MixedType()), true)->yes()
			? [new RuleErrorWithMessageAndLineAndFile(
				sprintf(
					'Property $%s is an array field and should be array, but is %s.',
					$property,
					$type->describe(VerbosityLevel::typeOnly())
				),
				$line,
				$file
			)]
			: [];
	}

}
