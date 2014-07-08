<?php

/**
 * Mail logging class.
 *
 * PHP Version 5.4
 *
 * @category   Libraries
 * @package    Feedback
 * @subpackage Libraries
 * @author     Damien Tardy-Panis <damien@m2mobi.com>
 * @copyright  2014, M2Mobi BV, Amsterdam, The Netherlands
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
     * Email address of the sender
     * @var String
     */
    private $from;

    /**
     * Email address(es) of the receiver(s)
     * @var Mixed
     */
    private $to;

    /**
     * Instance of the Mail class
     * @var \Lunr\Network\Mail
     */
    private $mail;

    /**
     * Constructor.
     *
     * @param String                        $from    Email address of the sender
     * @param Mixed                         $to      Email address(es) of the receiver(s)
     * @param \Lunr\Corona\RequestInterface $request Shared instance of the Request class.
     * @param \Lunr\Network\Mail            $mail    Instance of the Mail class
     */
    public function __construct($from, $to, $request, $mail)
    {
        $this->mail = $mail;
        $this->from = $from;
        $this->to   = $to;

        parent::__construct($request);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->mail);
        unset($this->from);
        unset($this->to);

        parent::__destruct();
    }

    /**
     * Set the mail 'from' field from parameters.
     *
     * @return MailLogger $self Self reference
     */
    private function set_mail_from()
    {
        $this->mail->set_from($this->from);

        return $this;
    }

    /**
     * Set the mail 'to' field from parameters.
     *
     * @return MailLogger $self Self reference
     */
    private function set_mail_to()
    {
        if (is_array($this->to))
        {
            foreach ($this->to as $email_to)
            {
                $this->mail->add_to($email_to);
            }
        }
        else
        {
            $this->mail->add_to($this->to);
        }

        return $this;
    }

    /**
     * Compose message string.
     *
     * @param String $message Base message with placeholders
     * @param array  $context Additional meta-information for the log
     *
     * @return String $msg Log Message String
     */
    protected function compose_message($message, $context)
    {
        $suffix = '';

        if (!empty($context['file']) && !empty($context['line']))
        {
            $suffix .= ' (' . $context['file'] . ': ' . $context['line'] . ')';
        }

        return $this->interpolate_message($message, $context) . $suffix;
    }

    /**
     * Compose subject string.
     *
     * @param String $level Log level
     *
     * @return String $subject Log subject String
     */
    private function compose_subject($level)
    {
        $subject = strtoupper($level);

        if ($this->request->host !== NULL)
        {
            $subject .= ' ' . $this->request->host;
        }

        if ($this->request->call !== NULL)
        {
            $subject .= ' ' . $this->request->call;
        }

        return $subject;
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
    public function log($level, $message, array $context = [])
    {
        $this->set_mail_from()
             ->set_mail_to();

        $subject = $this->compose_subject($level);
        $msg     = $this->compose_message($message, $context);

        $this->mail->set_subject($subject)
                   ->set_message($msg)
                   ->send();
    }

}

?>
