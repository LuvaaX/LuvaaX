<?php

namespace Luvaax\CoreBundle\Security\Model;

/**
 * @author Valentin Merlet (merlet.valentin@gmail.com)
 */
interface CacheManagerInterface
{
    /**
     * Clear the cache
     */
    public function clearCache();
}
