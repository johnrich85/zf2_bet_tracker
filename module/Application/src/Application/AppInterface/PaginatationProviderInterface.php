<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 08/07/14
 * Time: 19:52
 */

namespace Application\AppInterface;


interface PaginatationProviderInterface {
    public function getPaginatedList($page, $params);
} 