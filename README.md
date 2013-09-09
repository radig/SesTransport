# CakePHP SesTransport

Transport to send email with Amazon AWS SES

# Dependencies

* CakePHP 2.x
* "AWS SDK for PHP 2":https://github.com/aws/aws-sdk-php

# Install

## With Composer

<pre>
    composer install radig/SesTransport
</pre>

## Without Composer

* Clone or download into Plugin directory. Directory should named 'SesTransport'
* Install AWS-SDK for PHP into app/Vendor/AWS

## How to use

Just setup your email configuration as any other

<pre>
    public $default = array(
        'transport' => 'SesTransport.SesApi'
    );
</pre>

Now, just send your message

<pre>
    $email = new CakeEmail()
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->send();
</pre>

h2. Author e Copyright

* *Copyright 2013* "*Radig - Soluções em TI*":http://www.radig.com.br
* Licensed under MIT (file attached).
