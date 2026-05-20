<?php

namespace Astrotomic\PHPStan\Rules;

use Astrotomic\PHPStan\Rules\Concerns\BuildsRuleErrors;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\VerbosityLevel;

abstract class AbstractRule implements Rule
{
    use BuildsRuleErrors;

    protected function resolveClassReference(Name|Expr $node, Scope $scope): string
    {
        if ($node instanceof Expr) {
            return $scope->getType($node)->describe(VerbosityLevel::typeOnly());
        }

        if ($node->isSpecialClassName()) {
            return $scope->resolveName($node);
        }

        return $node->toString();
    }
}
