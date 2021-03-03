<?php

namespace DeepWebSolutions\Framework\Utilities\Hooks\Handlers;

use DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableLocalTrait;
use DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableTrait;
use DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DeepWebSolutions\Framework\Foundations\Actions\InitializableInterface;
use DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetFailureException;
use DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;

defined( 'ABSPATH' ) || exit;

/**
 * Modified version of the Hooks handler that differs by keeping the hooks registered only within a certain scope
 * delimited by certain start and end hooks, respectively.
 *
 * @see     https://github.com/andykeith/barn2-lib/blob/master/lib/class-wp-scoped-hooks.php
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks\Handlers
 */
class ScopedHooksHandler extends HooksHandler implements InitializableInterface {
	// region TRAITS

	use InitializableTrait;
	use InitializableLocalTrait;

	// endregion

	// region FIELDS AND CONSTANTS

	/**
	 * The hook on which the actions and filters should be registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     array   $start
	 */
	protected array $start;

	/**
	 * The hook on which the actions and filters should be un-registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     array   $end
	 */
	protected array $end;

	// endregion

	// region MAGIC METHODS

	/**
	 * ScopedHooksHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $start  The hook on which the actions and filters should be registered.
	 * @param   array   $end    The hook on which the actions and filters should be un-registered.
	 */
	public function __construct( array $start = array(), array $end = array() ) {
		$this->parse_scope( $start, $end );
		$this->initialize();
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Initialize the filters and actions collections and maybe hooks the instances run and reset methods.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  InitializationFailureException|null
	 */
	protected function initialize_local(): ?InitializationFailureException {
		$this->remove_all_actions();
		$this->remove_all_filters();

		if ( is_string( $this->start['hook'] ) && ! empty( $this->start['hook'] ) ) {
			if ( 'action' === $this->start['type'] ) {
				$this->array_walk_add_action( $this->start );
			} else {
				$this->array_walk_add_filter( $this->start );
			}
		}
		if ( is_string( $this->end['hook'] ) && ! empty( $this->end['hook'] ) ) {
			if ( 'action' === $this->end['type'] ) {
				$this->array_walk_add_action( $this->end );
			} else {
				$this->array_walk_add_filter( $this->end );
			}
		}

		return null;
	}

	/**
	 * Perform the registered hooks manipulation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	public function run(): ?RunFailureException {
		if ( is_null( $this->is_run ) ) {
			array_walk( $this->filters['added'], array( $this, 'array_walk_add_filter' ) );
			$this->filters['removed'] = array_filter( $this->filters['removed'], array( $this, 'array_walk_remove_filter' ) );

			array_walk( $this->actions['added'], array( $this, 'array_walk_add_action' ) );
			$this->actions['removed'] = array_filter( $this->actions['removed'], array( $this, 'array_walk_remove_action' ) );

			$this->is_run     = true;
			$this->run_result = $this->reset_result = $this->is_reset = null; // phpcs:ignore
		}

		return $this->run_result;
	}

	/**
	 * Undo the registered hooks manipulation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	public function reset(): ?ResetFailureException {
		if ( is_null( $this->is_reset ) ) {
			array_walk( $this->filters['added'], array( $this, 'array_walk_remove_filter' ) );
			array_walk( $this->filters['removed'], array( $this, 'array_walk_add_filter' ) );

			array_walk( $this->actions['added'], array( $this, 'array_walk_remove_action' ) );
			array_walk( $this->actions['removed'], array( $this, 'array_walk_add_action' ) );

			$this->is_reset     = true;
			$this->reset_result = $this->is_run = $this->run_result = null; // phpcs:ignore
		}

		return $this->reset_result;
	}

	// endregion

	// region METHODS

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->actions['added'] = $this->add( $this->actions['added'], $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Remove an action from the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_added_action( string $hook, ?object $component, string $callback, int $priority = 10 ): void {
		$this->actions['added'] = $this->remove( $this->actions['added'], $hook, $component, $callback, $priority );
	}

	/**
	 * Add a new action to the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->actions['removed'] = $this->add( $this->actions['removed'], $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Remove an action from the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_removed_action( string $hook, ?object $component, string $callback, int $priority = 10 ): void {
		$this->actions['removed'] = $this->remove( $this->actions['removed'], $hook, $component, $callback, $priority );
	}

	/**
	 * Reinitialize the actions collection.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_actions(): void {
		$this->actions = array(
			'added'   => array(),
			'removed' => array(),
		);
	}

	/**
	 * Add a new filter to the collection to be unregistered with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $hook           The name of the WordPress filter that is being registered.
	 * @param   object|null     $component      A reference to the instance of the object on which the filter is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 * @param   int             $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param   int             $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->filters['added'] = $this->add( $this->filters['added'], $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Remove a filter from the collection to be registered with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $hook           The name of the WordPress filter that is being registered.
	 * @param   object|null     $component      A reference to the instance of the object on which the filter is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 * @param   int             $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_added_filter( string $hook, ?object $component, string $callback, int $priority = 10 ): void {
		$this->filters['added'] = $this->remove( $this->filters['added'], $hook, $component, $callback, $priority );
	}

	/**
	 * Add a new filter to the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress filter that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->filters['removed'] = $this->add( $this->filters['removed'], $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Remove a filter to the collection to be unregistered with WordPress.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress filter that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 */
	public function remove_removed_filter( string $hook, ?object $component, string $callback, int $priority = 10 ): void {
		$this->filters['removed'] = $this->remove( $this->filters['removed'], $hook, $component, $callback, $priority );
	}

	/**
	 * Reinitialize the filters collection.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_filters(): void {
		$this->filters = array(
			'added'   => array(),
			'removed' => array(),
		);
	}

	// endregion

	// region HELPERS

	/**
	 * Parses the start and end hooks parameters.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $start  The hook on which the actions and filters should be registered.
	 * @param   array   $end    The hook on which the actions and filters should be un-registered.
	 */
	protected function parse_scope( array $start, array $end ): void {
		$this->start = array_merge(
			wp_parse_args( $start, $this->get_scope_hook_defaults() ),
			array(
				'component'     => $this,
				'callback'      => 'run',
				'accepted_args' => 0,
			)
		);
		$this->end   = array_merge(
			wp_parse_args( $end, $this->get_scope_hook_defaults() ),
			array(
				'component'     => $this,
				'callback'      => 'reset',
				'accepted_args' => 0,
			)
		);
	}

	/**
	 * Gets a default scope hook configuration.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	protected function get_scope_hook_defaults(): array {
		return array(
			'hook'     => '',
			'type'     => 'action',
			'priority' => 10,
		);
	}

	// endregion
}
