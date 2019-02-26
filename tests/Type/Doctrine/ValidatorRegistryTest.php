<?php

declare(strict_types = 1);

namespace PHPStan\Type\Doctrine;

use PHPStan\Type\Doctrine\Validator\Validator;
use PHPStan\Type\Type;
use PHPUnit\Framework\TestCase;

final class ValidatorRegistryTest extends TestCase
{

	public function testGetValidators(): void
	{
		$validator = new class () implements Validator {

			/**
			 * @return (string|\PHPStan\Rules\RuleError)[]
			 */
			public function validate(string $class, string $property, string $file, int $line, Type $type, array $mapping): array
			{
				return [];
			}

		};

		$registry = new ValidatorRegistry();
		self::assertCount(0, $registry->getValidators());
		$registry->add($validator);
		$validators = $registry->getValidators();
		self::assertCount(1, $validators);
		self::assertSame($validator, $validators[0]);
	}

}
