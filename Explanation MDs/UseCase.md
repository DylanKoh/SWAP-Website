7.1.	Use Case
Login
•	Description: Customer/Provider who has an account uses them to log into the website
•	Actors Involved: Customer/Provider & Authentication
•	Triggers: After User has clicked on the "Login" button
•	Pre-conditions: Requires Username and Password (and Google 2FA Code if 2FA is set up)
•	Basic-Flows: 
o	Customer/Provider enter their respective login page.
o	Customer/Provider enter their username and password.
o	Customer/Provider click on the "Login" button; they will successfully login if credentials are correct. 
•	Alternate Flows:
o	If 2FA is included, it will be prompted for 2FA code on Google Authenticator application after correct credentials are keyed in the Login page. For 2FA, code must be keyed in, correctly, for User to log in successfully.
•	Post-conditions: User logs in into the home page if credentials and 2FA code(if applicable) is correct.

Registration
•	Description: The public registers as a customer/provider
•	Actors Involved: Public, Authentication
•	Triggers: User clicks "Register" button
•	Pre-conditions: Have a valid email on-hand
•	Basic-Flows: 
o	User has to enter an email, his/her full name, a username and a password, which will be used to login in the future
o	Once registered successfully, users will be able to use the username and password to login
•	Alternate Flows: 
o	Users can choose to activate 2FA authentication during the registration process
o	When clicking on "Register", User will be prompted to set up their Google Authenticator and verify the code.
o	Once verified, User now have a new account.
•	Post-conditions: User account is created if all fields are keyed in and username and email does not match any existing records of usernames in Database.

Settings
•	Description: Customers/Providers are able to change anything about their Account details.
•	Actors Involved: Customers/Providers
•	Triggers: User clicks "Settings" button
•	Pre-conditions: Must be logged in to own account
•	Basic-Flows: 
o	User clicks on "Settings" button and is redirected to their own settings page.
o	User is able to change any details, including enabling or disabling 2FA, as long as new username does not clash with any existing username in the Database
o	User clicks on save changes to update their account
•	Alternate Flows: -
•	Post-conditions: Account is successfully updated only if new username or email does not match any existing records of username or email in the Database.

Viewing 
•	Description: Customers viewing service posted on the website
•	Actors Involved: Customer
•	Triggers: After the customer access the "Explore" store page
•	Pre-conditions: Users will need to sign up and login before they can view services.
•	Basic-Flows: 
o	Customer finds an appropriate service that they would like to obtain
o	Click on the service, which will redirect them to the service webpage
•	Alternate Flows:
o	From main webpage, view Top service providers
o	View services that they provide.
•	Post-conditions: -

Communicate
•	Description: Customers are able to communicate with the provider about the service offered
•	Actors Involved: Customer & Provider
•	Triggers: After the Customer sees a service he/she likes, he/she can click the button to start a conversation with the provider of the service
•	Pre-conditions: Providers must have registered an account and logged in before being able to start a conversation and communicate with a provider
•	Basic-Flows:
o	Customer clicks "Message" button to start conversation with the provider of the service they would like to acquire
•	Alternate Flows: -
•	Post-conditions: -

Post Offer 
•	Description: Providers are able to post a service that they wish to offer to  customers
•	Actors Involved: Providers
•	Triggers: After providers have accessed the "Explore" page, they can click on the "Post a service" button.
•	Pre-conditions: Providers on our website have to be logged in before they can post their service offers.
•	Basic-Flows: 
o	From the main page, they will have to Navigate to the "Explore" store page.
o	Click on the "Post a service" button.
o	Fill up the form with the relavent information that they would like to display.
•	Alternate Flows:
o	Navigate to their profile page
o	Choose on "Post a service" and fill up the information
•	Post-conditions:
o	Information relating to the service post, such as "Price", "Title", "Description" have to be filled up before it can be posted.

Accepting Offer 
•	Description: Providers can choose to accept the offer given by the Customer
•	Actors Involved: Provider
•	Triggers: After Customer has provided an offer to the provider
•	Pre-conditions: Provider must receive an offer from the Customer
•	Basic-Flows: 
o	Provider logins into the website
o	Provider will receive an offer from Customer where they are able to choose whether to accept or decline the offer
•	Alternate Flows: -
•	Post-conditions: 
o	Status of offer, whether accepted or declined by provider

Payment Method
•	Description: Customer can choose how they want to make payment for the service
•	Actors Involved: Customer
•	Triggers: After provider has chosen to accept offer given by Customer
•	Pre-conditions: Offer given to provider must be accepted
•	Basic-Flows:
o	After the provider has accepted the offer, the Customer will be redirected to a payment screen
o	The Customer will then input their payment information
•	Alternate Flows: 
o	After the first payment, customers can choose to save their payment information and any subsequent payments can be done with that saved information
•	Post-conditions: Payment information
o	Card number
o	Name on credit card
o	Card expiry date
o	CVV [Used for verification if details are saved in Database]

Sending Out Completed Product
•	Description: After payment, the provider can send out the completed product
•	Actors Involved: Provider
•	Triggers: After provided has completed the product
•	Pre-conditions: Customer has to have paid the provider
•	Basic-Flows:
o	After the provider has completed the product, it will be sent out to the Customer
•	Alternate Flows: -
•	Post-conditions: -

Posting of Reviews
•	Description: Customers can choose to leave reviews for one another
•	Actors Involved:Customer
•	Triggers: After they have completed a successful deal.
•	Pre-conditions: 
o	Customer must have sent payment to the provider
o	Provider must have sent the completed product to the Customer
•	Basic-Flows: 
o	After the deal has been completed successfully
o	The Customer will be given the option to leave a review
•	Alternate Flows: 
o	Customer can choose to edit the review in the future if required
•	Post-conditions: 
o	Review information like rating and description of the deal needs to be filled up before they can post the review
