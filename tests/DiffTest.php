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
        $pathToResultJson = $this->getFixtureFullPath('ResultStylish');
        $this->assertEquals(file_get_contents($pathToResultJson), genDiff($pathToFile1, $pathToFile2));
    }

    public function testYml(): void
    {
        $pathToFile1 = $this->getFixtureFullPath('file1.yml');
        $pathToFile2 = $this->getFixtureFullPath('file2.yml');
        $pathToResultYml = $this->getFixtureFullPath('ResultStylish');
        $this->assertEquals(file_get_contents($pathToResultYml), genDiff($pathToFile1, $pathToFile2));
    }

    public function testDiff(): void
    {
        $pathToFile1 = $this->getFixtureFullPath('file1.yml');
        $pathToFile2 = $this->getFixtureFullPath('file2.yml');
        $this->assertEquals(['key' => 'test'], genDiff($pathToFile1, $pathToFile2));
    }

    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}