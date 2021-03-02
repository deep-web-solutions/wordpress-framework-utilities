<?php

namespace DeepWebSolutions\Framework\Utilities\AdminNotices;

use DeepWebSolutions\Framework\Foundations\Actions\Outputtable\OutputFailureException;
use DeepWebSolutions\Framework\Foundations\Actions\OutputtableInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Describes an admin notice.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\AdminNotices
 */
interface AdminNoticeInterface extends OutputtableInterface {
	// region GETTERS

	/**
	 * Returns the notice's unique ID.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_handle(): string;

	// endregion

	// region METHODS

	/**
	 * Whether the notice is persistent or not. Non-persistent notices should be deleted from the store
	 * after their first output.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_persistent(): bool;

	/**
	 * Checks whether the notice should be outputted or not. Your implementation is free
	 * to ignore this result.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool    Whether the notice should be outputted or not.
	 */
	public function should_output(): bool;

	/**
	 * Outputs the notice inline as HTML.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  OutputFailureException|null
	 */
	public function output(): ?OutputFailureException;

	// endregion
}
