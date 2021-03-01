<?php

namespace DeepWebSolutions\Framework\Utilities\Templating;

use DeepWebSolutions\Framework\Foundations\Plugin\PluginAwareInterface;
use DeepWebSolutions\Framework\Foundations\Plugin\PluginAwareTrait;
use DeepWebSolutions\Framework\Foundations\Plugin\PluginInterface;
use DeepWebSolutions\Framework\Foundations\WordPress\Helpers\HooksHelpersTrait;
use DeepWebSolutions\Framework\Helpers\FileSystem\FilesystemAwareTrait;
use DeepWebSolutions\Framework\Helpers\WordPress\Request;
use DeepWebSolutions\Framework\Utilities\Logging\LoggingService;
use DeepWebSolutions\Framework\Utilities\Logging\LoggingServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\Logging\LoggingServiceAwareTrait;
use Psr\Log\LogLevel;

defined( 'ABSPATH' ) || exit;

/**
 * Retrieves PHP template files either as HTML string or by loading them into the execution flow.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Templating
 */
class TemplatingService implements LoggingServiceAwareInterface, PluginAwareInterface {
	// region TRAITS

	use FilesystemAwareTrait;
	use HooksHelpersTrait;
	use LoggingServiceAwareTrait;
	use PluginAwareTrait;

	// endregion

	// region MAGIC METHODS

	/**
	 * TemplatingService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface     $plugin             Instance of the plugin.
	 * @param   LoggingService      $logging_service    Instance of the logging service.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service ) {
		$this->set_plugin( $plugin );
		$this->set_logging_service( $logging_service );
	}

	// endregion

	// region METHODS

	/**
	 * Requires a template file part.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $slug           Template slug.
	 * @param   string  $name           Template name. Pass an empty string to ignore.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  void
	 */
	public function load_template_part( string $slug, string $name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ): void {
		$template = ( ! empty( $name ) )
			? $this->locate_template( "{$slug}-{$name}.php", $template_path, $default_path, $constant_name )
			: $this->locate_template( "{$slug}.php", $template_path, $default_path, $constant_name );

		// Allow 3rd-party plugins to filter the template file from their plugin.
		$filtered_template = apply_filters( $this->get_hook_tag( 'get_template_part' ), $template, $slug, $name, $template_path, $default_path, $args, $constant_name ); // phpcs:ignore

		if ( $filtered_template !== $template ) {
			if ( ! $this->get_wp_filesystem()->exists( $filtered_template ) ) {
				$this->log_event_and_doing_it_wrong(
					__FUNCTION__,
					/* translators: %s: Path to template file */
					sprintf( __( '%s does not exist.', 'dws-wp-framework-utilities' ), '<code>' . $filtered_template . '</code>' ),
					'1.0.0',
					LogLevel::ERROR,
					'framework'
				);
				return;
			}
			$template = $filtered_template;
		}

		// Load the found template part.
		do_action( $this->get_hook_tag( 'before_template_part' ), $slug, $name, $template_path, $template, $args, $constant_name ); // phpcs:ignore

		load_template( $template, false, $args );

		do_action( $this->get_hook_tag( 'after_template_part' ), $slug, $name, $template_path, $template, $args, $constant_name ); // phpcs:ignore
	}

	/**
	 * Returns the content of a template part as an HTML string.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $slug           Template slug.
	 * @param   string  $name           Template name. Pass an empty string to ignore.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  string
	 */
	public function get_template_part_html( string $slug, string $name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ): string {
		ob_start();
		$this->load_template_part( $slug, $name, $template_path, $default_path, $args, $constant_name );
		return ob_get_clean();
	}

	/**
	 * Requires a template file.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $template_name  The name of the template file searched for.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  void
	 */
	public function load_template( string $template_name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ): void {
		$template = self::locate_template( $template_name, $template_path, $default_path, $constant_name );

		// Allow 3rd-party plugins to filter the template file from their plugin.
		$filtered_template = apply_filters( $this->get_hook_tag( 'get_template' ), $template, $template_name, $template_path, $default_path, $args, $constant_name ); // phpcs:ignore

		if ( $filtered_template !== $template ) {
			if ( ! $this->get_wp_filesystem()->exists( $filtered_template ) ) {
				$this->log_event_and_doing_it_wrong(
					__FUNCTION__,
					/* translators: %s: Path to template file */
					sprintf( __( '%s does not exist.', 'dws-wp-framework-utilities' ), '<code>' . $filtered_template . '</code>' ),
					'1.0.0',
					LogLevel::ERROR,
					'framework'
				);
				return;
			}
			$template = $filtered_template;
		}

		// Load the found template.
		do_action( $this->get_hook_tag( 'before_template' ), $template_name, $template_path, $template, $args, $constant_name ); // phpcs:ignore

		load_template( $template, false, $args );

		do_action( $this->get_hook_tag( 'after_template' ), $template_name, $template_path, $template, $args, $constant_name ); // phpcs:ignore
	}

	/**
	 * Returns the content of a template as an HTML string.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $template_name  The name of the template file searched for.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   array   $args           Arguments to pass on to the template.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  string
	 */
	public function get_template_html( string $template_name, string $template_path, string $default_path, array $args = array(), string $constant_name = 'TEMPLATE_DEBUG' ): string {
		ob_start();
		$this->load_template( $template_name, $template_path, $default_path, $args, $constant_name );
		return ob_get_clean();
	}

	/**
	 * Returns the path to a template file. If the theme overwrites the file and debugging is disabled, returns the path
	 * to the theme's file, otherwise the path to the default file packaged with the plugin.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $template_name  The name of the template file searched for.
	 * @param   string  $template_path  The relative path of the template from the root of the active theme.
	 * @param   string  $default_path   The absolute path to the template's folder within the plugin.
	 * @param   string  $constant_name  The name of the constant that should evaluate to true for debugging to be considered active.
	 *
	 * @return  string
	 */
	public function locate_template( string $template_name, string $template_path, string $default_path, string $constant_name = 'TEMPLATE_DEBUG' ): string {
		$template = Request::has_debug( $constant_name ) ? '' : locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		$template = empty( $template )
			? trailingslashit( $default_path ) . $template_name
			: $template;

		return apply_filters( $this->get_hook_tag( 'locate_template' ), $template, $template_name, $template_path, $default_path, $constant_name ); // phpcs:ignore
	}

	// endregion
}
