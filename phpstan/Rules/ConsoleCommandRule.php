<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
class ConsoleCommandRule extends AbstractClassRule
{
    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->shouldBeProcessed($node)) {
            return [];
        }

        if (! $this->isInNamespace($node, 'App\\Console\\Commands\\')) {
            return [];
        }

        if (! $this->hasClassnameSuffix($node, 'Command')) {
            return [
                $this->error(
                    message: 'Console Commands must have a `Command` classname suffix.',
                    node: $node,
                    scope: $scope
                ),
            ];
        }

        if (! $this->hasMethod($node, 'handle', 'int')) {
            return [
                $this->error(
                    message: 'Console Commands have to define a `handle(): int` method.',
                    node: $node,
                    scope: $scope
                ),
            ];
        }

        $publicMethods = collect($node->getMethods())
            ->filter(fn (ClassMethod $method): bool => $method->isPublic())
            ->reject(fn (ClassMethod $method): bool => $method->name->name === '__construct')
            ->reject(fn (ClassMethod $method): bool => $method->name->name === 'handle');

        if ($publicMethods->isNotEmpty()) {
            return $publicMethods
                ->map(fn (ClassMethod $method) => $this->error(
                    message: 'Console Commands are not allowed to define other public methods than `handle()`.',
                    node: $method,
                    scope: $scope,
                ))
                ->all();
        }

        return [];
    }
}
