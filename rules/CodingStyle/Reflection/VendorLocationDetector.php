<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Reflection;

use PHPStan\Reflection\MethodReflection;
use Rector\FileSystem\FilePathHelper;

final readonly class VendorLocationDetector
{
    public function __construct(
        private FilePathHelper $filePathHelper
    ) {
    }

    public function detectMethodReflection(MethodReflection $methodReflection): bool
    {
        $declaringClassReflection = $methodReflection->getDeclaringClass();
        $fileName = $declaringClassReflection->getFileName();

        // probably internal
        if ($fileName === null) {
            return false;
        }

        $normalizedFileName = $this->filePathHelper->normalizePathAndSchema($fileName);
        return str_contains($normalizedFileName, '/vendor/');
    }
}
