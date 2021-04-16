Users Table

Users table will be the table that is populated by customers of this proposed website. It will contain all login information of the customers. 
Passwords will be, by default, hashed with SHA256 with two passes and prepended with salt_1 before first hash and appended with salt_2 after first hash. 
The field, googleSecret, will be populated be a randomly generated secret from a third-party library, PHPGansta/Google Authenticator, if 2FA is selected for that account. This field will help in implementation of 2FA via Google Authenticator application.


Providers Table

Providers table will be the table that is populated by providers of this proposed website. The function of fields and data type are similar with the Users table. 
Rationale of creating another table just for the Providers is to make exploiting and information disclosure to be harder and longer for the Adversary.


Sales Table

Sales table will consist of the credit card information, ONLY if customers want to store it with the website for quicker transaction in further orders. 
Sensitive information like card number and expiry date will be encrypted with a private key set by the Customer, which will be hashed twice, like the Users and Providers tables' passwords.


Services Table

Services table will consist of what services each provider can provide to the customers. It will house what the name of the service is, the price set by the provider, and the description of the service. 
It is tied to the provider via a foreign key which points to their ID in the Providers table.


Orders Table

Orders table will consist of orders made by Customers of the services of the Providers. It will contain statuses like whether it has been accepted by the Provider of the service, and whether it has been completed by the Provider. 
Comments can be inputted by Customer during order. Order date will be recorded to help for organisation of Orders for the Providers. Orders will also be linked via a Foreign Key to the Customer's ID. 


Reviews Table

Reviews table will consist of the Customers' reviews based on completed Orders of the Service Provider, that are bounded to the Customers. Customers can rate, and comment (if applicable) about the Order. 
These ratings from the reviews will be aggregated for the overall rating of the Services of the Providers. Reviews will also be used to show other Customers how that Service is.


Message Table
The message table is used for storing communication between the Customers and Providers. This table is designed in the perspective of the Customers, where if isSending==1, it means that the message is from the Customers themselves. 
If isReceiving==1, it means that the message is from the Providers. Messages will have date of creation, for ordering in the solution of the project.
