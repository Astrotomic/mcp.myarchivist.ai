<?php

namespace Astrotomic\PHPStan\Rules;

use Astrotomic\PHPStan\Rules\Concerns\HasDocComments;
use Illuminate\Support\Str;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\UnionType;
use PHPStan\Type\ObjectType;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
abstract class AbstractClassRule extends AbstractRule
{
    use HasDocComments;

    public function getNodeType(): string
    {
        return Class_::class;
    }

    protected function shouldBeProcessed(Class_ $node): bool
    {
        if ($node->isAbstract()) {
            return false;
        }

        if ($node->namespacedName === null) {
            return false;
        }

        return true;
    }

    protected function isExtending(Class_ $node, string $extends): bool
    {
        return (new ObjectType($extends))
            ->isSuperTypeOf(new ObjectType((string) $node->namespacedName))
            ->yes();
    }

    protected function isInNamespace(Class_ $node, string $namespace): bool
    {
        return str_starts_with((string) $node->namespacedName, Str::finish($namespace, '\\'));
    }

    protected function hasClassnameSuffix(Class_ $node, string $suffix): bool
    {
        return str_ends_with(class_basename((string) $node->namespacedName), $suffix);
    }

    protected function hasMethod(Class_ $node, string $methodName, ?string $returnType = null): bool
    {
        $method = $node->getMethod($methodName);

        if ($method === null) {
            return false;
        }

        if ($returnType !== null) {
            $actualReturnType = $this->getReturnTypeName($method->getReturnType());

            if ($actualReturnType !== $returnType) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert a return type node to its string representation.
     *
     * Handles all possible return types from PHP-Parser:
     * - Identifier (e.g., 'string', 'int', 'void')
     * - Name (e.g., fully qualified class names)
     * - NullableType (e.g., '?string')
     * - UnionType (e.g., 'string|int')
     * - IntersectionType (e.g., 'Foo&Bar')
     */
    protected function getReturnTypeName(Identifier|Name|ComplexType|null $type): ?string
    {
        if ($type === null) {
            return null;
        }

        if ($type instanceof Identifier) {
            return $type->name;
        }

        if ($type instanceof Name) {
            return $type->toString();
        }

        if ($type instanceof NullableType) {
            return '?'.$this->getReturnTypeName($type->type);
        }

        if ($type instanceof UnionType) {
            return collect($type->types)
                ->map(fn (Identifier|Name|IntersectionType $t): ?string => $this->getReturnTypeName($t))
                ->implode('|');
        }

        if ($type instanceof IntersectionType) {
            return collect($type->types)
                ->map(fn (Identifier|Name $t): ?string => $this->getReturnTypeName($t))
                ->implode('&');
        }

        return null;
    }

    protected function usesTrait(Class_ $node, string $traitName): bool
    {
        return collect($node->getTraitUses())
            ->map(fn (TraitUse $use): array => $use->traits)
            ->collapse()
            ->contains(fn (FullyQualified $trait) => $trait->toString() === $traitName);
    }

    protected function implementsInterface(Class_ $node, string $interfaceName): bool
    {
        return collect($node->implements)
            ->contains(fn (FullyQualified $interface) => $interface->toString() === $interfaceName);
    }
}
