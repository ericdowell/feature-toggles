<?php

declare(strict_types=1);

namespace FeatureToggle\Tests\Unit\Toggle;

use FeatureToggle\Tests\TestCase;
use FeatureToggle\Toggle\Conditional;
use FeatureToggle\Tests\Traits\TestToggle;
use FeatureToggle\Contracts\Toggle as ToggleContract;

class ConditionalTest extends TestCase
{
    use TestToggle;

    /**
     * @param  string  $name
     * @param  mixed  $is_active
     * @return ToggleContract|Conditional
     */
    protected function getInstance(string $name, $is_active): ToggleContract
    {
        return new Conditional($name, $is_active);
    }

    /**
     * @param  mixed  $value
     * @return mixed
     */
    public function getIsActiveAttribute($value): callable
    {
        return function () use ($value) {
            return $value;
        };
    }
}
