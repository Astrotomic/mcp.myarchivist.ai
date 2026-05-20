<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\FuncCall>
 */
class DisallowedFunctionCallRule extends AbstractRule
{
    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    /**
     * @param \PhpParser\Node\Expr\FuncCall $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! $node->name instanceof Name) {
            return [];
        }

        $function = $node->name->toString();

        // Note: die, exit, and eval are language constructs (not functions) and are
        // handled by DisallowedExitRule and DisallowedEvalRule respectively.
        if (in_array($function, ['dd', 'dump', 'ray', 'rd'], true)) {
            return [
                $this->error(
                    message: "Should not use function `{$function}`.",
                    node: $node,
                    scope: $scope
                ),
            ];
        }

        return [];
    }
}
