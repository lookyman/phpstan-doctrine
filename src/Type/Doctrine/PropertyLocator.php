<?php

declare(strict_types = 1);

namespace PHPStan\Type\Doctrine;

use PhpParser\Node;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Analyser\ScopeFactory;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Broker\Broker;
use PHPStan\File\FileHelper;
use PHPStan\Parser\Parser;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\FileTypeMapper;

final class PropertyLocator
{

	/** @var \PHPStan\Broker\Broker */
	private $broker;

	/** @var \PHPStan\Parser\Parser */
	private $parser;

	/** @var \PHPStan\Type\FileTypeMapper */
	private $fileTypeMapper;

	/** @var \PHPStan\File\FileHelper */
	private $fileHelper;

	/** @var \PHPStan\Analyser\TypeSpecifier */
	private $typeSpecifier;

	/** @var \PHPStan\Analyser\ScopeFactory */
	private $scopeFactory;

	public function __construct(
		Broker $broker,
		Parser $parser,
		FileTypeMapper $fileTypeMapper,
		FileHelper $fileHelper,
		TypeSpecifier $typeSpecifier,
		ScopeFactory $scopeFactory
	) {
		$this->broker = $broker;
		$this->parser = $parser;
		$this->fileTypeMapper = $fileTypeMapper;
		$this->fileHelper = $fileHelper;
		$this->typeSpecifier = $typeSpecifier;
		$this->scopeFactory = $scopeFactory;
	}

	public function locate(ClassReflection $reflection): array
	{
		$located = [];
		$name = $reflection->getName();
		$file = $reflection->getFileName();

		(new NodeScopeResolver($this->broker, $this->parser, $this->fileTypeMapper, $this->fileHelper, $this->typeSpecifier, false, false, false, []))->processNodes(
			$this->parser->parseFile($file),
			$this->scopeFactory->create(ScopeContext::create($file)),
			function (Node $node, Scope $scope) use (&$located, $name): void {
				if (!$node instanceof Node\Stmt\PropertyProperty) {
					return;
				}
				$class = $scope->getClassReflection();
				if ($class === null || $class->getName() !== $name) {
					return;
				}
				$located[$node->name->name] = $node->getLine();
			}
		);

		return $located;
	}

}
