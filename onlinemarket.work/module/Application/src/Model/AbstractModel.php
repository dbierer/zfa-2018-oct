<?php
namespace Application\Model;

abstract class AbstractModel implements PropertyInterface
{
    protected $mapping = [];
    protected $properties = [];
    public function __construct(array $properties = NULL)
    {
        if ($properties) $this->exchangeArray($properties);
    }
    public function __call($method, $value)
    {
        $prefix = substr($method, 0, 3);
        $key    = $this->normalizeKey(substr($method, 3));
        if ($prefix == 'get') {
            $result = $this->properties[$key] ?? NULL;
        } elseif ($prefix == 'set') {
            $this->properties[$key] = $value[0];
            $result = $this;
        } else {
            $result = NULL;
        }
        return $result;
    }
    public function getMapping()
    {
        return $this->mapping;
    }
    public function getProperty($key)
    {
		$key = $this->normalizeKey($key);
        return $this->properties[$key] ?? NULL;
    }
    public function setProperty($key, $value)
    {
		$key = $this->normalizeKey($key);
        $this->properties[$key] = $value;
        return $this;
    }
    public function unsetProperty($key)
    {
		$key = $this->normalizeKey($key);
        if (isset($this->properties[$key])) {
            unset($this->properties[$key]);
        }
        return $this;
    }
    protected function normalizeKey($key)
    {
		return strtolower(substr($key, 0, 1)) . substr($key, 1);
	}
}
