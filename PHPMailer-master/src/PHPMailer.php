<?php
namespace PHPMailer\PHPMailer;

use PHPMailer\PHPMailer\Exception;

class PHPMailer {
    public $Version = '6.8.0';
    public $SMTPDebug = 0;
    public $Host = '';
    public $Port = 25;
    public $SMTPAuth = false;
    public $Username = '';
    public $Password = '';
    public $SMTPSecure = '';
    public $setFrom = '';
    public $setFromName = '';
    public $addAddress = [];
    public $Subject = '';
    public $Body = '';
    public $isHTML = false;
    public $AltBody = '';
    
    public function __construct($exceptions = null) {
        $this->exceptions = $exceptions === null ? true : $exceptions;
    }
    
    public function isSMTP() {
        $this->Mailer = 'smtp';
    }
    
    public function setFrom($address, $name = '', $auto = true) {
        $this->setFrom = $address;
        $this->setFromName = $name;
    }
    
    public function addAddress($address, $name = '') {
        $this->addAddress[] = ['address' => $address, 'name' => $name];
    }
    
    public function isHTML($ishtml = true) {
        $this->isHTML = $ishtml;
    }
    
    public function send() {
        if (empty($this->setFrom)) {
            throw new Exception('From address not set');
        }
        
        $to = '';
        foreach ($this->addAddress as $addr) {
            $to = $addr['address'];
            break;
        }
        
        if (empty($to)) {
            throw new Exception('No recipients set');
        }
        
        $subject = $this->Subject;
        $body = $this->Body;
        
        $headers = "From: {$this->setFromName} <{$this->setFrom}>\r\n";
        $headers .= "Reply-To: {$this->setFrom}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: " . ($this->isHTML ? 'text/html' : 'text/plain') . "; charset=UTF-8\r\n";
        
        if ($this->SMTPAuth && !empty($this->Username) && !empty($this->Password)) {
            $result = $this->sendSMTP($to, $subject, $body, $headers);
        } else {
            $result = mail($to, $subject, $body, $headers);
        }
        
        if (!$result) {
            if ($this->exceptions) {
                throw new Exception('Send failed');
            }
            return false;
        }
        return true;
    }
    
    private function sendSMTP($to, $subject, $body, $headers) {
        $socket = @fsockopen(
            $this->SMTPSecure === 'tls' ? 'tls://' . $this->Host : $this->Host,
            $this->Port,
            $errno,
            $errstr,
            30
        );
        
        if (!$socket) {
            if ($this->exceptions) {
                throw new Exception("Connection failed: $errstr ($errno)");
            }
            return false;
        }
        
        $response = fgets($socket, 515);
        if (substr($response, 0, 3) != '220') {
            fclose($socket);
            return false;
        }
        
        fputs($socket, "EHLO " . gethostname() . "\r\n");
        $this->getSMTPResponse($socket);
        
        if ($this->SMTPSecure === 'tls') {
            fputs($socket, "STARTTLS\r\n");
            $this->getSMTPResponse($socket);
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            fputs($socket, "EHLO " . gethostname() . "\r\n");
            $this->getSMTPResponse($socket);
        }
        
        fputs($socket, "AUTH LOGIN\r\n");
        $this->getSMTPResponse($socket);
        
        fputs($socket, base64_encode($this->Username) . "\r\n");
        $this->getSMTPResponse($socket);
        
        fputs($socket, base64_encode($this->Password) . "\r\n");
        $response = $this->getSMTPResponse($socket);
        if (substr($response, 0, 3) != '235') {
            fclose($socket);
            return false;
        }
        
        fputs($socket, "MAIL FROM: <{$this->setFrom}>\r\n");
        $this->getSMTPResponse($socket);
        
        fputs($socket, "RCPT TO: <{$to}>\r\n");
        $this->getSMTPResponse($socket);
        
        fputs($socket, "DATA\r\n");
        $this->getSMTPResponse($socket);
        
        $message = "To: {$to}\r\n";
        $message .= $headers;
        $message .= "Subject: {$subject}\r\n";
        $message .= "\r\n";
        $message .= $body;
        $message .= "\r\n.\r\n";
        
        fputs($socket, $message);
        $this->getSMTPResponse($socket);
        
        fputs($socket, "QUIT\r\n");
        fgets($socket, 515);
        fclose($socket);
        
        return true;
    }
    
    private function getSMTPResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        return $response;
    }
}
?>