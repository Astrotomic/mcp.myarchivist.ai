<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
class ControllerRule extends AbstractClassRule
{
    /**
     * @param \PhpParser\Node\Stmt\Class_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $this->shouldBeProcessed($node)) {
            return [];
        }

        if (! $this->isInNamespace($node, 'App\\Http\\Controllers\\')) {
            return [];
        }

        if (! $this->hasClassnameSuffix($node, 'Controller')) {
            return [
                $this->error(
                    message: 'Controller classnames have to end with `Controller`.',
                    node: $node,
                    scope: $scope
                ),
            ];
        }

        if (! $this->hasMethod($node, '__invoke')) {
            return [
                $this->error(
                    message: 'Controllers have to define a `__invoke()` method.',
                    node: $node,
                    scope: $scope
                ),
            ];
        }

        $publicMethods = collect($node->getMethods())
            ->filter(fn (ClassMethod $method): bool => $method->isPublic())
            ->reject(fn (ClassMethod $method): bool => $method->name->name === '__construct')
            ->reject(fn (ClassMethod $method): bool => $method->name->name === '__invoke');

        if ($publicMethods->isNotEmpty()) {
            return $publicMethods
                ->map(fn (ClassMethod $method) => $this->error(
                    message: 'Controllers are not allowed to define other public methods than `__invoke()`.',
                    node: $method,
                    scope: $scope,
                ))
                ->all();
        }

        return [];
    }
}
