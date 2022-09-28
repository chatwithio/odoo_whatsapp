# Odoo (Whatsapp API)

## Installation

The project is build on Symfony 6 and the PHP version of the language being used is PHP 8.1. Install symfony on your
local machine

- ### Run the following command in order to set up the project

```bash
# Install dependencies
composer update
# or
composer install

# Migrate 
php bin/console doctrine:migrations:migrate

# Run Server
symfony server:start -d

# Add and set value in your .env.local for the following environment variables
WHATSAPP_TEMPLATE_NAME=
WHATSAPP_TEMPLATE_LANGUAGE=
WHATSAPP_TEMPLATE_NAMESPACE=
WHATSAPP_TEMPLATE_ODOO_STATUS=
```

- ### Using the odoo business to send the whatsapp message

  The business can be saved in the `odoo_business` table. We need the following field in order to save the
    - Host
    - Database
    - Business Name
    - Api Key

  You can get all these field by creating a business on Odoo and going through their developer's documentation.

- ### Getting the contacts and sending the message
    - We can get the contact lists and instantly send them the Whatsapp Text message according to out template that we
      want to use to send the message.
    - You can run this command in order to get the contacts and send them the message if they have not already been sent
      the message
      ```
      php bin/console odoo:send-whatsapp
      ```
    - Once the contacts have been retrieved, and the message has been sent, we can see the list of contacts in the
      `odoo_contact` table and list of recipients from those contacts can be seen `odoo_sent_contact` table. 
    - The `status change message` is sent to the contact when the status has changed.

Create your account at tochat.be and get support and your whatsapp api.
