<?php
App::import('Vendor', 'autoload', false, array(), 'autoload.php');
App::uses('AbstractTransport', 'Network/Email');

use Aws\Ses\SesClient;

/**
 * AmazonSes Transport
 *
 * @package         radig.SesTransport.Lib.Network.Email
 * @copyright       Radig Soluções em TI
 * @author          Radig Dev Team - suporte@radig.com.br
 * @license         MIT
 * @link            http://github.com/radig
 */
class SesApiTransport extends AbstractTransport {
/**
 * Send mail
 *
 * @param CakeEmail $email CakeEmail
 * @return array
 */
    public function send(CakeEmail $cakeEmail) {
        $config['key'] = $this->_config['username'];
        $config['secret'] = $this->_config['password'];
        $config['region'] = $this->_config['region'];

        $sesEmail = SesClient::factory($config);

        if (isset($this->_config['dkim']) && !empty($this->_config['dkim'])) {
            $sesEmail->setIdentityDkimEnabled(array('Identity' => $this->_config['dkim'], 'DkimEnabled' => true));
        }

        $headers = $cakeEmail->getHeaders(array('from', 'sender', 'replyTo', 'readReceipt', 'returnPath', 'to', 'cc', 'subject'));
        $headers = $this->_headersToString($headers);

        $lines = $cakeEmail->message();
        $messages = array();
        foreach ($lines as $line) {
            if ((!empty($line)) && ($line[0] === '.')) {
                $messages[] = '.' . $line;
            } else {
                $messages[] = $line;
            }
        }

        $message = implode("\r\n", $messages);
        $raw_message = $headers . "\r\n\r\n" . $message . "\r\n\r\n\r\n";

        try {
            $sesEmail->sendRawEmail(array('RawMessage' => array('Data' => base64_encode($raw_message))));
        } catch (MessageRejectedException $e) {
            CakeLog::write('warning', print_r($e->getMessage(),true), 'assync_mail');
            return false;
        }

        return array('headers' => $headers, 'message' => $message);
    }
}
