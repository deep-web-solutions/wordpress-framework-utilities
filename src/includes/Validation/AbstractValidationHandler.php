<?php

namespace DeepWebSolutions\Framework\Utilities\Validation;

use DeepWebSolutions\Framework\Foundations\Services\AbstractHandler;

\defined( 'ABSPATH' ) || exit;

/**
 * Template for encapsulating some of the most often needed functionality of a validation handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Validation
 */
abstract class AbstractValidationHandler extends AbstractHandler implements ValidationHandlerInterface {
	// region MAGIC METHODS

	/**
	 * AbstractValidationHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id     The ID of the handler instance.
	 */
	public function __construct( string $handler_id = 'settings' ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct( $handler_id );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function get_type(): string {
		return 'validation';
	}

	// endregion
}
