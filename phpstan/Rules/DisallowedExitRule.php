<?php

namespace Astrotomic\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Exit_;
use PHPStan\Analyser\Scope;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Expr\Exit_>
 */
class DisallowedExitRule extends AbstractRule
{
    public function getNodeType(): string
    {
        return Exit_::class;
    }

    /**
     * @param \PhpParser\Node\Expr\Exit_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $kind = $node->getAttribute('kind', Exit_::KIND_DIE);
        $construct = $kind === Exit_::KIND_DIE ? 'die' : 'exit';

        return [
            $this->error(
                message: "Should not use `{$construct}`.",
                node: $node,
                scope: $scope
            ),
        ];
    }
}
