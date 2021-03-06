<?php

namespace DeepWebSolutions\Framework\Utilities\Dependencies\Handlers;

use DeepWebSolutions\Framework\Utilities\Dependencies\AbstractDependenciesHandler;
use DeepWebSolutions\Framework\Utilities\Dependencies\DependenciesCheckerInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * A basic handler implementation for supporting multiple checkers.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @package DeepWebSolutions\WP-Framework\Utilities\Dependencies\Handlers
 */
class MultiCheckerHandler extends AbstractDependenciesHandler {
	// region FIELDS AND CONSTANTS

	/**
	 * Collection of checkers to use.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     DependenciesCheckerInterface[]
	 */
	protected array $checkers;

	// endregion

	// region MAGIC METHODS

	/**
	 * MultiCheckerHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                          $handler_id             The ID of the handler.
	 * @param   DependenciesCheckerInterface[]  $dependencies_checker   Dependencies checkers to use.
	 */
	public function __construct( string $handler_id, array $dependencies_checker = array() ) {
		parent::__construct( $handler_id );
		$this->set_checkers( $dependencies_checker );
	}

	// endregion

	// region GETTERS

	/**
	 * Returns all the checker instances.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  DependenciesCheckerInterface[]
	 */
	public function get_checkers(): array {
		return $this->checkers;
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the checker instances.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   DependenciesCheckerInterface[]      $dependencies_checkers      Checkers to use from now on.
	 *
	 * @return  MultiCheckerHandler
	 */
	public function set_checkers( array $dependencies_checkers ): MultiCheckerHandler {
		$this->checkers = array();

		foreach ( $dependencies_checkers as $checker ) {
			if ( $checker instanceof DependenciesCheckerInterface ) {
				$this->register_checker( $checker );
			}
		}

		return $this;
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new checker with the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   DependenciesCheckerInterface    $checker    Checker to add.
	 *
	 * @return  MultiCheckerHandler
	 */
	public function register_checker( DependenciesCheckerInterface $checker ): MultiCheckerHandler {
		$this->checkers[] = $checker;
		return $this;
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the dependencies checked for.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_dependencies(): array {
		return $this->walk_checkers( 'get_dependencies' );
	}

	/**
	 * Returns the unfulfilled dependencies of the checker.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_missing_dependencies(): array {
		return $this->walk_checkers( 'get_missing_dependencies' );
	}

	/**
	 * Returns whether the dependencies are fulfilled or not according to the checker.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool[][]
	 */
	public function are_dependencies_fulfilled(): array {
		return $this->walk_checkers( 'are_dependencies_fulfilled' );
	}

	// endregion

	// region HELPERS

	/**
	 * Walks over all registered checkers and compiles an array of results generated by calling a method on each of them.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $method     Method to call on each handler to get the result.
	 *
	 * @return  array
	 */
	protected function walk_checkers( string $method ): array {
		$result = array();

		foreach ( $this->checkers as $checker ) {
			$checker_type = $checker->get_type();
			$checker_id   = $checker->get_id();

			$result[ $checker_type ]                = $result[ $checker_type ] ?? array();
			$result[ $checker_type ][ $checker_id ] = \method_exists( $checker, $method ) ? \call_user_func( array( $checker, $method ) ) : null;
		}

		return $result;
	}

	// endregion
}
