Mailer Component
================

The `Mailer` component enables you to send emails using different supported protocols.

Current supported protocols are:

* SMTP
* PHPs' `mail()` function
* Sendmail

To use the component, you first need to configure it under `components.mailer`.
Here is an example configuration:

```yaml
components:
    mailer:
        default:
            character_set: utf-8
            max_line_length: 78
            priority: 2 # high priority
            sender:
                email: sven.alhamad@gmail.com
                name: Sven Al Hamad
            transport:
                type: smtp
                host: smtp.gmail.com
                port: 465
                username: me@gmail.com
                password: ***
                encryption: ssl
                auth_mode: login
            antiflood:
                threshold: 99
                sleep: 1
            disable_delivery: false
```

Depending on defined `transport.type` other transport parameters are required. 

## Configuration parameters

The `Mailer` configuration consists of several parameters that are explained in the next few sections.

**Note:** Some of the configuration parameters are bridge-specific, like the `antiflood` parameter. The default bridge is the **SwiftMailer**, which of course supports the antiflood measures.

### Character set (`character_set`)

This is the default character set that will be used in encoding your email content.
By default the character set is set to `utf-8` which supports most language characters.
You might need to change this for some languages, for example, like Japanese.

### Max line length (`max_line_length`)

This parameter is used to make your email more compliant for reading on older email readers.
The `max_line_length` defines how long a single line can be. This parameter should be kept under 1000 characters, as defined by RFC 2822.

### Priority (`priority`)

The priority parameter defines the priority level of your message. 

The following priorities can be set:

- `1` - highest
- `2` - high
- `3` - normal
- `4` - low
- `5` - lowest

This parameter optional.

### Sender (`sender`)

This is the default sender that will be set on your outgoing emails.

### Transport (`transport`)

The transport configuration block consists of following parameters: 

- `type`
    - defines the type of the connection
    - can be `smtp`, `mail` or `sendmail`


These parameters are needed only in case of a SMTP connection:

- `host`
    - defines the location of your smtp host
- `port`
-   - the port used to connect to the host
    - port can vary based on the defined `encryption` and `auth_mode`  
- `username`
    - username needed to connect to the host
- `password`
    - password needed to connect to the host
- `encryption`
    - encryption used for the connection
    - this parameter is optional
    - can be `ssl` or `tls` based on your host
- `auth_mode`
    - authorization mode used to connect to the host
    - this parameter is optional
    - can be `plain`, `login`, `cram-md5`, or `null`

### Antiflood (`antiflood`)

Some mail servers have a set of safety measures that limit the amout of emails that you can send per connection or in some time interval. This is mostly to discourage spammers to user their services, but sometimes that might cause a problem even for non-spammers. In order to avoid falling into these safety measure the `antiflood` parameter can limit how many emails you can send per connection and how much time you have to wait until you can establishe a new connection. 

Don't worry about disconnecting, connecting again and resuming the sending of emails...this is all fully authomized and you don't have to do anything.

The `antiflood` param consists of two attributes:
- `threshold`
    - defines how many emails to send per one connection
- `sleep`
    - defines how many seconds to wait until a new connection can be established and the sending resumed


## Usage

Using the `Mailer` component is quite simple, just implement the `MailerTrait`, build your message and send it.

Here is one simple usage example:

```php
class MyClass
{
    use \Webiny\Bridge\Mailer\MailerTrait;

	function sendEmail() {
		// get the Mailer instance
		$mailer = $this->mailer('default');

		// let's build our message
		$msg = $mailer->getMessage();
		$msg->setSubject('Hello email')
			->setBody('This is my test email body')
			->setTo(['me@gmail.com'=>'Jack']);

		// send it
		$mailer->send($msg);
	}
}
```