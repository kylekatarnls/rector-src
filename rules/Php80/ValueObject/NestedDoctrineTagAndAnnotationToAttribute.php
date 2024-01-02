<?php

declare(strict_types=1);

namespace Rector\Php80\ValueObject;

use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;

final readonly class NestedDoctrineTagAndAnnotationToAttribute
{
    public function __construct(
        private DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode,
        private NestedAnnotationToAttribute $nestedAnnotationToAttribute,
    ) {
    }

    public function getDoctrineAnnotationTagValueNode(): DoctrineAnnotationTagValueNode
    {
        return $this->doctrineAnnotationTagValueNode;
    }

    public function getNestedAnnotationToAttribute(): NestedAnnotationToAttribute
    {
        return $this->nestedAnnotationToAttribute;
    }
}
