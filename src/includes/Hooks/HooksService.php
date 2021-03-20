<?php

namespace DeepWebSolutions\Framework\Utilities\Hooks;

use DeepWebSolutions\Framework\Foundations\Actions\ResettableInterface;
use DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\DependencyInjection\ContainerAwareInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\HandlerInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\Services\AbstractHandlerService;
use DeepWebSolutions\Framework\Foundations\Utilities\Services\Actions\ResettableHandlerServiceTrait;
use DeepWebSolutions\Framework\Foundations\Utilities\Services\Actions\RunnableHandlerServiceTrait;
use DeepWebSolutions\Framework\Utilities\Hooks\Handlers\DefaultHooksHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * A wrapper around a singleton hooks handler instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Hooks
 */
class HooksService extends AbstractHandlerService implements RunnableInterface, ResettableInterface {
	// region TRAITS

	use RunnableHandlerServiceTrait;
	use ResettableHandlerServiceTrait;

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the instance of a given handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  HooksHandlerInterface
	 */
	public function get_handler(): HooksHandlerInterface { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler();
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
	 */
	public function add_action( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->get_handler()->add_action( $hook, $component, $callback, $priority, $accepted_args );
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
	 */
	public function remove_action( string $hook, ?object $component, string $callback, int $priority = 10 ): void {
		$this->get_handler()->remove_action( $hook, $component, $callback, $priority );
	}

	/**
	 * Removes all actions from the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_actions(): void {
		$this->get_handler()->remove_all_actions();
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
	 */
	public function add_filter( string $hook, ?object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->get_handler()->add_filter( $hook, $component, $callback, $priority, $accepted_args );
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
	 */
	public function remove_filter( string $hook, ?object $component, string $callback, int $priority = 10 ): void {
		$this->get_handler()->remove_filter( $hook, $component, $callback, $priority );
	}

	/**
	 * Removes all filters from the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_filters(): void {
		$this->get_handler()->remove_all_filters();
	}

	// endregion

	// region HELPERS

	/**
	 * Registers a default handler if one has not been set through the container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerInterface|null       $handler    Handler passed on in the constructor.
	 *
	 * @throws  NotFoundExceptionInterface      Thrown if the NullLogger is not found in the plugin DI-container.
	 * @throws  ContainerExceptionInterface     Thrown if some other error occurs while retrieving the NullLogger instance.
	 */
	protected function set_default_handler( ?HandlerInterface $handler ): void {
		if ( ! \is_a( $handler, $this->get_handler_class() ) ) {
			$plugin  = $this->get_plugin();
			$handler = ( $plugin instanceof ContainerAwareInterface )
				? $plugin->get_container()->get( DefaultHooksHandler::class )
				: new DefaultHooksHandler();
		}

		$this->set_handler( $handler );
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
