<?php

namespace Luvaax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 */
class CacheController extends Controller
{
    /**
     * Clear cache action
     *
     * @return Response
     */
    public function clearAction()
    {
        $this->get('luvaax.core.cache_manager')->clearCache();

        // @todo
        return new Response(200);
    }
}
