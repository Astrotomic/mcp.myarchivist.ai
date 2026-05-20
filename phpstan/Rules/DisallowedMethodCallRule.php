<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<MethodCall>
 */
class DisallowedMethodCallRule extends AbstractRule
{
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param  MethodCall  $node
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
