<?php

namespace Astrotomic\PHPStan\Rules\Concerns;

use Illuminate\Support\Str;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;

trait BuildsRuleErrors
{
    protected function error(string $message, Node $node, Scope $scope, ?string $reason = null, ?string $link = null): RuleError
    {
        $builder = RuleErrorBuilder::message($message)
            ->identifier(
                Str::of(static::class)
                    ->classBasename()
                    ->replaceLast('Rule', '')
                    ->camel()
                    ->prepend('rules.hospitable.')
            )
            ->line($node->getLine())
            ->file($scope->getFile());

        if ($reason !== null) {
            $builder = $builder->addTip($reason);
        }

        if ($link !== null) {
            $builder = $builder->addTip($link);
        }

        return $builder->build();
    }
}
