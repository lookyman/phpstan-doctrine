<?php declare(strict_types = 1);

namespace PHPStan\Rules\Doctrine\ORM;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\Doctrine\ObjectMetadataResolver;
use PHPStan\Type\Doctrine\PropertyLocator;
use PHPStan\Type\Doctrine\ValidatorRegistry;

class ColumnRuleTest extends RuleTestCase
{

	protected function getRule(): Rule
	{
		$validatorRegistry = new ValidatorRegistry();
		$broker = $this->createBroker();

		return new ColumnRule(
			$broker,
			new ObjectMetadataResolver(__DIR__ . '/entity-manager.php', null),
			$validatorRegistry,
			new PropertyLocator(
				$broker,
				$this->getParser(),
				'', // todo
				$this->getFileHelper(),
				$this->getTypeSpecifier(),
				$this->createScopeFactory($broker, $this->getTypeSpecifier())
			)
		);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/MyEntity.php'], [
			[
				'', // todo
				36,
			],
		]);
	}

}
