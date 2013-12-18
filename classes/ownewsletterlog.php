<?php

/**
 * Log handling of the OW Newsletter using ezcLog
 * logmessage
 * time [severity] [source] [category] ( element1: value1, .... )
 *
 * <code>
 *     $log = OWNewsletterLog::getInstance();
 *     $log->source = "source";
 *     $log->category = "category";
 *     $log->log( $message, ezcLog::INFO , array() );
 *     $log->log( $message, ezcLog::DEBUG , array() );
 *     $log->log( $message, ezcLog::ERROR , array() );
 * </code>
 */
class OWNewsletterLog extends ezcLog {

	/**
	 * Stores the instance of this class
	 *
	 * @var OWNewsletterLog
	 */
	protected static $instance = null;

	/**
	 * if true the logname includes 'cli' in name
	 *
	 * @var boolean
	 */
	protected $isCliMode = false;

	/**
	 * newsletter.ini [DebugSettings] Debug=enabled|disabled
	 * @var boolean
	 */
	private $debug = false;

	/**
	 * Constructs an empty OWNewsletterLog instance
	 *
	 * This constructor is private as this class should be used as a
	 * singleton. Use the getInstance() method instead to get an ezcLog instance.
	 *
	 * @param boolean $isCliMode
	 * @return void
	 */
	function __construct( $isCliMode = false ) {
		if ( $isCliMode === true ) {
			$this->isCliMode = true;
		}
		$this->reset();
		$log = $this;

		// "var/log"
		$ini = eZINI::instance();
		$varDir = eZSys::varDirectory();
		$iniLogDir = $ini->variable( 'FileSettings', 'LogDir' );
		$permissions = octdec( $ini->variable( 'FileSettings', 'LogFilePermissions' ) );
		$logDir = eZDir::path( array( $varDir, $iniLogDir ) );
		$logNamePostfix = '';

		// Debug enabled
		$newsletterIni = eZINI::instance( 'newsletter.ini' );

		if ( $newsletterIni->variable( 'DebugSettings', 'Debug' ) == 'enabled' ) {
			$this->debug = true;
		}

		if ( $isCliMode === true ) {
			$logNamePostfix = 'cli_';
		}

		// Create the writers
		$generalFilename = "newsletter_" . $logNamePostfix . "general.log";
		$errorFilename = "newsletter_" . $logNamePostfix . "error.log";
		$writeAll = new ezcLogUnixFileWriter( $logDir, $generalFilename );
		$writeErrors = new ezcLogUnixFileWriter( $logDir, $errorFilename );

		// Check file permissions
		foreach ( array( $generalFilename, $errorFilename ) as $file ) {
			$path = eZDir::path( array( $logDir, $file ) );
			if ( substr( decoct( fileperms( $path ) ), 2 ) !== $permissions ) {
				@chmod( $path, $permissions );
			}
		}

		$errorFilter = new ezcLogFilter;
		$errorFilter->severity = ezcLog::ERROR;
		$log->getMapper()->appendRule( new ezcLogFilterRule( $errorFilter, $writeErrors, true ) );
		$log->getMapper()->appendRule( new ezcLogFilterRule( new ezcLogFilter, $writeAll, true ) );
	}

	/**
	 * Returns the instance of the class
	 *
	 * @param boolean $isCliMode
	 * @return OWNewsletterLog
	 */
	public static function getInstance( $isCliMode = false ) {
		try {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self( $isCliMode );
				ezcBaseInit::fetchConfig( 'OWNewsletterInitLog', self::$instance );
			}
			return self::$instance;
		} catch ( ezcBaseFilePermissionException $e ) {
			eZDebug::writeError( $e->getMessage(), 'OWNewsletterLog::getInstance()' );
			if ( $isCliMode ) {
				$output = new ezcConsoleOutput();
				$output->formats->error->color = 'red';
				$output->formats->error->style = array( 'bold' );
				$output->outputLine( $e->getMessage(), 'error' );
				exit();
			}
		}
	}

	/**
	 * Write info.
	 *
	 * @param string $message
	 * @param string $source
	 * @param string $category
	 * @param array $attributes
	 * @return void
	 */
	public static function writeInfo( $message, $source = 'default', $category = 'default', array $attributes = array() ) {
		/*
		  OWNewsletterLog::getInstance()->log(
		  "Test log",
		  ezcLog::INFO,
		  array(
		  'source' => 'Access',
		  'type' => 'blog',
		  'data' =>  $data ));
		 */
		$log = OWNewsletterLog::getInstance();
		$log->source = $source;
		$log->category = $category;
		$log->log( $message, ezcLog::INFO, $attributes );
	}

	/**
	 * Write error
	 *
	 * @param string $message
	 * @param string $source
	 * @param string $category
	 * @param array $attributes
	 * @return void
	 */
	public static function writeError( $message, $source = 'default', $category = 'default', array $attributes = array() ) {
		$log = OWNewsletterLog::getInstance();
		$log->source = $source;
		$log->category = $category;
		$log->log( $message, ezcLog::ERROR, $attributes );
	}

	/**
	 * Write Debug
	 *
	 * @param string $message
	 * @param string $source
	 * @param string $category
	 * @param array $attributes
	 * @return void
	 */
	public static function writeDebug( $message, $source = 'default', $category = 'default', array $attributes = array() ) {
		$log = OWNewsletterLog::getInstance();
		if ( $log->isDebugEnabled() === true ) {
			$log->source = $source;
			$log->category = $category;
			$log->log( $message, ezcLog::DEBUG, $attributes );
		}
	}

	/**
	 * Write Notice
	 *
	 * @param string $message
	 * @param string $source
	 * @param string $category
	 * @param array $attributes
	 * @return void
	 */
	public static function writeNotice( $message, $source = 'default', $category = 'default', array $attributes = array() ) {
		$log = OWNewsletterLog::getInstance();
		$log->source = $source;
		$log->category = $category;
		$log->log( $message, ezcLog::NOTICE, $attributes );
	}

	/**
	 *
	 * @return boolean
	 */
	public function isDebugEnabled() {
		return $this->debug;
	}

}
