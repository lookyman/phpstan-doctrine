<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Descriptors;

use PHPStan\Type\Type;

class IntegerType implements DoctrineTypeDescriptor
{

	public function getType(): string
	{
		return 'integer';
	}

	public function getWritableToPropertyType(): Type
	{
		return new \PHPStan\Type\IntegerType();
	}

	public function getWritableToDatabaseType(): Type
	{
		return new \PHPStan\Type\IntegerType();
	}

}
