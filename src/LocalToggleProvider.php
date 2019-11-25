<?php

declare(strict_types=1);

namespace FeatureToggle;

use FeatureToggle\Concerns\ToggleProvider;
use FeatureToggle\Contracts\Toggle as ToggleContract;
use FeatureToggle\Contracts\ToggleProvider as ToggleProviderContract;
use FeatureToggle\Toggle\Local as LocalToggle;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Collection;

class LocalToggleProvider implements ToggleProviderContract
{
    use ToggleProvider;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * @var string
     */
    protected $key;

    /**
     * LocalToggleProvider constructor.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  string  $key
     */
    public function __construct(ConfigContract $config, string $key = 'feature-toggle.toggles')
    {
        $this->config = $config;
        $this->key = $key;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'local';
    }

    /**
     * Get all toggles from config and normalize.
     *
     * @return ToggleContract[]|Collection
     */
    protected function calculateToggles(): Collection
    {
        $toggles = collect();

        foreach ($this->calculateLocalToggles() as $name => $isActive) {
            $toggles->put($name, new LocalToggle($name, $isActive));
        }

        return $toggles;
    }

    /**
     * Pull feature toggles from the application config file.
     *
     * @return array
     */
    protected function calculateLocalToggles(): array
    {
        $localFeatures = $this->config->get($this->key, []);

        if (! is_array($localFeatures)) {
            return [];
        }

        return $localFeatures;
    }
}
