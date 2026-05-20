<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Eval_;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\Eval_>
 */
class DisallowedEvalRule extends AbstractRule
{
    public function getNodeType(): string
    {
        return Eval_::class;
    }

    /**
     * @param \PhpParser\Node\Expr\Eval_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        return [
            $this->error(
                message: 'Should not use `eval`.',
                node: $node,
                scope: $scope
            ),
        ];
    }
}
