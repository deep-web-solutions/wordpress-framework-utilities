<?php

namespace DeepWebSolutions\Framework\Utilities\Dependencies\States;

use DeepWebSolutions\Framework\Foundations\Exceptions\NotImplementedException;
use DeepWebSolutions\Framework\Foundations\States\Disableable\DisableableExtensionTrait;
use DeepWebSolutions\Framework\Foundations\States\DisableableInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\DependencyInjection\ContainerAwareInterface;
use DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesService;
use DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\Dependencies\Helpers\DependenciesContextsEnum;
use DeepWebSolutions\Framework\Utilities\Dependencies\Helpers\DependenciesHelpersTrait;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * Abstract trait for dependent disablement of instances with dependencies.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\States
 */
trait DisabledDependenciesTrait {
	// region TRAITS

	use DependenciesHelpersTrait;
	use DisableableExtensionTrait;

	// endregion

	// region METHODS

	/**
	 * If the using class is disableable, disable it if required dependencies are not fulfilled.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the container can't find an entry.
	 * @throws  ContainerExceptionInterface     Thrown if the container encounters any other error.
	 * @throws  NotImplementedException         Thrown when using this function in an unsupported context.
	 *
	 * @return  bool
	 */
	public function is_disabled_dependencies(): bool {
		$is_disabled = false;

		if ( $this instanceof DisableableInterface ) {
			$handler = $this->get_dependencies_handler( DependenciesContextsEnum::DISABLED_STATE );
			if ( \is_null( $handler ) ) {
				throw new NotImplementedException( 'Dependency checking scenario not supported' );
			}

			$are_deps_fulfilled = $handler->are_dependencies_fulfilled();
			$is_disabled        = ! $this->check_fulfillment_status( $are_deps_fulfilled );
		}

		return $is_disabled;
	}

	// endregion
}
