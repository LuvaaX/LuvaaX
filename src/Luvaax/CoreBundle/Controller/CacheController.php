<?php

namespace Luvaax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Valentin Merlet (merlet.valentin@gmail.com)
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

        // @todo Peekmo
        return new Response(200);
    }
}
