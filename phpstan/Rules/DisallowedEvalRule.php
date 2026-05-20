<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Eval_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<Eval_>
 */
class DisallowedEvalRule extends AbstractRule
{
    public function getNodeType(): string
    {
        return Eval_::class;
    }

    /**
     * @param  Eval_  $node
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
