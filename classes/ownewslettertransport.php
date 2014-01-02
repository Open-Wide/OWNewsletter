<?php
/**
 * Generate output and mail
 */
class OWNewsletterTransport
{

    /**
    * Holds the properties of this class.
    *
    * @var array(string=>mixed)
    */
    protected $properties = array();

    /**
     * Set all default values
     *
     * @param string $transportMethod
     * @return void
     */
    function __construct( $transportMethod = 'file' )
    {
        $this->transportMethod = $transportMethod;
    }

    /**
     * Choose opportuneness send-logic
     *
     * @param object $ezcMailComposerObject
     * @return error message / boolean
     */
    public function send( ezcMail $ezcMailComposerObject )
    {
        $iniTransport = $this->transportMethod;
        $newsletterINI = eZINI::instance( 'newsletter.ini' );

        switch ( $iniTransport )
        {
            case 'smtp':
            {
                // read smtp settings from ini
                $smtpTransportServer = $newsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportServer' );
                $smtpTransportPort = $newsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportPort' );
                $smtpTransportUser = $newsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportUser' );
                $smtpTransportPassword = $newsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportPassword' );
                $smtpTransportConnectionType = $newsletterINI->variable( 'NewsletterMailSettings', 'SmtpTransportConnectionType' );

                $options = new ezcMailSmtpTransportOptions();
                if( $smtpTransportConnectionType )
                {
                    $options->connectionType = $smtpTransportConnectionType;
                }
                $transport = new ezcMailSmtpTransport(  $smtpTransportServer,
                                                        $smtpTransportUser,
                                                        $smtpTransportPassword,
                                                        $smtpTransportPort,
                                                        $options );

            } break;
            case 'file':
            {
                // var/log/mail
                $mailDir = $newsletterINI->variable( 'NewsletterMailSettings', 'FileTransportMailDir' );
                $transport = new OWNewsletterTransportFile( $mailDir );
            } break;
            case 'sendmail':
            case 'mta':
            {
                $transport = new ezcMailMtaTransport();
            } break;
        }

        try
        {
            $transport->send( $ezcMailComposerObject );
            // OWNewsletterLog::writeInfo( 'email send ok', 'OWNewsletterTransport', 'send' );

            return true;
        }
        catch ( ezcMailTransportException $e )
        {
            // error by transport with tracking
            eZDebug::writeError( 'OWNewsletterTransport:send: ' . $e->getMessage()  );
            return $e;
        }
    }

    /**
     * Sets the property $name to $value.
     *
     * @param string $name
     * @param string $value
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name does not exist
     * @throws ezcBaseValueException
     *         if $value is not accepted for the property $name
     * @param string $name
     * @param mixed $value
     * @return void
     * @ignore
     */
    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'transportMethod':
                if ( !(  in_array($value, array( 'file', 'smtp', 'sendmail' ) ) ) )
                {
                    throw new ezcBaseValueException( 'transportMethod', $value, 'file, smtp, sendmail' );
                }
                $this->properties[$name] = $value;
                break;

            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }

    /**
     * Returns the value of the property $name.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name does not exist
     *
     * @param string $name
     * @return mixed
     * @ignore
     */
    public function __get( $name )
    {
        switch ( $name )
        {
            case 'transportMethod':
                return $this->properties[$name];
            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }

    /**
     * Returns true if the property $name is set, otherwise false.
     *
     * @param string $name
     * @return boolean
     * @ignore
     */
    public function __isset( $name )
    {
        switch ( $name )
        {
            case 'transportMethod':
                return isset( $this->properties[$name] );

            default:
                return false;
        }
    }

}

?>
