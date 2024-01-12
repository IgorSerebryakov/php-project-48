<?php

namespace Project\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    public function testJson(): void
    {
        $pathToFile1 = $this->getFixtureFullPath('file1.json');
        $pathToFile2 = $this->getFixtureFullPath('file2.json');
        $pathToResultJson = $this->getFixtureFullPath('ResultJson');
        $this->assertEquals(file_get_contents($pathToResultJson), genDiff($pathToFile1, $pathToFile2));
    }

    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}