<?php

namespace Project\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testStylish($format): void
    {
        $pathToFile1 = $this->getFixtureFullPath("file1.{$format}");
        $pathToFile2 = $this->getFixtureFullPath("file2.{$format}");
        $pathToResult = $this->getFixtureFullPath('ResultStylish');

        $this->assertEquals(file_get_contents($pathToResult), genDiff($pathToFile1, $pathToFile2));
    }

    /**
     * @dataProvider additionProvider
     */
    public function testPlain($format): void
    {
        $pathToFile1 = $this->getFixtureFullPath("file1.{$format}");
        $pathToFile2 = $this->getFixtureFullPath("file2.{$format}");
        $pathToResult = $this->getFixtureFullPath('ResultPlain');

        $this->assertEquals(file_get_contents($pathToResult), genDiff($pathToFile1, $pathToFile2, 'plain'));
    }

    /**
     * @dataProvider additionProvider
     */
    public function testJson($format): void
    {
        $pathToFile1 = $this->getFixtureFullPath("file1.{$format}");
        $pathToFile2 = $this->getFixtureFullPath("file2.{$format}");
        $pathToResult = $this->getFixtureFullPath('ResultJson');

        $this->assertEquals(file_get_contents($pathToResult), genDiff($pathToFile1, $pathToFile2, 'json'));
    }

    public function additionProvider()
    {
        return [
            ['json'],
            ['yml']
        ];
    }
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
