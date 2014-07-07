<?php

/**
 * Mail logging class.
 *
 * PHP Version 5.5
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Libraries
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @copyright  2012-2014, M2Mobi BV, Amsterdam, The Netherlands
 * @license    http://lunr.nl/LICENSE MIT License
 */

namespace Lunr\Feedback;

/**
 * Class for logging messages in mails
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Libraries
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 */
class MailLogger extends AbstractLogger
{

    /**
     * Information defining the mail parameters in the format
     * Array(
     *   'from' => emailFrom
     *   'to'   => emailTo or Array(emailTo1, emailTo2,...)
     * )
     * @var Array
     */
    private $parameters;


    /**
     * Instance of the Mail class
     * @var \Lunr\Network\Mail
     */
    private $mail;

    /**
     * Constructor.
     *
     * @param Array                         $parameters Information defining the mail parameters
     * @param \Lunr\Core\DateTime           $datetime   Instance of the DateTime class.
     * @param \Lunr\Corona\RequestInterface $request    Shared instance of the Request class.
     * @param \Lunr\Network\Mail            $mail       Instance of the Mail class
     */
    public function __construct($parameters, $datetime, $request, $mail)
    {
        $this->parameters = $parameters;
        $this->mail       = $mail;

        parent::__construct($request, $datetime);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->parameters);
        unset($this->mail);

        parent::__destruct();
    }

    /**
     * Set the mail 'from' field from parameters.
     *
     * @return MailLogger $self Self reference
     */
    private function setMailFrom()
    {
        if (isset($this->parameters['from']))
        {
            $this->mail->set_from($this->parameters['from']);
        }

        return $this;
    }

    /**
     * Set the mail 'to' field from parameters.
     *
     * @return MailLogger $self Self reference
     */
    private function setMailTo()
    {
        if (isset($this->parameters['to']))
        {
            if (is_array($this->parameters['to']))
            {
                foreach ($this->parameters['to'] as $emailTo)
                {
                    $this->mail->add_to($emailTo);
                }
            }
            else
            {
                $this->mail->add_to($this->parameters['to']);
            }
        }

        return $this;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level   Log level (severity)
     * @param String $message Log Message
     * @param array  $context Additional meta-information for the log
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->setMailFrom()
             ->setMailTo();

        $subject = strtoupper($level) . ' ' . $this->request->host . ' [' . $this->datetime->get_datetime() . ']';
        $msg     = $this->compose_message($message, $context);

        $this->mail->set_subject($subject)
                   ->set_message($msg)
                   ->send();
    }

}

?>
