<?php

declare(strict_types=1);

namespace Rector\PhpParser\NodeFinder;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\TypeWithClassName;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\BetterNodeFinder;

final readonly class LocalMethodCallFinder
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private NodeTypeResolver $nodeTypeResolver,
        private NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * @return MethodCall[]
     */
    public function match(Class_ $class, ClassMethod $classMethod): array
    {
        $className = $this->nodeNameResolver->getName($class);
        if (! is_string($className)) {
            return [];
        }

        $classMethodName = $this->nodeNameResolver->getName($classMethod);

        /** @var MethodCall[] $matchingMethodCalls */
        $matchingMethodCalls = $this->betterNodeFinder->find(
            $class->getMethods(),
            function (Node $subNode) use ($className, $classMethodName): bool {
                if (! $subNode instanceof MethodCall) {
                    return false;
                }

                if (! $this->nodeNameResolver->isName($subNode->name, $classMethodName)) {
                    return false;
                }

                $callerType = $this->nodeTypeResolver->getType($subNode->var);
                if (! $callerType instanceof TypeWithClassName) {
                    return false;
                }

                return $callerType->getClassName() === $className;
            }
        );

        return $matchingMethodCalls;
    }
}
