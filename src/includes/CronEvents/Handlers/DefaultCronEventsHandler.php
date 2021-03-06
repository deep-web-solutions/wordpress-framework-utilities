<?php

namespace DeepWebSolutions\Framework\Utilities\CronEvents\Handlers;

use DeepWebSolutions\Framework\Foundations\Actions\Resettable\ResetFailureException;
use DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DeepWebSolutions\Framework\Helpers\WordPress\Hooks\HooksHelpersAwareInterface;
use DeepWebSolutions\Framework\Utilities\CronEvents\AbstractCronEventsHandler;
use DeepWebSolutions\Framework\Utilities\CronEvents\CronIntervalsEnum;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterTrait;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles the registration of cron events with WordPress' API.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @package DeepWebSolutions\WP-Framework\Utilities\CronEvents\Handlers
 */
class DefaultCronEventsHandler extends AbstractCronEventsHandler implements HooksHelpersAwareInterface, HooksServiceRegisterInterface {
	// region TRAITS

	use HooksServiceRegisterTrait;

	// endregion

	// region MAGIC METHODS

	/**
	 * DefaultCronEventsHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $handler_id     The ID of the handler instance.
	 */
	public function __construct( string $handler_id = 'default' ) { // phpcs:ignore
		parent::__construct( $handler_id );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Registers hook with the hooks service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HooksService    $hooks_service      Instance of the hooks service.
	 */
	public function register_hooks( HooksService $hooks_service ): void {
		$hooks_service->add_filter( 'cron_schedules', $this, 'register_cron_schedules' );
	}

	/**
	 * Registers cron events with WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	protected function run_local(): ?RunFailureException {
		$run_result = null;

		$events = \array_merge( $this->single_events, $this->recurring_events );
		foreach ( $events as $event ) {
			if ( \wp_next_scheduled( $event['hook'], $event['args'] ) ) {
				continue;
			}

			if ( isset( $event['recurrence'] ) ) {
				$result = \wp_schedule_event( $event['timestamp'], $event['recurrence'], $event['hook'], $event['args'] );
			} else {
				$result = \wp_schedule_single_event( $event['timestamp'], $event['hook'], $event['args'] );
			}

			if ( false === $result ) {
				$run_result = new RunFailureException( \sprintf( 'Failed to schedule event %s', \wp_json_encode( $event ) ) );
				break;
			}
		}

		return $run_result;
	}

	/**
	 * Clears the registered cron events from WordPress.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ResetFailureException|null
	 */
	protected function reset_local(): ?ResetFailureException {
		$reset_result = null;

		$events = \array_merge( $this->single_events, $this->recurring_events );
		foreach ( $events as $event ) {
			$timestamp = \wp_next_scheduled( $event['hook'], $event['args'] );
			$result    = \wp_unschedule_event( $timestamp, $event['hook'], $event['args'] );

			if ( false === $result ) {
				$reset_result = new ResetFailureException( \sprintf( 'Failed to unschedule event %s', \wp_json_encode( $event ) ) );
				break;
			}
		}

		return $reset_result;
	}

	// endregion

	// region HOOKS

	/**
	 * Registers required handlers with the WP cron API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $schedules      Currently registered WP cron schedules.
	 *
	 * @return  array
	 */
	public function register_cron_schedules( array $schedules ): array {
		foreach ( $this->recurring_events as $events ) {
			foreach ( $events as $event ) {
				$schedule = CronIntervalsEnum::get_interval_description( $event['recurrence'] );
				if ( \is_null( $schedule ) ) {
					$schedule = \apply_filters( $this->get_hook_tag( 'schedule' ), $schedule ); // phpcs:ignore
				}

				if ( ! empty( $schedule ) && \is_array( $schedule ) ) {
					$schedules[ $event['recurrence'] ] = $schedule;
				}
			}
		}

		return $schedules;
	}

	// endregion
}
