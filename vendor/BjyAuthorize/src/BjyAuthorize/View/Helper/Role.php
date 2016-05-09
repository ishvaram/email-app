<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */

namespace BjyAuthorize\View\Helper;

use Zend\View\Helper\AbstractHelper;
use BjyAuthorize\Service\Authorize;

/**
 * Role View helper. Allows checking access to a resource/privilege in views.
 *
 * @author Ben Youngblood <bx.youngblood@gmail.com>
 */
class Role extends AbstractHelper
{
    /**
     * @var Authorize
     */
    protected $authorizeService;

    /**
     * @param Authorize $authorizeService
     */
    public function __construct(Authorize $authorizeService)
    {
        $this->authorizeService = $authorizeService;
    }

    /**
     * @param mixed      $resource
     * @param mixed|null $privilege
     *
     * @return bool
     */
    public function __invoke()
    {		
		$role = $this->authorizeService->getIdentityProvider()->getIdentityRoles();
		return $role[0];
    }
}
