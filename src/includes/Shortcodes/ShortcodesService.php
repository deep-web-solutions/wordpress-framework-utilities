<?php

namespace DeepWebSolutions\Framework\Utilities\Shortcodes;

use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\Actions\ResetHandlersTrait;
use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\Actions\RunHandlersTrait;
use DeepWebSolutions\Framework\Utilities\Shortcodes\Handlers\DefaultShortcodesHandler;
use DeepWebSolutions\Framework\Foundations\Actions\{ ResettableInterface, RunnableInterface };
use DeepWebSolutions\Framework\Foundations\Utilities\Services\AbstractHandlerService;

\defined( 'ABSPATH' ) || exit;

/**
 * Performs various shortcodes registration actions against a given handler.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Shortcodes
 */
class ShortcodesService extends AbstractHandlerService implements RunnableInterface, ResettableInterface {
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
	 * @return  ShortcodesHandlerInterface
	 */
	public function get_handler(): ShortcodesHandlerInterface { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler();
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new shortcode with the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $tag            The name of the WordPress shortcode that is being registered.
	 * @param   object|null     $component      A reference to the instance of the object on which the shortcode is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 */
	public function add_shortcode( string $tag, ?object $component, string $callback ): void {
		$this->get_handler()->add_shortcode( $tag, $component, $callback );
	}

	/**
	 * Removes a shortcode from the handler.
	 *
	 * @param   string          $tag            The name of the WordPress shortcode that is being deregistered.
	 * @param   object|null     $component      A reference to the instance of the object on which the shortcode is defined.
	 * @param   string          $callback       The name of the function definition on the $component.
	 */
	public function remove_shortcode( string $tag, ?object $component, string $callback ): void {
		$this->get_handler()->remove_shortcode( $tag, $component, $callback );
	}

	/**
	 * Removes all shortcodes from the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function remove_all_shortcodes(): void {
		$this->get_handler()->remove_all_shortcodes();
	}

	// endregion

	// region HELPERS

	/**
	 * Returns the class name of the default handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	protected function get_default_handler_class(): string {
		return DefaultShortcodesHandler::class;
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
		return ShortcodesHandlerInterface::class;
	}

	// endregion
}
