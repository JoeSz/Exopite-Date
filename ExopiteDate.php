<?php
/**
 * Exopite Date
 *
 * A fluent extension to PHPs DateTime class.
 *
 * Based on:
 * @link https://github.com/jasonlewis/expressive-date
 *
 * Timezones:
 * @link https://en.wikipedia.org/wiki/List_of_tz_database_time_zones
 *
 * Date formats:
 * @link http://php.net/manual/en/function.date.php
 *
 * strtotime date formats:
 * @link https://framework.zend.com/manual/1.12/en/zend.date.constants.html#zend.date.constants.selfdefinedformats
 */
/**
 * // Use the static make method to get an instance of Exopite Date.
 * // Y-m-d, Y-m-d H:i:s, Y.m.d, Y.m.d H:i:s, Y/m/d, Y/m/d H:i:s, d.m.Y, d.m.Y H:i:s, d-m-Y, d-m-Y H:i:s, d/m/Y, d/m/Y H:i:s, m.d.Y, m.d.Y H:i:s, m-d-Y, m-d-Y H:i:s, m/d/Y, m/d/Y H:i:s,
 * $date = ExopiteDate::make();
 * $date = ExopiteDate::makeFromString( '31.12.2001 23:59:59', 'Europe/Berlin', 'd.m.Y H:i:s' );
 * $date = ExopiteDate::makeFromString( '31.12.2001 23:59:59', 'Europe/Berlin' );
 *
 *
 * makeFromTime($hour = null, $minute = null, $second = null, $timezone = null)
 * makeFromDate($year = null, $month = null, $day = null, $timezone = null)
 * makeFromDateTime($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $timezone = null)
 */
/**
 *  @method int getDay() Get day of month.
 *  @method int getMonth() Get the month.
 *  @method int getYear() Get the year.
 *  @method int getHour() Get the hour.
 *  @method int getMinute() Get the minutes.
 *  @method int getSecond() Get the seconds.
 *  @method string getDayOfWeek() Get the day of the week, e.g., Monday.
 *  @method int getDayOfWeekAsNumeric() Get the numeric day of week.
 *  @method int getDaysInMonth() Get the number of days in the month.
 *  @method int getDayOfYear() Get the day of the year.
 *  @method string getDaySuffix() Get the suffix of the day, e.g., st.
 *  @method bool isLeapYear() Determines if is leap year.
 *  @method string isAmOrPm() Determines if time is AM or PM.
 *  @method bool isDaylightSavings() Determines if observing daylight savings.
 *  @method int getGmtDifference() Get difference in GMT.
 *  @method int getSecondsSinceEpoch() Get the number of seconds since epoch.
 *  @method string getTimezoneName() Get the timezone name.
 *  @method setDay(int $day) Set the day of month.
 *  @method setMonth(int $month) Set the month.
 *  @method setYear(int $year) Set the year.
 *  @method setHour(int $hour) Set the hour.
 *  @method setMinute(int $minute) Set the minutes.
 *  @method setSecond(int $second) Set the seconds.
 */

class ExopiteDate extends DateTime {

	/**
	 * Default date format used when casting object to string.
	 *
	 * @var string
	 */
	protected $defaultDateFormat = 'jS F, Y \a\\t g:ia';

	/**
	 * Starting day of the week, where 0 is Sunday and 1 is Monday.
	 *
	 * @var int
	 */
	protected $weekStartDay = 0;

	/**
	 * Create a new ExopiteDate instance.
	 *
	 * @param  string  $time
	 * @param  string|DateTimeZone  $timezone
	 * @return void
	 */
	public function __construct($time = null, $timezone = null)
	{
		$timezone = $this->parseSuppliedTimezone($timezone);

		parent::__construct($time, $timezone);
	}

	/**
	 * Make and return new ExopiteDate instance.
	 *
	 * @param  string  $time
	 * @param  string|DateTimeZone  $timezone
	 * @return ExopiteDate
	 */
	public static function make($time = null, $timezone = null)
	{
		return new static($time, $timezone);
	}

	/**
	 * Make and return a new ExopiteDate instance with defined year, month, and day.
	 *
	 * @param  int  $year
	 * @param  int  $month
	 * @param  int  $day
	 * @param  string|DateTimeZone  $timezone
	 * @return ExopiteDate
	 */
	public static function makeFromDate($year = null, $month = null, $day = null, $timezone = null)
	{
		return static::makeFromDateTime($year, $month, $day, null, null, null, $timezone);
	}

	/**
	 * Make and return a new ExopiteDate instance with defined hour, minute, and second.
	 *
	 * @param  int  $hour
	 * @param  int  $minute
	 * @param  int  $second
	 * @param  string|DateTimeZone  $timezone
	 * @return ExopiteDate
	 */
	public static function makeFromTime($hour = null, $minute = null, $second = null, $timezone = null)
	{
		return static::makeFromDateTime(null, null, null, $hour, $minute, $second, $timezone);
	}

	/**
	 * Make and return a new ExopiteDate instance with defined year, month, day, hour, minute, and second.
	 *
	 * @param  int  $year
	 * @param  int  $month
	 * @param  int  $day
	 * @param  int  $hour
	 * @param  int  $minute
	 * @param  int  $second
	 * @param  string|DateTimeZone  $timezone
	 * @return ExopiteDate
	 */
	public static function makeFromDateTime($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $timezone = null)
	{
		$date = new static(null, $timezone);

		$date->setDate($year ?: $date->getYear(), $month ?: $date->getMonth(), $day ?: $date->getDay());

		// If no hour was given then we'll default the minute and second to the current
		// minute and second. If a date was given and minute or second are null then
		// we'll set them to 0, mimicking PHPs behaviour.
		if (is_null($hour))
		{
			$minute = $minute ?: $date->getMinute();
			$second = $second ?: $date->getSecond();
		}
		else
		{
			$minute = $minute ?: 0;
			$second = $second ?: 0;
		}

		$date->setTime($hour ?: $date->getHour(), $minute, $second);

		return $date;

	}

    /**
     * Make and return a new ExopiteDate instance with defined DateTime format.
     * @link http://php.net/manual/en/function.date.php
     *
     * @param  string $time
     * @param  string $format
     * @param  string/TimeZone $timezone
     * @return ExopiteDate
     *
     * Accept: Y[-./]m[-./]d [H:i:s], m[-./]d[-./]Y [H:i:s], d[-./]m[-./]Y [H:i:s]
     * regex:
     * @link https://stackoverflow.com/questions/13194322/php-regex-to-check-date-is-in-yyyy-mm-dd-format/29417597#29417597
     */
    public static function makeFromString($time = null, $timezone = null, $format = null )
    {
        $formats = array(

            'Y-m-d' => '/^(((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48]))-((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|[12]\d))))|((([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))-((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|1\d|2[0-8])))))$/',

            'Y-m-d H:i:s' => '/^(((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48]))-((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|[12]\d))))|((([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))-((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|1\d|2[0-8]))))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'Y.m.d' => '/^((((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|1\d|2[0-8]))))\.(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))|(((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48]))\.((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|[12]\d)))))$/',

            'Y.m.d H:i:s' => '/^((((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|1\d|2[0-8]))))\.(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))|(((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48]))\.((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|[12]\d))))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'Y/m/d' => '/^(((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48]))\/((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|[12]\d))))|((([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))\/((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|1\d|2[0-8])))))$/',

            'Y/m/d H:i:s' => '/^(((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48]))\/((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|[12]\d))))|((([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))\/((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|1\d|2[0-8]))))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'd.m.Y' => '/^(((((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\.(0[469]|11)))|((0[1-9]|[12]\d)\.02))\.((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\.(0[469]|11)))|((0[1-9]|1\d|2[0-8])\.02)))\.(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))$/',

            'd.m.Y H:i:s' => '/^(((((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\.(0[469]|11)))|((0[1-9]|[12]\d)\.02))\.((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[1-9]|[12]\d|3[01])\.(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\.(0[469]|11)))|((0[1-9]|1\d|2[0-8])\.02)))\.(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'd-m-Y' => '/^(((((0[1-9]|[12]\d|3[01])-(0[13578]|1[02]))|((0[1-9]|[12]\d|30)-(0[469]|11)))|((0[1-9]|[12]\d)-02))-((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[1-9]|[12]\d|3[01])-(0[13578]|1[02]))|((0[1-9]|[12]\d|30)-(0[469]|11)))|((0[1-9]|1\d|2[0-8])-02)))-(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))$/',

            'd-m-Y H:i:s' => '/^(((((0[1-9]|[12]\d|3[01])-(0[13578]|1[02]))|((0[1-9]|[12]\d|30)-(0[469]|11)))|((0[1-9]|[12]\d)-02))-((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[1-9]|[12]\d|3[01])-(0[13578]|1[02]))|((0[1-9]|[12]\d|30)-(0[469]|11)))|((0[1-9]|1\d|2[0-8])-02)))-(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'd/m/Y' => '/^(((((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\/(0[469]|11)))|((0[1-9]|[12]\d)\/02))\/((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\/(0[469]|11)))|((0[1-9]|1\d|2[0-8])\/02)))\/(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))$/',

            'd/m/Y H:i:s' => '/^(((((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\/(0[469]|11)))|((0[1-9]|[12]\d)\/02))\/((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02]))|((0[1-9]|[12]\d|30)\/(0[469]|11)))|((0[1-9]|1\d|2[0-8])\/02)))\/(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'm.d.Y' => '/^(((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|[12]\d)))\.((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|1\d|2[0-8]))))\.(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))$/',

            'm.d.Y H:i:s' => '/^(((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|[12]\d)))\.((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[13578]|1[02])\.(0[1-9]|[12]\d|3[01]))|((0[469]|11)\.(0[1-9]|[12]\d|30)))|(02\.(0[1-9]|1\d|2[0-8]))))\.(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'm-d-Y' => '/^(((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|[12]\d)))-((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|1\d|2[0-8]))))-(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))$/',

            'm-d-Y H:i:s' => '/^(((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|[12]\d)))-((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[13578]|1[02])-(0[1-9]|[12]\d|3[01]))|((0[469]|11)-(0[1-9]|[12]\d|30)))|(02-(0[1-9]|1\d|2[0-8]))))-(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',

            'm/d/Y' => '/^(((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|[12]\d)))\/((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|1\d|2[0-8]))))\/(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00)))$/',

            'm/d/Y H:i:s' => '/^(((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|[12]\d)))\/((((1[26]|2[048])00)|[12]\d([2468][048]|[13579][26]|0[48])))|(((((0[13578]|1[02])\/(0[1-9]|[12]\d|3[01]))|((0[469]|11)\/(0[1-9]|[12]\d|30)))|(02\/(0[1-9]|1\d|2[0-8]))))\/(([12]\d([02468][1235679]|[13579][01345789]))|((1[1345789]|2[1235679])00))) ([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/',


        );

        if ( ! isset( $format ) ) {

            foreach ( $formats as $format_string => $pattern ) {
                if ( preg_match( $pattern, $time ) ) {
                    $format = $format_string;
                    break;
                }
            }

        }

        $timezone = self::parseSuppliedTimezone($timezone);
        $time = DateTime::createFromFormat($format, $time, $timezone);
        return static::makeFromDateTime($time->format('Y'), $time->format('m'), $time->format('d'), $time->format('H'), $time->format('i'), $time->format('s'), $timezone);
    }

	/**
	 * Parse a supplied timezone.
	 *
	 * @param  string|DateTimeZone  $timezone
	 * @return DateTimeZone
	 */
	protected function parseSuppliedTimezone($timezone)
	{
		if ($timezone instanceof DateTimeZone or is_null($timezone))
		{
			return $timezone;
		}

		try
		{
			$timezone = new DateTimeZone($timezone);
		}
		catch (Exception $error)
		{
			throw new InvalidArgumentException('The supplied timezone ['.$timezone.'] is not supported.');
		}

		return $timezone;
	}

	/**
	 * Use the current date and time.
	 *
	 * @return ExopiteDate
	 */
	public function now()
	{
		$this->setTimestamp(time());

		return $this;
	}

	/**
	 * Use today's date and time at midnight.
	 *
	 * @return ExopiteDate
	 */
	public function today()
	{
		$this->now()->setHour(0)->setMinute(0)->setSecond(0);

		return $this;
	}

	/**
	 * Use tomorrow's date and time at midnight.
	 *
	 * @return ExopiteDate
	 */
	public function tomorrow()
	{
		$this->now()->addOneDay()->startOfDay();

		return $this;
	}

	/**
	 * Use yesterday's date and time at midnight.
	 *
	 * @return ExopiteDate
	 */
	public function yesterday()
	{
		$this->now()->minusOneDay()->startOfDay();

		return $this;
	}

	/**
	 * Use the start of the day.
	 *
	 * @return ExopiteDate
	 */
	public function startOfDay()
	{
		$this->setHour(0)->setMinute(0)->setSecond(0);

		return $this;
	}

	/**
	 * Use the end of the day.
	 *
	 * @return ExopiteDate
	 */
	public function endOfDay()
	{
		$this->setHour(23)->setMinute(59)->setSecond(59);

		return $this;
	}

	/**
	 * Use the start of the week.
	 *
	 * @return ExopiteDate
	 */
	public function startOfWeek()
	{
		$this->minusDays($this->getDayOfWeekAsNumeric())->startOfDay();

		return $this;
	}

	/**
	 * Use the end of the week.
	 *
	 * @return ExopiteDate
	 */
	public function endOfWeek()
	{
		$this->addDays(6 - $this->getDayOfWeekAsNumeric())->endOfDay();

		return $this;
	}

	/**
	 * Use the start of the month.
	 *
	 * @return ExopiteDate
	 */
	public function startOfMonth()
	{
		$this->setDay(1)->startOfDay();

		return $this;
	}

	/**
	 * Use the end of the month.
	 *
	 * @return ExopiteDate
	 */
	public function endOfMonth()
	{
		$this->setDay($this->getDaysInMonth())->endOfDay();

		return $this;
	}

	/**
	 * Add one day.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneDay()
	{
		return $this->modifyDays(1);
	}

	/**
	 * Add a given amount of days.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addDays($amount)
	{
		return $this->modifyDays($amount);
	}

	/**
	 * Minus one day.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneDay()
	{
		return $this->modifyDays(1, true);
	}

	/**
	 * Minus a given amount of days.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusDays($amount)
	{
		return $this->modifyDays($amount, true);
	}

	/**
	 * Modify by an amount of days.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyDays($amount, $invert = false)
	{
		if ($this->isFloat($amount))
		{
			return $this->modifyHours($amount * 24, $invert);
		}

		$interval = new DateInterval("P{$amount}D");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Add one month.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneMonth()
	{
		return $this->modifyMonths(1);
	}

	/**
	 * Add a given amount of months.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addMonths($amount)
	{
		return $this->modifyMonths($amount);
	}

	/**
	 * Minus one month.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneMonth()
	{
		return $this->modifyMonths(1, true);
	}

	/**
	 * Minus a given amount of months.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusMonths($amount)
	{
		return $this->modifyMonths($amount, true);
	}

	/**
	 * Modify by an amount of months.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyMonths($amount, $invert = false)
	{
		if ($this->isFloat($amount))
		{
			return $this->modifyWeeks($amount * 4, $invert);
		}

		$interval = new DateInterval("P{$amount}M");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Add one year.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneYear()
	{
		return $this->modifyYears(1);
	}

	/**
	 * Add a given amount of years.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addYears($amount)
	{
		return $this->modifyYears($amount);
	}

	/**
	 * Minus one year.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneYear()
	{
		return $this->modifyYears(1, true);
	}

	/**
	 * Minus a given amount of years.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusYears($amount)
	{
		return $this->modifyYears($amount, true);
	}

	/**
	 * Modify by an amount of Years.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyYears($amount, $invert = false)
	{
		if ($this->isFloat($amount))
		{
			return $this->modifyMonths($amount * 12, $invert);
		}

		$interval = new DateInterval("P{$amount}Y");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Add one hour.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneHour()
	{
		return $this->modifyHours(1);
	}

	/**
	 * Add a given amount of hours.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addHours($amount)
	{
		return $this->modifyHours($amount);
	}

	/**
	 * Minus one hour.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneHour()
	{
		return $this->modifyHours(1, true);
	}

	/**
	 * Minus a given amount of hours.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusHours($amount)
	{
		return $this->modifyHours($amount, true);
	}

	/**
	 * Modify by an amount of hours.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyHours($amount, $invert = false)
	{
		if ($this->isFloat($amount))
		{
			return $this->modifyMinutes($amount * 60, $invert);
		}

		$interval = new DateInterval("PT{$amount}H");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Add one minute.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneMinute()
	{
		return $this->modifyMinutes(1);
	}

	/**
	 * Add a given amount of minutes.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addMinutes($amount)
	{
		return $this->modifyMinutes($amount);
	}

	/**
	 * Minus one minute.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneMinute()
	{
		return $this->modifyMinutes(1, true);
	}

	/**
	 * Minus a given amount of minutes.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusMinutes($amount)
	{
		return $this->modifyMinutes($amount, true);
	}

	/**
	 * Modify by an amount of minutes.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyMinutes($amount, $invert = false)
	{
		if ($this->isFloat($amount))
		{
			return $this->modifySeconds($amount * 60, $invert);
		}

		$interval = new DateInterval("PT{$amount}M");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Add one second.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneSecond()
	{
		return $this->modifySeconds(1);
	}

	/**
	 * Add a given amount of seconds.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addSeconds($amount)
	{
		return $this->modifySeconds($amount);
	}

	/**
	 * Minus one second.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneSecond()
	{
		return $this->modifySeconds(1, true);
	}

	/**
	 * Minus a given amount of seconds.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusSeconds($amount)
	{
		return $this->modifySeconds($amount, true);
	}

	/**
	 * Modify by an amount of seconds.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifySeconds($amount, $invert = false)
	{
		$interval = new DateInterval("PT{$amount}S");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Add one week.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addOneWeek()
	{
		return $this->modifyWeeks(1);
	}

	/**
	 * Add a given amount of weeks.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function addWeeks($amount)
	{
		return $this->modifyWeeks($amount);
	}

	/**
	 * Minus one week.
	 *
	 * @return ExopiteDate
	 */
	public function minusOneWeek()
	{
		return $this->modifyWeeks(1, true);
	}

	/**
	 * Minus a given amount of weeks.
	 *
	 * @param  int  $amount
	 * @return ExopiteDate
	 */
	public function minusWeeks($amount)
	{
		return $this->modifyWeeks($amount, true);
	}

	/**
	 * Modify by an amount of weeks.
	 *
	 * @param  int  $amount
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyWeeks($amount, $invert = false)
	{
		if ($this->isFloat($amount))
		{
			return $this->modifyDays($amount * 7, $invert);
		}

		$interval = new DateInterval("P{$amount}W");

		$this->modifyFromInterval($interval, $invert);

		return $this;
	}

	/**
	 * Modify from a DateInterval object.
	 *
	 * @param  DateInterval  $interval
	 * @param  bool  $invert
	 * @return ExopiteDate
	 */
	protected function modifyFromInterval($interval, $invert = false)
	{
		if ($invert)
		{
			$this->sub($interval);
		}
		else
		{
			$this->add($interval);
		}

		return $this;
	}

	/**
	 * Set the timezone.
	 *
	 * @param  string|DateTimeZone  $timezone
	 * @return ExopiteDate
	 */
	public function setTimezone($timezone)
	{
		$timezone = $this->parseSuppliedTimezone($timezone);

		parent::setTimezone($timezone);

		return $this;
	}

	/**
	 * Sets the timestamp from a human readable string.
	 *
	 * @param  string  $string
	 * @return ExopiteDate
	 */
	public function setTimestampFromString($string)
	{
		$this->setTimestamp(strtotime($string));

		return $this;
	}

	/**
	 * Determine if day is a weekday.
	 *
	 * @return bool
	 */
	public function isWeekday()
	{
		$day = $this->getDayOfWeek();

		return ! in_array($day, array('Saturday', 'Sunday'));
	}

	/**
	 * Determine if day is a weekend.
	 *
	 * @return bool
	 */
	public function isWeekend()
	{
		return ! $this->isWeekday();
	}

	/**
	 * Get the difference in years.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getDifferenceInYears($compare = null)
	{
		if ( ! $compare)
		{
			$compare = new ExopiteDate(null, $this->getTimezone());
		}

		return $this->diff($compare)->format('%r%y');
	}

	/**
	 * Get the difference in months.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getDifferenceInMonths($compare = null)
	{
		if ( ! $compare)
		{
			$compare = new ExopiteDate(null, $this->getTimezone());
		}

		$difference = $this->diff($compare);

		list($years, $months) = explode(':', $difference->format('%y:%m'));

		return (($years * 12) + $months) * $difference->format('%r1');
	}

	/**
	 * Get the difference in days.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getDifferenceInDays($compare = null)
	{
		if ( ! $compare)
		{
			$compare = new ExopiteDate(null, $this->getTimezone());
		}

		return $this->diff($compare)->format('%r%a');
	}

	/**
	 * Get the difference in hours.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getDifferenceInHours($compare = null)
	{
		return $this->getDifferenceInMinutes($compare) / 60;
	}

	/**
	 * Get the difference in minutes.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getDifferenceInMinutes($compare = null)
	{
		return $this->getDifferenceInSeconds($compare) / 60;
	}

	/**
	 * Get the difference in seconds.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getDifferenceInSeconds($compare = null)
	{
		if ( ! $compare)
		{
			$compare = new ExopiteDate(null, $this->getTimezone());
		}

		$difference = $this->diff($compare);

		list($days, $hours, $minutes, $seconds) = explode(':', $difference->format('%a:%h:%i:%s'));

		// Add the total amount of seconds in all the days.
		$seconds += ($days * 24 * 60 * 60);

		// Add the total amount of seconds in all the hours.
		$seconds += ($hours * 60 * 60);

		// Add the total amount of seconds in all the minutes.
		$seconds += ($minutes * 60);

		return $seconds * $difference->format('%r1');
	}

	/**
	 * Get a relative date string, e.g., 3 days ago.
	 *
	 * @param  ExopiteDate  $compare
	 * @return string
	 */
	public function getRelativeDate($compare = null)
	{
		if ( ! $compare)
		{
			$compare = new ExopiteDate(null, $this->getTimezone());
		}

		$units = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year');
		$values = array(60, 60, 24, 7, 4.35, 12);

		// Get the difference between the two timestamps. We'll use this to cacluate the
		// actual time remaining.
		$difference = abs($compare->getTimestamp() - $this->getTimestamp());

		for ($i = 0; $i < count($values) and $difference >= $values[$i]; $i++)
		{
			$difference = $difference / $values[$i];
		}

		// Round the difference to the nearest whole number.
		$difference = round($difference);

		if ($compare->getTimestamp() < $this->getTimestamp())
		{
			$suffix = 'from now';
		}
		else
		{
			$suffix = 'ago';
		}

		// Get the unit of time we are measuring. We'll then check the difference, if it is not equal
		// to exactly 1 then it's a multiple of the given unit so we'll append an 's'.
		$unit = $units[$i];

		if ($difference != 1)
		{
			$unit .= 's';
		}

		return $difference.' '.$unit.' '.$suffix;
	}

	/**
	 * Get a date string in the format of 2012-12-04.
	 *
	 * @return string
	 */
	public function getDate( $format = null )
	{
        switch ( $format ) {
            case 'en_US':
            case 'us':
                $format = 'm/d/Y';
                break;
            case 'de':
            case 'de_DE':
            case 'deutsch':
            $format = 'd.m.Y';
                break;
            case 'hu':
            case 'hu_HU':
            case 'magyar':
            $format = 'Y.m.d';
                break;
        }

        return ( empty( $format ) ) ? $this->format('Y-m-d') : $this->format( $format );

	}

    /**
     * Get a date and time string in the format of 2012-12-04 23:43:27.
     *
     * @return string
     */
    public function getDateTime( $format = null )
    {
        switch ( $format ) {
            case 'en_US':
            case 'us':
                $format = 'm/d/Y H:i:s';
                break;
            case 'de':
            case 'de_DE':
            case 'deutsch':
                $format = 'd.m.Y H:i:s';
                break;
            case 'hu':
            case 'hu_HU':
            case 'magyar':
                $format = 'Y.m.d H:i:s';
                break;
        }

        return ( empty( $format ) ) ? $this->format('Y-m-d H:i:s') : $this->format( $format );
    }

    /**
     * ISO-8601 week number of year, weeks starting on Monday
     * Example: 42 (the 42nd week in the year)
     *
     * @return string
     */
    public function getWeekNumber()
    {
        return $this->format('W');
    }

    /**
     * ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0)
     * 1 (for Monday) through 7 (for Sunday).
     *
     * @return string
     */
    public function getDayOfTheWeek()
    {
        return $this->format('N');
    }

    public function createFormatter( $local, $variant ) {
        $local = ( $local == 'local' ) ? Locale::getDefault() : $local;
        return new IntlDateFormatter(
            $local,
            // Locale::getDefault(),
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            $variant
        );
    }

    /**
     * A full textual representation of the day of the week.
     *
     * @param  string $local   [ISO local, eg: us_EN]
     * @param  string $variant [ful/short]
     * @return string
     */
    public function getDayName( $local = null, $variant = 'full' )
    {
        if ( isset( $local ) || $local = 'local' ) {
        // if ( isset( $local ) && class_exists( 'IntlDateFormatter' ) ) { //check if exist
            $variant = ( $variant == 'full' ) ? 'EEEE' : 'EEE';
            return $this->createFormatter( $local, $variant )->format( $this->getTimestamp() );
        }
        return $this->format('l');
    }

    /**
     * The day of the year (starting from 0).
     *
     * @return string
     */
    public function getDayOfTheYear()
    {
        return $this->format('z');
    }

    /**
     * A full textual representation of a month, such as January or March.
     *
     * @return string
     * @link http://php.net/manual/en/intldateformatter.format.php
     */
    public function getMonthName( $local = null, $variant = 'full' )
    {
        if ( isset( $local ) || $local = 'local' ) {
            $variant = ( $variant == 'full' ) ? 'MMMM' : 'MMM';
            return $this->createFormatter( $local, $variant )->format( $this->getTimestamp() );
        }
        return $this->format('F');
    }

	/**
	 * Get a date string in the format of Jan 31, 1991.
	 *
	 * @return string
	 */
	public function getShortDate()
	{
		return $this->format('M j, Y');
	}

	/**
	 * Get a date string in the format of January 31st, 1991 at 7:45am.
	 *
	 * @return string
	 */
	public function getLongDate()
	{
		return $this->format('F jS, Y \a\\t g:ia');
	}

	/**
	 * Get a date string in the format of 07:42:32.
	 *
	 * @return string
	 */
	public function getTime()
	{
		return $this->format('H:i:s');
	}

	/**
	 * Get a date string in the default format.
	 *
	 * @return string
	 */
	public function getDefaultDate()
	{
		return $this->format($this->defaultDateFormat);
	}

	/**
	 * Set the default date format.
	 *
	 * @param  string  $format
	 * @return ExopiteDate
	 */
	public function setDefaultDateFormat($format)
	{
		$this->defaultDateFormat = $format;

		return $this;
	}

	/**
	 * Set the starting day of the week, where 0 is Sunday and 1 is Monday.
	 *
	 * @param int|string $weekStartDay
	 * @return void
	 */
	public function setWeekStartDay($weekStartDay)
	{
		if (is_numeric($weekStartDay))
		{
			$this->weekStartDay = $weekStartDay;
		}
		else
		{
			$this->weekStartDay = array_search(strtolower($weekStartDay), array('sunday', 'monday'));
		}

		return $this;
	}

	/**
	 * Get the starting day of the week, where 0 is Sunday and 1 is Monday
	 *
	 * @return int
	 */
	public function getWeekStartDay()
	{
		return $this->weekStartDay;
	}

	/**
	 * Get a date attribute.
	 *
	 * @param  string  $attribute
	 * @return mixed
	 */
	protected function getDateAttribute($attribute)
	{
		switch ($attribute)
		{
			case 'Day':
				return $this->format('d');
				break;
			case 'Month':
				return $this->format('m');
				break;
			case 'Year':
				return $this->format('Y');
				break;
			case 'Hour':
				return $this->format('G');
				break;
			case 'Minute':
				return $this->format('i');
				break;
			case 'Second':
				return $this->format('s');
				break;
			case 'DayOfWeek':
				return $this->format('l');
				break;
			case 'DayOfWeekAsNumeric':
				return (7 + $this->format('w') - $this->getWeekStartDay()) % 7;
				break;
			case 'DaysInMonth':
				return $this->format('t');
				break;
			case 'DayOfYear':
				return $this->format('z');
				break;
			case 'DaySuffix':
				return $this->format('S');
				break;
			case 'GmtDifference':
				return $this->format('O');
				break;
			case 'SecondsSinceEpoch':
				return $this->format('U');
				break;
			case 'TimezoneName':
				return $this->getTimezone()->getName();
				break;
		}

		throw new InvalidArgumentException('The date attribute ['.$attribute.'] could not be found.');
	}

	/**
	 * Syntactical sugar for determining if date object "is" a condition.
	 *
	 * @param  string  $attribute
	 * @return mixed
	 */
	protected function isDateAttribute($attribute)
	{
		switch ($attribute)
		{
			case 'LeapYear':
				return (bool) $this->format('L');
				break;
			case 'AmOrPm':
				return $this->format('A');
				break;
			case 'DaylightSavings':
				return (bool) $this->format('I');
				break;
		}

		throw new InvalidArgumentException('The date attribute ['.$attribute.'] could not be found.');
	}

	/**
	 * Set a date attribute.
	 *
	 * @param  string  $attribute
	 * @return mixed
	 */
	protected function setDateAttribute($attribute, $value)
	{
		switch ($attribute)
		{
			case 'Day':
				return $this->setDate($this->getYear(), $this->getMonth(), $value);
				break;
			case 'Month':
				return $this->setDate($this->getYear(), $value, $this->getDay());
				break;
			case 'Year':
				return $this->setDate($value, $this->getMonth(), $this->getDay());
				break;
			case 'Hour':
				return $this->setTime($value, $this->getMinute(), $this->getSecond());
				break;
			case 'Minute':
				return $this->setTime($this->getHour(), $value, $this->getSecond());
				break;
			case 'Second':
				return $this->setTime($this->getHour(), $this->getMinute(), $value);
				break;
		}

		throw new InvalidArgumentException('The date attribute ['.$attribute.'] could not be set.');
	}

	/**
	 * Alias for ExopiteDate::equalTo()
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function sameAs(ExopiteDate $date)
	{
		return $this->equalTo($date);
	}

	/**
	 * Determine if date is equal to another Exopite Date instance.
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function equalTo(ExopiteDate $date)
	{
		return $this == $date;
	}

	/**
	 * Determine if date is not equal to another Exopite Date instance.
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function notEqualTo(ExopiteDate $date)
	{
		return ! $this->equalTo($date);
	}

	/**
	 * Determine if date is greater than another Exopite Date instance.
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function greaterThan(ExopiteDate $date)
	{
		return $this > $date;
	}

    /**
     * Determine if date is greater than another Exopite Date instance.
     *
     * @param  ExopiteDate  $date
     * @return bool
     */
    public function after(ExopiteDate $date)
    {
        return $this->greaterThan($date);
    }

	/**
	 * Determine if date is less than another Exopite Date instance.
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function lessThan(ExopiteDate $date)
	{
		return $this < $date;
	}

    /**
     * Determine if date is less than another Exopite Date instance.
     *
     * @param  ExopiteDate  $date
     * @return bool
     */
    public function before(ExopiteDate $date)
    {
        return $this->lessThan($date);
    }

	/**
	 * Determine if date is greater than or equal to another Exopite Date instance.
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function greaterOrEqualTo(ExopiteDate $date)
	{
		return $this >= $date;
	}

	/**
	 * Determine if date is less than or equal to another Exopite Date instance.
	 *
	 * @param  ExopiteDate  $date
	 * @return bool
	 */
	public function lessOrEqualTo(ExopiteDate $date)
	{
		return $this <= $date;
	}

    /**
     * Determine if date is betwwen than another Exopite Dates instances (start and end).
     *
     * @param  ExopiteDate $start_date
     * @param  ExopiteDate $end_date
     * @return bool
     */
    public function between(ExopiteDate $start_date, ExopiteDate $end_date)
    {
        return $this->greaterThan($start_date) && $this->lessThan($end_date);
    }

    /**
     * Determine if date is betwwen than or equal another Exopite Dates instances (start and end).
     *
     * @param  ExopiteDate $start_date
     * @param  ExopiteDate $end_date
     * @return bool
     */
    public function betweenEqualTo(ExopiteDate $start_date, ExopiteDate $end_date)
    {
        return $this->greaterOrEqualTo($start_date) && $this->lessOrEqualTo($end_date);
    }

	/**
	 * Dynamically handle calls for date attributes and testers.
	 *
	 * @param  string  $method
	 * @param  array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (substr($method, 0, 3) == 'get' or substr($method, 0, 3) == 'set')
		{
			$attribute = substr($method, 3);
		}
		elseif (substr($method, 0, 2) == 'is')
		{
			$attribute = substr($method, 2);

			return $this->isDateAttribute($attribute);
		}

		if ( ! isset($attribute))
		{
			throw new InvalidArgumentException('Could not dynamically handle method call ['.$method.']');
		}

		if (substr($method, 0, 3) == 'set')
		{
			return $this->setDateAttribute($attribute, $parameters[0]);
		}

		// If not setting an attribute then we'll default to getting an attribute.
		return $this->getDateAttribute($attribute);
	}

	/**
	 * Return the default date format when casting to string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->getDefaultDate();
	}

	/**
	 * Determine if a given amount is a floating point number.
	 *
	 * @param  int|float  $amount
	 * @return bool
	 */
	protected function isFloat($amount)
	{
		return is_float($amount) and intval($amount) != $amount;
	}

	/**
	 * Return copy of Exopite Date object
	 *
	 * @return ExopiteDate
	 */
	public function copy()
	{
		return clone $this;
	}

}
