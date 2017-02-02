<?php

namespace CEHD\App\Dict;

class Dict
{
    function __construct(array $data=[])
    {
        foreach($data as $key => $value)
        {
            $this->__create_property($key, $value);
        }
    }

    function __toString()
    {
        return json_encode($this);
    }

    private function __create_property($name, $value)
    {
        $this->{$name} = $value;
    }

    public function toArray()
    {
        return json_decode($this->toJSON(), true);
    }

    public function toJSON()
    {
        return json_encode($this);
    }

    // Alias for toJSON
    public function toString()
    {
        return $this->toJSON();
    }

    public function get($property_name)
    {
        if (property_exists($this, $property_name))
        {
          return $this->$property_name;
        }
        return null;
    }
}
