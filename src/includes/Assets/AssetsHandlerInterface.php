<?php

namespace DeepWebSolutions\Framework\Utilities\Assets;

use DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Describes an object that registers and/or enqueues assets.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\Assets
 */
interface AssetsHandlerInterface extends RunnableInterface {
	/* empty on purpose */
}