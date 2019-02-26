<?php

declare(strict_types = 1);

namespace PHPStan\DependencyInjection;

use Nette\DI\CompilerExtension;
use PHPStan\Type\Doctrine\ValidatorRegistry;
use function array_keys;
use function sprintf;

final class ValidatorExtension extends CompilerExtension
{

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$registry = $builder->getDefinitionByType(ValidatorRegistry::class);
		foreach (array_keys($builder->findByTag('phpstan.doctrine.validator')) as $name) {
			$registry->addSetup('add', [sprintf('@%s', $name)]);
		}
	}

}
