<?php

declare(strict_types=1);

namespace FeatureToggle\Tests\Traits;

use FeatureToggle\Contracts\ToggleProvider as ToggleProviderContract;

/**
 * @mixin \PHPUnit\Framework\Assert
 */
trait TestToggleProvider
{
    /**
     * @return ToggleProviderContract
     */
    abstract protected function getToggleProvider(): ToggleProviderContract;

    /**
     * @param  array|null  $toggles
     * @return ToggleProviderContract
     */
    abstract protected function setToggles(array $toggles = null): ToggleProviderContract;

    /**
     * @param mixed $value
     * @return mixed
     */
    public function getIsActiveAttribute($value)
    {
        return $value;
    }

    /**
     * @covers ::getToggles
     * @covers ::refreshToggles
     *
     * @return void
     */
    public function testNotArrayConfigToggles(): void
    {
        $this->assertCount(0, $this->setToggles()->getToggles());
    }

    /**
     * @covers ::getToggles
     * @covers ::getActiveToggles
     * @covers ::activeTogglesToJson
     * @covers ::refreshToggles
     * @covers ::calculateToggles
     *
     * @return void
     */
    public function testActiveTogglesToJsonNotEmpty(): void
    {
        $expected = json_encode([
            'foo' => [
                'name' => 'foo',
                'is_active' => true,
            ],
        ]);

        $this->assertSame($expected, $this->setToggles(['foo' => $this->getIsActiveAttribute(true)])->activeTogglesToJson());
    }

    /**
     * @covers ::getToggles
     * @covers ::getActiveToggles
     * @covers ::activeTogglesToJson
     *
     * @return void
     */
    public function testActiveTogglesToJsonEmpty(): void
    {
        $this->assertSame('{}', $this->getToggleProvider()->activeTogglesToJson());
    }

    /**
     * @covers ::isActive
     * @covers ::getToggles
     * @covers ::getActiveToggles
     * @covers ::refreshToggles
     * @covers ::calculateToggles
     *
     * @return void
     */
    public function testActiveToggle(): void
    {
        $toggleProvider = $this->setToggles([
            'foo' => $this->getIsActiveAttribute(true),
            'bar' => $this->getIsActiveAttribute('on'),
        ]);

        $this->assertTrue($toggleProvider->isActive('foo'));
        $this->assertTrue($toggleProvider->isActive('bar'));
        $this->assertCount(2, $toggleProvider->getToggles());
        $this->assertCount(2, $toggleProvider->getActiveToggles());
    }

    /**
     * @covers ::isActive
     * @covers ::getToggles
     * @covers ::getActiveToggles
     * @covers ::refreshToggles
     * @covers ::calculateToggles
     *
     * @return void
     */
    public function testInActiveToggle(): void
    {
        $toggleProvider = $this->setToggles([
            'foo' => $this->getIsActiveAttribute(false),
            'bar' => $this->getIsActiveAttribute('off'),
        ]);

        $this->assertFalse($toggleProvider->isActive('foo'));
        $this->assertFalse($toggleProvider->isActive('bar'));
        $this->assertCount(2, $toggleProvider->getToggles());
        $this->assertCount(0, $toggleProvider->getActiveToggles());
    }
}
