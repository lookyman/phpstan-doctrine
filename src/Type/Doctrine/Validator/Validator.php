<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Validator;

use PHPStan\Type\Type;

interface Validator
{

	/**
	 * @return (string|\PHPStan\Rules\RuleError)[]
	 */
	public function validate(string $class, string $property, string $file, int $line, Type $type, array $mapping): array;

}
