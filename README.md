### Prerequisites

- PHP 8.0.x
- [Composer](https://getcomposer.org/download/)
- [Symfony Server](https://symfony.com/download)

Edit the .env file in the project root and change Infobip parameters accordingly:
```console
INFOBIP_API_KEY=somekey
INFOBIP_API_BASE_URL=https://youridentifier.api.infobip.com/
INFOBIP_SMS_RECIPIENT_NUMBER=phonenumber
```

#### Install dependencies and start server
```console
make dev
```

#### Run tests
```console
make test
```

#### Send reports
```console
make reports
```

The reports take care of their own schedules, so the above command is 
meant to be added to cron like so: * * * * *

#### Start the message queue (for consuming pending SMS notifications)
```console
make queue
```

#### Use the app

- import the postman collection from project root and start testing
