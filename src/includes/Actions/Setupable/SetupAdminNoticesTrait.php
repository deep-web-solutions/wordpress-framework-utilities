<?php

namespace DeepWebSolutions\Framework\Utilities\Actions\Setupable;

use DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableExtensionTrait;
use DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DeepWebSolutions\Framework\Foundations\Utilities\DependencyInjection\ContainerAwareInterface;
use DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticesService;
use DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticesServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\AdminNotices\AdminNoticesServiceRegisterTrait;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * Trait for registering admin notices of using instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Actions\Setupable
 */
trait SetupAdminNoticesTrait {
	// region TRAITS

	use AdminNoticesServiceRegisterTrait;
	use SetupableExtensionTrait;

	// endregion

	// region METHODS

	/**
	 * Try to automagically call the admin notices registration method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters some other error.
	 *
	 * @return  SetupFailureException|null
	 */
	public function setup_admin_notices(): ?SetupFailureException {
		if ( $this instanceof AdminNoticesServiceAwareInterface ) {
			$service = $this->get_admin_notices_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( AdminNoticesService::class );
		} else {
			return new SetupFailureException( 'Admin notices registration setup scenario not supported' );
		}

		$this->register_admin_notices( $service );
		return null;
	}

	// endregion
}
