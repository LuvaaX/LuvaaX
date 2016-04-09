<?php

namespace Luvaax\CoreBundle\Model;

class ContentType
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Get the value of Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of Name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = ucfirst($name);

        return $this;
    }

}
