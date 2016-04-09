<?php

namespace Luvaax\CoreBundle\Module;
use Doctrine\Common\Collections\ArrayCollection;
use Luvaax\CoreBundle\Module\Model\ModuleInterface;

class ModuleRegistry
{
    /**
     * @var ArrayCollection
     */
    private $modules;

    /**
     * Initialize module list
     */
    public function __construct()
    {
        $this->modules = new ArrayCollection();
    }

    /**
     * Add a module to the list
     *
     * @param ModuleInterface $module
     */
    public function addModule(ModuleInterface $module)
    {
        $this->modules->add($module);
    }
}
