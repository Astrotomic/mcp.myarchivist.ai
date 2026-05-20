<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\MethodCall>
 */
class DisallowedMethodCallRule extends AbstractRule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Identifier) {
            return [];
        }

        $method = $node->name->toString();

        if (in_array($method, ['dd', 'dump', 'ray', 'rd'], true)) {
            return [
                $this->error(
                    message: "Should not use method `{$method}`.",
                    node: $node,
                    scope: $scope
                ),
            ];
        }

        return [];
    }
}
