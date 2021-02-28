<?php

namespace DeepWebSolutions\Framework\Utilities\Logging\Factory;

use DeepWebSolutions\Framework\Utilities\Logging\LoggerFactory;

defined( 'ABSPATH' ) || exit;

/**
 * Basic implementation of the logger-factory-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Logging\Factory
 */
trait LoggerFactoryAwareTrait {
	// region FIELDS AND CONSTANTS

	/**
	 * Logger factory for retrieving loggers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     LoggerFactory|null
	 */
	protected ?LoggerFactory $logger_factory = null;

	// endregion

	// region GETTERS

	/**
	 * Gets the logger factory instance. If not set, returns a factory that always returns a noop logger.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  LoggerFactory
	 */
	public function get_logger_factory(): LoggerFactory {
		return $this->logger_factory ?? new LoggerFactory();
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the logger factory instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   LoggerFactory   $logger_factory     The logger factory instance to use from now on.
	 */
	public function set_logger_factory( LoggerFactory $logger_factory ): void {
		$this->logger_factory = $logger_factory;
	}

	// endregion
}