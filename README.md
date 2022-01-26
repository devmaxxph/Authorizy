# wollito
Wollito Payment Gateway


Wollito Payment Gateway
-----------------------

To install the payment gateway we can use composer.\
 `composer require wollito/wollito:dev-main`

After package installation, you need to include the autoload file in
your project. \
 `require_once "vendor/autoload.php";` \

Ensure your composer.json includes the following;

`             `

``` {style="color: #e83e8c;"}
    "autoload": {
        "psr-4": {
            "WollitoPackage\\": "vendor/wollito/wollito"
        }
    }
                
```

#### Introduction:

To get start you must instantiate the class, you pass the API Key, API
Secret & Currency

Currency options include; GBP, USD, EUR \

`             $wollito = new \WollitoPackage\Wollito("YOUR KEY HERE", "YOUR SECRET KEY HERE", "STATEMENT DESCRIPTOR", "your SITE URL", "CURRENCY (GBP)");         `
\
\

Once you have instantiated the class, you can call function using the
`$wollito` variable.

#### Processing Payments:

The process payment functions, takes the parameters below and can be
called like so:
Always include the payment=true parameter.

` $data = $wollito->create_charge_link("AMOUNT", "ORDER ID", "Return URL (yoururl.com/returnpage.php?payment=true"); `
\
\

#### Response:

The call will return a url or false value

#### Webhook:

Sometimes payments are returned as pending and therefore will need
updating via a webhook.

Webhook calls are posted to webhook.php, to the originating site URL
which made the charge request.

Webhooks can be authenticated with the following call, just pass the
`$_POST` data variable

`             $wollito->validate_webhook_call($_POST);         `

Examples can be found [Here](https://github.com/Wollito/wollito/tree/main/Example)
