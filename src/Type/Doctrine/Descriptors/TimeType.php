<?php declare(strict_types = 1);

namespace PHPStan\Type\Doctrine\Descriptors;

use DateTime;
use DateTimeInterface;
use PHPStan\Type\Type;

class TimeType implements DoctrineTypeDescriptor
{

	public function getType(): string
	{
		return 'time';
	}

	public function getWritableToPropertyType(): Type
	{
		return new \PHPStan\Type\ObjectType(DateTime::class);
	}

	public function getWritableToDatabaseType(): Type
	{
		return new \PHPStan\Type\ObjectType(DateTimeInterface::class);
	}

}
