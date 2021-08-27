<?php

namespace DeepWebSolutions\Framework\Utilities\Hooks;

use DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\Actions\ResetHandlersTrait;
use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\Actions\RunHandlersTrait;
use DeepWebSolutions\Framework\Foundations\Utilities\Services\AbstractMultiHandlerService;
use DeepWebSolutions\Framework\Utilities\Hooks\Handlers\DefaultHooksHandler;

\defined( 'ABSPATH' ) || exit;

/**
 * A wrapper around a singleton hooks handler instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
class HooksService extends AbstractMultiHandlerService implements RunnableInterface, ResettableInterface {
	// region TRAITS

	use ResetHandlersTrait;
	use RunHandlersTrait;

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the instance of a given handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler_id     The ID of the handler to retrieve.
	 *
	 * @return  HooksHandlerInterface
	 */
	public function get_handler( string $handler_id ): ?HooksHandlerInterface { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new action with the handler.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being registered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int            $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 * @param    string         $handler_id     The ID of the handler to use.
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1, string $handler_id = 'default' ): void {
		$this->get_handler( $handler_id )->add_action( $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Removes an action from the handler.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress action that is being deregistered.
	 * @param    object|null    $component      A reference to the instance of the object on which the action is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    string         $handler_id     The ID of the handler to use.
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10, string $handler_id = 'default' ): void {
		$this->get_handler( $handler_id )->remove_action( $hook, $component, $callback, $priority );
	}

	/**
	 * Removes all actions from the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param    string     $handler_id     The ID of the handler to use.
	 */
	public function remove_all_actions( string $handler_id = 'default' ): void {
		$this->get_handler( $handler_id )->remove_all_actions();
	}

	/**
	 * Registers a new filter with the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $hook           The name of the WordPress filter that is being registered.
	 * @param   object|null     $component      A reference to the instance of the object on which the filter is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 * @param   int             $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param   int             $accepted_args  Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 * @param    string         $handler_id     The ID of the handler to use.
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1, string $handler_id = 'default' ): void {
		$this->get_handler( $handler_id )->add_filter( $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Removes a filter from the handler.
	 *
	 * @since    1.0.0
	 * @version  1.0.0
	 *
	 * @param    string         $hook           The name of the WordPress filter that is being deregistered.
	 * @param    object|null    $component      A reference to the instance of the object on which the filter is defined.
	 * @param    string         $callback       The name of the function definition on the $component.
	 * @param    int            $priority       Optional. he priority at which the function should be fired. Default is 10.
	 * @param    string         $handler_id     The ID of the handler to use.
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10, string $handler_id = 'default' ): void {
		$this->get_handler( $handler_id )->remove_filter( $hook, $component, $callback, $priority );
	}

	/**
	 * Removes all filters from the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param    string     $handler_id     The ID of the handler to use.
	 */
	public function remove_all_filters( string $handler_id = 'default' ): void {
		$this->get_handler( $handler_id )->remove_all_filters();
	}

	// endregion

	// region HELPERS

	/**
	 * Returns the class name of the default handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	protected function get_default_handlers_classes(): array {
		return array( DefaultHooksHandler::class );
	}

	/**
	 * Returns the class name of the used handler for better type-checking.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	protected function get_handler_class(): string {
		return HooksHandlerInterface::class;
	}

	// endregion
}
