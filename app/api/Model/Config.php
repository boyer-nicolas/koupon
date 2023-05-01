<?php

namespace Koupon\Model;

final class Config
{
    private $configFile;
    private $config;

    public function __construct()
    {
        $this->configFile = "../config/config.php";
        $this->config = require $this->configFile;
    }

    /**
     * @param mixed $key
     * 
     * @return [type]
     */
    public function get($key)
    {
        return $this->config[$key];
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * 
     * @return [type]
     */
    public function set($key, $value)
    {
        $this->config[$key] = $value;
        $this->save();
    }

    /**
     * @return [type]
     */
    private function save()
    {
        $config = "<?php\n\nreturn [\n";
        foreach ($this->config as $key => $value)
        {
            $config .= "\t'$key' => '$value',\n";
        }
        $config .= "];";
        file_put_contents($this->configFile, $config);
    }

    /**
     * @return [type]
     */
    public function getAll()
    {
        return $this->config;
    }

    /**
     * @param mixed $config
     * 
     * @return [type]
     */
    public function setAll($config)
    {
        $this->config = $config;
        $this->save();
    }
}
