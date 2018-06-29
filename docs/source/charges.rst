Charges
=======

To charge a credit or a debit card, you create a ``Charge`` object. You can retrieve and refund individual charges. Charges are identified by a unique, random ID.

API Endpoint : ``https://api.atlpay.com/v2/charges``

The charge object
-----------------
.. csv-table::
   :header: "Attribute", "Description"
   :widths: 20, 80

   "type", "type of the object, in this case it will return ``charge``"
   "id", "Unique identifier for the object." 
   "currency", "Three-letter ISO currency code, in lowercase. Must be a supported currency." 
   "amount", "A positive integer in the smallest currency unit (e.g., 100 to charge £1.00) representing how much to charge." 
   "fees", "The fee (if any) for the charge."
   "description", "An arbitrary string attached to the object. Often useful for displaying to users."
   "txn_reference", "ID of the invoice this charge is for if one exists."
   "threedSecure.redirect", "The URL provided to you to redirect a customer to as part of a redirect authentication flow."
   "threedSecure.returnUrl", "The URL you provide to redirect the customer to after they authenticated their payment."
   "threedSecure.status", "The status of the redirect, either ``PENDING`` (ready to be used by your customer to authenticate the transaction), ``CHARGEABLE`` (succesful authentication, cannot be reused) or ``NOT_AVAILABLE`` (redirect should not be used) or ``FAILED`` (failed authentication, cannot be reused)."
   "status", "Status of the charge"
   "token", "The token object used for charge"
   "created", "Time at which the object was created. Measured in seconds since the Unix epoch."
   "mode", "``live`` if the object exists in live mode or the value ``test`` if the object exists in test mode."
   
Create a charge
---------------

To charge a credit card or other payment source, you create a Charge object. If your API key is in test mode, the supplied payment source (e.g., card) won't actually be charged, although everything else will occur as if in live mode. (ATLPay assumes that the charge would have completed successfully).

.. csv-table::
   :header: "Attribute", "Mandatory", "Description"
   :widths: 20, 20, 60

   "token", "Yes", "A card token to be charged, like the ones returned by `ATLPay.js <https://www.atlpay.com/js/ATLPay.js>`_."
   "amount", "Yes", "A positive integer in the smallest currency unit (e.g., 100 to charge £1.00) representing how much to charge."
   "currency", "Yes", "3-letter ISO code for currency."
   "description", "Yes", "An arbitrary string which you can attach to a ``Charge`` object. It is displayed when in the web interface alongside the charge."
   "txn_reference", "No", "ID of the invoice this charge is for if one exists."
   "return_url", "YES", "The URL you provide to redirect the customer to after they authenticated their payment."
   
**Returns**

Returns a Charge object if the charge succeeded. Returns an error if something goes wrong. A common source of error is an invalid or expired card, or a valid card with insufficient available balance.

Example request

.. code-block:: bash

    curl -X POST \
      https://api.atlpay.com/v2/charges \
      -H 'X-Api-Key: YOUR_API_KEY' \
      -F token=776e05d1-cda5-4f78-8d36-761a23b8a30a \
      -F amount=500 \
      -F currency=GBP \
      -F 'description=Order Desc' \
      -F 'txn_reference=Your Order Number' \
      -F return_url=https://www.your-3d-return-url.com/
	  
Example response

.. code-block:: json

    {
        "type": "charge",
        "id": "C2018062972567",
        "currency": "GBP",
        "amount": 500,
        "fees": null,
        "description": "Order Desc",
        "txn_reference": "Your Order Number",
        "threedSecure": {
            "redirect": "https://payments.atlpay.com/3d-secure/C2018062972567?paRes=MjU3NDQyM2YtNmFmMy00OTEzLWI1YTUtZmZjY2EzODg4ZGYx",
            "returnUrl": "https://www.your-3d-return-url.com/",
            "status": "PENDING"
        },
        "status": "PENDING",
        "token": {
            "type": "token",
            "id": "776e05d1-cda5-4f78-8d36-761a23b8a30a",
            "card": {
                "fundingType": "DEBIT",
                "country": "GB",
                "last4Digits": "3005",
                "type": "VISA_DEBIT",
                "brand": "VISA",
                "bank": "BANK OF IRELAND (UK) PLC",
                "name": "Richard AMOAH",
                "authorization": "REQUIRED",
                "addressLine1": null,
                "addressLine2": null,
                "addressCity": null,
                "addressState": null,
                "addressZip": null,
                "addressCountry": null
            },
            "created": 1530260981
        },
        "created": 1530266033,
        "mode": "live"
    }
	
Retrieve a charge
-----------------

Retrieves the details of a charge that has previously been created. Supply the unique charge ID that was returned from your previous request, and ATLPay will return the corresponding charge information. The same information is returned when creating or refunding the charge.

Example request

.. code-block:: bash
    
    curl -X GET \
    	https://api.atlpay.com/v2/charges/C2018062972567 \
    	-H 'X-Api-Key: YOUR_API_KEY'
	  
Example response

.. code-block:: json

   {
        "type": "charge",
        "id": "C2018062972567",
        "currency": "GBP",
        "amount": 500,
        "fees": null,
        "description": "Order Desc",
        "txn_reference": "Your Order Number",
        "threedSecure": {
            "redirect": "https://payments.atlpay.com/3d-secure/C2018062972567?paRes=MjU3NDQyM2YtNmFmMy00OTEzLWI1YTUtZmZjY2EzODg4ZGYx",
            "returnUrl": "https://www.your-3d-return-url.com/",
            "status": "PENDING"
        },
        "status": "PENDING",
        "token": {
            "type": "token",
            "id": "776e05d1-cda5-4f78-8d36-761a23b8a30a",
            "card": {
                "fundingType": "DEBIT",
                "country": "GB",
                "last4Digits": "3005",
                "type": "VISA_DEBIT",
                "brand": "VISA",
                "bank": "BANK OF IRELAND (UK) PLC",
                "name": "Richard AMOAH",
                "authorization": "REQUIRED",
                "addressLine1": null,
                "addressLine2": null,
                "addressCity": null,
                "addressState": null,
                "addressZip": null,
                "addressCountry": null
            },
            "created": 1530260981
        },
        "created": 1530266033,
        "mode": "live"
    }
	
Capture a charge
----------------

Capture the payment of an existing, uncaptured, charge. Uncaptured payments expire exactly seven days after they are created. If they are not captured by that point in time, they will be marked as refunded and will no longer be capturable.

**Returns**

Returns the charge object. Capturing a charge will always succeed, unless the charge is already refunded, expired, captured in which case this method will return an error.

Example request

.. code-block:: bash
    
    curl -X POST \
    	https://api.atlpay.com/v2/charges/capture/C2018062972567 \
    	-H 'X-Api-Key: YOUR_API_KEY'
	  
Example response

.. code-block:: json

   {
       "type": "charge",
       "id": "C2018062972567",
       "currency": "GBP",
       "amount": 500,
       "fees": 33,
       "description": "Order Desc",
       "txn_reference": "Your Order Number",
       "threedSecure": {
           "redirect": "https://payments.atlpay.com/3d-secure/C2018062972567?paRes=MjU3NDQyM2YtNmFmMy00OTEzLWI1YTUtZmZjY2EzODg4ZGYx",
           "returnUrl": "https://www.your-3d-return-url.com/",
           "status": "CAPTURED"
       },
       "status": "CHARGE_SUCCESS",
       "token": {
           "type": "token",
           "id": "776e05d1-cda5-4f78-8d36-761a23b8a30a",
           "card": {
               "fundingType": "DEBIT",
               "country": "GB",
               "last4Digits": "3005",
               "type": "VISA_DEBIT",
               "brand": "VISA",
               "bank": "BANK OF IRELAND (UK) PLC",
               "name": "Richard AMOAH",
               "authorization": "REQUIRED",
               "addressLine1": null,
               "addressLine2": null,
               "addressCity": null,
               "addressState": null,
               "addressZip": null,
               "addressCountry": null
           },
           "created": 1530260981
       },
       "created": 1530269496,
       "mode": "live"
   }