Authentication
==============

Authenticate your account by including your secret key in API requests. You can manage your API keys in the Dashboard. Your API keys carry many privileges, so be sure to keep them secure! Do not share your secret API keys in publicly accessible areas such GitHub, client-side code, and so forth.

To use your API key, you need only call ``\ATLPay\ATLPay::setSecretKey()`` with your key. The PHP library will then automatically send this key in each request.

cURL Example

.. code-block:: bash

    curl -X POST \
    	https://api.atlpay.com/v2/tokens \
    	-H 'X-Api-Key: YOUR_API_KEY_HERE'

All API requests must be made over HTTPS. Calls made over plain HTTP will fail. API requests without authentication will also fail.