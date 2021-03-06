<?php declare (strict_types = 1);


namespace Shopware\Psh\Config;

/**
 * Builder pattern
 *
 * Create a config from a more complicated proto format by representing a statefull representation of config read so far.
 */
class ConfigBuilder
{
    const DEFAULT_ENV = '##default##';

    private $header = '';

    private $environments = [];

    private $currentEnvironment;

    private $currentCommandPaths;

    private $currentDynamicVariables;

    private $templates;

    private $currentConstants;

    public function setHeader(string $header = null): ConfigBuilder
    {
        $this->header = $header;
        return $this;
    }

    public function start(string $environment = null): ConfigBuilder
    {
        $this->reset();
        if (!$environment) {
            $environment = self::DEFAULT_ENV;
        }

        $this->currentEnvironment = $environment;
        return $this;
    }

    public function setCommandPaths(array $commandPaths): ConfigBuilder
    {
        $this->currentCommandPaths = $commandPaths;
        return $this;
    }

    public function setDynamicVariables(array $dynamicVariables): ConfigBuilder
    {
        $this->currentDynamicVariables = $dynamicVariables;
        return $this;
    }

    public function setConstants(array $constants): ConfigBuilder
    {
        $this->currentConstants = $constants;
        return $this;
    }

    public function setTemplates(array $templates): ConfigBuilder
    {
        $this->templates = $templates;
        return $this;
    }

    public function create(): Config
    {
        $this->reset();
        return new Config($this->header, self::DEFAULT_ENV, $this->environments);
    }

    private function reset()
    {
        if ($this->currentEnvironment) {
            $this->environments[$this->currentEnvironment] = new ConfigEnvironment(
                $this->currentCommandPaths,
                $this->currentDynamicVariables,
                $this->currentConstants,
                $this->templates
            );
        }

        $this->currentCommandPaths = null;
        $this->currentDynamicVariables = null;
        $this->currentConstants = null;
        $this->templates = null;
    }
}
