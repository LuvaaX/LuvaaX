<?php

namespace Luvaax\CoreBundle\Model;

interface CustomContentTypeInterface
{
    /**
     * Can the content type be edit or not ?
     *
     * @return boolean
     */
    public static function isAvailableForEditing();
}
