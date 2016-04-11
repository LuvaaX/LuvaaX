<?php

namespace Luvaax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

        $this->addFlash(
            'success',
            $this->get('translator')
                 ->trans(
                    'luvaax.flash_messages.success_clear_cache',
                    array('%env%' => $this->get('kernel')->getEnvironment())
                 )
            );

        return new RedirectResponse($this->generateUrl('easyadmin'));
    }
}
