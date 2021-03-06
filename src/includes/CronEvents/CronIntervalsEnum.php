<?php

namespace DeepWebSolutions\Framework\Utilities\CronEvents;

\defined( 'ABSPATH' ) || exit;

/**
 * Valid values for cron intervals.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Utilities\CronEvents
 */
class CronIntervalsEnum {
	/**
	 * The slug of a 5 minute cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const MINUTES_5 = 'dws_minutes_5';

	/**
	 * The slug of a 10 minute cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const MINUTES_10 = 'dws_minutes_10';

	/**
	 * The slug of a 15 minute cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const MINUTES_15 = 'dws_minutes_15';

	/**
	 * The slug of a 30 minute cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const MINUTES_30 = 'dws_minutes_30';

	/**
	 * The slug of a 1 hour cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_1 = 'hourly';

	/**
	 * The slug of a 2 hours cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_2 = 'dws_hours_2';

	/**
	 * The slug of a 3 hours cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_3 = 'dws_hours_3';

	/**
	 * The slug of a 4 hours cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_4 = 'dws_hours_4';

	/**
	 * The slug of a 6 hours cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_6 = 'dws_hours_6';

	/**
	 * The slug of a 12 hours cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_12 = 'twicedaily';

	/**
	 * The slug of a 24 hours cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const HOURS_24 = 'daily';

	/**
	 * The slug of a 7 days cron interval.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     string
	 */
	public const WEEKS_1 = 'weekly';

	// region HELPERS

	/**
	 * Converts an interval's slug into a WP-compatible cron schedule definition.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $interval_slug  Slug of the interval to convert.
	 *
	 * @return  array|null
	 */
	public static function get_interval_description( string $interval_slug ): ?array {
		$minutes_display = /* translators: Number of minutes. */ __( 'Every %s minutes', 'dws-wp-framework-utilities' );
		$hours_display   = /* translators: number of hours. */ __( 'Every %s hours', 'dws-wp-framework-utilities' );
		$result          = null;

		switch ( $interval_slug ) {
			case self::MINUTES_5:
				$result = array(
					'interval' => 60 * 5,
					'display'  => \sprintf( $minutes_display, 5 ),
				);
				break;
			case self::MINUTES_10:
				$result = array(
					'interval' => 60 * 10,
					'display'  => \sprintf( $minutes_display, 10 ),
				);
				break;
			case self::MINUTES_15:
				$result = array(
					'interval' => 60 * 15,
					'display'  => \sprintf( $minutes_display, 15 ),
				);
				break;
			case self::MINUTES_30:
				$result = array(
					'interval' => 60 * 30,
					'display'  => \sprintf( $minutes_display, 30 ),
				);
				break;
			case self::HOURS_2:
				$result = array(
					'interval' => 60 * 60 * 2,
					'display'  => \sprintf( $hours_display, 2 ),
				);
				break;
			case self::HOURS_3:
				$result = array(
					'interval' => 60 * 60 * 3,
					'display'  => \sprintf( $hours_display, 3 ),
				);
				break;
			case self::HOURS_4:
				$result = array(
					'interval' => 60 * 60 * 4,
					'display'  => \sprintf( $hours_display, 4 ),
				);
				break;
			case self::HOURS_6:
				$result = array(
					'interval' => 60 * 60 * 6,
					'display'  => \sprintf( $hours_display, 6 ),
				);
				break;
		}

		return $result;
	}

	// endregion
}
