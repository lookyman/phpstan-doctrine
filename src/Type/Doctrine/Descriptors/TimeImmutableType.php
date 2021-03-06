<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Descriptors;

use DateTimeImmutable;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class TimeImmutableType implements DoctrineTypeDescriptor
{

	public function getType(): string
	{
		return 'time_immutable';
	}

	public function getWritableToPropertyType(): Type
	{
		return new ObjectType(DateTimeImmutable::class);
	}

	public function getWritableToDatabaseType(): Type
	{
		return new ObjectType(DateTimeImmutable::class);
	}

}
