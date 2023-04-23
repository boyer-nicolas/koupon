<?php

namespace Koupon\Model;


final class Filters
{
    private $data;

    public function __construct()
    {
        $this->data = $_POST;
    }

    /**
     * @param string $key
     * 
     * @return string
     */
    public function sanitize(string $key): string
    {
        return htmlspecialchars($this->data[$key]);
    }
}
