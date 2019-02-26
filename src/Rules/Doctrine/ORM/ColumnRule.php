<?php declare(strict_types = 1);

namespace PHPStan\Rules\Doctrine\ORM;

use Doctrine\ORM\Mapping\ClassMetadataInfo;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use PHPStan\Type\Doctrine\ObjectMetadataResolver;
use PHPStan\Type\Doctrine\PropertyLocator;
use PHPStan\Type\Doctrine\ValidatorRegistry;
use Throwable;
use function array_merge;
use function assert;

class ColumnRule implements Rule
{

	/** @var \PHPStan\Broker\Broker */
	private $broker;

	/** @var \PHPStan\Type\Doctrine\ObjectMetadataResolver */
	private $objectMetadataResolver;

	/** @var \PHPStan\Type\Doctrine\ValidatorRegistry */
	private $validatorRegistry;

	/** @var \PHPStan\Type\Doctrine\PropertyLocator */
	private $propertyLocator;

	public function __construct(
		Broker $broker,
		ObjectMetadataResolver $objectMetadataResolver,
		ValidatorRegistry $validatorRegistry,
		PropertyLocator $propertyLocator
	) {
		$this->objectMetadataResolver = $objectMetadataResolver;
		$this->broker = $broker;
		$this->validatorRegistry = $validatorRegistry;
		$this->propertyLocator = $propertyLocator;
	}

	public function getNodeType(): string
	{
		return Node\Stmt\Class_::class;
	}

	/**
	 * @return (string|\PHPStan\Rules\RuleError)[]
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		assert($node instanceof Node\Stmt\Class_);

		if (!isset($node->namespacedName)) {
			return [];
		}
		$classReflection = $this->broker->getClass((string) $node->namespacedName);

		$objectManager = $this->objectMetadataResolver->getObjectManager();
		if ($objectManager === null) {
			return [];
		}

		try {
			$metadata = $objectManager->getClassMetadata($classReflection->getName());
		} catch (Throwable $e) {
			return [];
		}

		if (!$metadata instanceof ClassMetadataInfo) {
			return [];
		}

		$errors = [[]];
		$validators = $this->validatorRegistry->getValidators();
		$located = [];

		foreach ($classReflection->getNativeReflection()->getProperties() as $property) {
			try {
				$name = $property->getName();
				$mapping = $metadata->getFieldMapping($name);

				$propertyReflection = $classReflection->getProperty($name, $scope);
				$type = $propertyReflection->getType();
				$declaringClass = $propertyReflection->getDeclaringClass();
				$class = $declaringClass->getName();
				$file = $declaringClass->getFileName();
				if (!isset($located[$class])) {
					$located[$class] = $this->propertyLocator->locate($declaringClass);
				}

				foreach ($validators as $validator) {
					$errors[] = $validator->validate($class, $name, $file, $located[$class][$name] ?? -1, $type, $mapping);
				}
			} catch (Throwable $e) {
				continue;
			}
		}

		return array_merge(...$errors);
	}

}
