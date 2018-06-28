Errors
======

ATLPay uses conventional HTTP response codes to indicate the success or failure of an API request. In general: Codes in the ``2xx`` range indicate success. Codes in the ``4xx`` range indicate an error that failed given the information provided (e.g., a required parameter was omitted, a charge failed, etc.). Codes in the ``5xx`` range indicate an error with ATLPay's servers (these are rare).

Some ``4xx`` errors that could be handled programmatically (e.g., a card is declined) include an error code that briefly explains the error reported.

Error Codes
----------------

.. csv-table::
   :header: "HTTP Code", "Description"
   :widths: 20, 80

   "200 - OK", "Everything worked as expected."
   "400 - Bad Request", "The request was unacceptable, often due to missing a required parameter."
   "401 - Unauthorized", "No valid API key provided."
   "402 - Request Failed", "The parameters were valid but the request failed."
   "403 - Forbidden", "You cannot access this resource."
   "404 - Not Found", "The requested resource doesn't exist."
   "500, 502, 503, 504 - Server Errors", "Something went wrong on ATLPay's end. (These are rare.)"

Error Attributes
----------------

.. csv-table::
   :header: "Attribute", "Description"
   :widths: 20, 80

   "type ``string``", "The type of error returned"
   "message ``string``", "A human-readable message providing more details about the error."
   "error.code ``string``", "For some errors that could be handled programmatically, a short string indicating the error code reported."
   "error.message ``string``", "A human-readable message providing more details about the error."
   "error.parameter ``string``", "if the error is parameter-specific, the parameter related to the error. For example, you can use this to display a message near the correct form field."