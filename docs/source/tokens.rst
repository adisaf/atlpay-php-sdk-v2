Tokens
======

Tokenization is the process ATLPay uses to collect sensitive card or bank account details, or personally identifiable information (PII), directly from your customers in a secure manner. A token representing this information is returned to your server to use. You should use `ATLPay.js <https://www.atlpay.com/js/ATLPay.js>`_ or our mobile libraries to perform this process, client-side. This ensures that no sensitive card data touches your server, and allows your integration to operate in a PCI-compliant way.

If you cannot use client-side tokenization, you can also create tokens using the API with your secret API key. Keep in mind that if your integration uses this method, you are responsible for any PCI compliance that may be required, and you must keep your secret API key safe. Unlike with client-side tokenization, your customer's information is not sent directly to ATLPay, so we cannot determine how it is handled or stored.

Tokens cannot be stored or used more than once.

The token object
----------------
