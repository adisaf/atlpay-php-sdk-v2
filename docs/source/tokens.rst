Tokens
======

Tokenization is the process ATLPay uses to collect sensitive card or bank account details, or personally identifiable information (PII), directly from your customers in a secure manner. A token representing this information is returned to your server to use. You should use `ATLPay.js <https://www.atlpay.com/js/ATLPay.js>`_ or our mobile libraries to perform this process, client-side. This ensures that no sensitive card data touches your server, and allows your integration to operate in a PCI-compliant way.

If you cannot use client-side tokenization, you can also create tokens using the API with your secret API key. Keep in mind that if your integration uses this method, you are responsible for any PCI compliance that may be required, and you must keep your secret API key safe. Unlike with client-side tokenization, your customer's information is not sent directly to ATLPay, so we cannot determine how it is handled or stored.

Tokens cannot be stored or used more than once.

API Endpoint : ``https://api.atlpay.com/v2/tokens``

The token object
----------------
.. csv-table::
   :header: "Attribute", "Description"
   :widths: 20, 80

   "type", "type of the object, in this case it will return ``token``"
   "id", "Unique identifier for the object."
   "card.fundingType", "Funding type of card ``CREDIT`` or ``DEBIT``"
   "card.country", "ISO 2 country code of the card eg. GB, FR etc."
   "card.last4Digits", "Last 4 digits of the card"
   "card.type", "Type of the card eg. ``VISA_DEBIT`` OR ``VISA_CREDIT``"
   "card.brand", "Brand of the card eg. Visa, MasterCard etc."
   "card.bank", "Issuer Bank of the Card"
   "card.name", "Cardholder name"
   "card.authorization", "If additional authorization is required or not. Please refer to 3-D Security for more details."
   "card.addressLine1", "Billing address line 1"
   "card.addressLine2", "Billing address line 2"
   "card.addressCity", "Billing city"
   "card.addressState", "Billing state/region/county"
   "card.addressZip", "Billing zipcode or postal code"
   "card.addressCountry", "ISO 2 country code of billing country"
   "created", "Time at which the object was created. Measured in seconds since the Unix epoch."
   "mode", "``live`` if the object exists in live mode or the value ``test`` if the object exists in test mode."
 

   
Create a card token
-------------------
Creates a single-use token that represents a credit card's details. This token can be used in place of a credit card dictionary with any API method. These tokens can be used only once.

In most cases, you should create tokens client-side using ATLPay.js or our mobile libraries, instead of using the API.

.. csv-table::
   :header: "Attribute", "Mandatory", "Description"
   :widths: 20, 20, 60

   "card[number]", "Yes", "The card number, as a string without any separators."
   "card[cvc]", "Yes", "Card security code."
   "card[exp_month]", "Yes", "Two-digit number representing the card's expiration month."
   "card[exp_year]", "Yes", "Four-digit number representing the card's expiration year."
   "card[name]", "Yes", "Cardholder's full name."
   "address[address_line1]", "No", "Billing address line 1"
   "address[address_line2]", "No", "Billing address line 2"
   "address[city]", "No", "Billing city"
   "address[state]", "No", "Billing state/region/county"
   "address[zipcode]", "No", "Billing zipcode or postal code"
   "address[country]", "No", "ISO 2 country code of billing country"
   "shopper[ip]", "Yes", "IP Address of customer"
   "shopper[session_id]", "Yes", "Session ID of customer online session"
   "shopper[email]", "Yes", "Email address of the customer"
   
**Returns**

Returns the created card token if successful. Otherwise, this call returns an error.
   
Example request

.. code-block:: bash

    curl -X POST \
      https://api.atlpay.com/v2/tokens \
      -H 'X-Api-Key: YOUR_API_KEY' \
      -F 'card[number]=5555555555554444' \
      -F 'card[cvc]=938' \
      -F 'card[exp_month]=08' \
      -F 'card[exp_year]=2020' \
      -F 'card[name]=USER NAME' \
      -F 'shopper[ip]=203.163.245.135' \
      -F 'shopper[session_id]=sess_12312asdhasd7' \
      -F 'shopper[email]=user@example.com'
	  
Example response

.. code-block:: json

   {
       "type": "token",
       "id": "776e05d1-cda5-4f78-8d36-761a23b8a30a",
       "card": {
           "fundingType": "DEBIT",
           "country": "GB",
           "last4Digits": "4444",
           "type": "VISA_DEBIT",
           "brand": "VISA",
           "bank": "BANK OF IRELAND (UK) PLC",
           "name": "DEMO USER",
           "authorization": "REQUIRED",
           "addressLine1": null,
           "addressLine2": null,
           "addressCity": null,
           "addressState": null,
           "addressZip": null,
           "addressCountry": null
       },
       "created": 1530260981,
       "mode": "live"
   }
   
Retrieve a token
----------------
Returns a token if a valid ID was provided. Returns an error otherwise.

Example request

.. code-block:: bash
    
    curl -X GET \
    	https://api.atlpay.com/v2/tokens/776e05d1-cda5-4f78-8d36-761a23b8a30a \
    	-H 'X-Api-Key: YOUR_API_KEY'
	  
Example response

.. code-block:: json

   {
       "type": "token",
       "id": "776e05d1-cda5-4f78-8d36-761a23b8a30a",
       "card": {
           "fundingType": "DEBIT",
           "country": "GB",
           "last4Digits": "4444",
           "type": "VISA_DEBIT",
           "brand": "VISA",
           "bank": "BANK OF IRELAND (UK) PLC",
           "name": "DEMO USER",
           "authorization": "REQUIRED",
           "addressLine1": null,
           "addressLine2": null,
           "addressCity": null,
           "addressState": null,
           "addressZip": null,
           "addressCountry": null
       },
       "created": 1530260981,
       "mode": "live"
   }