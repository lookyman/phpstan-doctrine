<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine;

use PHPStan\Type\Doctrine\Validator\Validator;

final class ValidatorRegistry
{

	/** @var \PHPStan\Type\Doctrine\Validator\Validator[] */
	private $validators = [];

	public function add(Validator $validator): void
	{
		$this->validators[] = $validator;
	}

	/**
	 * @return \PHPStan\Type\Doctrine\Validator\Validator[]
	 */
	public function getValidators(): array
	{
		return $this->validators;
	}

}
