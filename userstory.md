Magento 2 Assignment

Note:- Create a simple Google Doc where you first write a clear given user story. Then, implement the feature described in the user story and ensure it works as expected. Take a screenshot of the working output, as well as a screenshot showing that the GrumPHP check has passed successfully with no issues. Add both screenshots below the user story in the same Google Doc. Finally, upload the completed document to the training drive folder that has been shared with you. Finally, copy the Google Drive link of the completed document and paste it in the text field below for submission.

Note:- Create a simple Google Doc where you first write a clear given user story. Then, implement the feature described in the user story and ensure it works as expected. Take a screenshot of the working output, as well as a screenshot showing that the GrumPHP check has passed successfully with no issues. Add both screenshots below the user story in the same Google Doc. Finally, upload the completed document to the training drive folder that has been shared with you. Finally, copy the Google Drive link of the completed document and paste it in the text field below for submission.
Note: Assignment Link: https://classroom.github.com/a/6CafnNkZ
The URL on your local system should contain <yourname>.
Vendor:  <Yourname>
User Story #1 : Change the preference for \Magento\Catalog\Api\Data\CategoryInterface to an interface declared in Mod1. Create a file “<Yourname>/Test” and inject the CategoryInterface as a dependency in the constructor along with two other constructor arguments ( an array and a string). Add a method displayParams() and echo the array and the string arguments passed to the constructor. Create a controller and inject the <Yourname>/Test class. Call the displayParams() method in the execute method of the controller.
User Story #1a : Instead of just displaying the parameters in the displayParams() method, modify the logic to serialize the array argument into JSON format and log it to a custom log file. Also, implement a mechanism to validate that the injected CategoryInterface is an instance of a custom interface declared in Mod1, and throw an exception if not.
User Story #2 : Use a plugin to append “ On Sale!” to all products whose price is less than $60. Change the copyright text and the welcome message to your own custom text using a plugin. Append “Hummingbird” to the beginning of the name of each breadcrumb.
User Story #2a : Extend the plugin to append “WholeSale !!” on products whose price less than 20, append “Super Sale!!” whose price greater than 20 but less than 50 and append “Premium !!” whose price greater than 50 and also dynamically calculate and display the price discounted by 15% for each product on Super Sale. Additionally, implement a plugin that adds a logo of disount on the frontend for products On Sale.
User Story #3 : Create an observer for an event that fires on the product view page and log the name of the product in system.log file. Everytime a page is loaded, log its html in system.log file.
User Story #3a : Create an observer that logs the name of the product along with additional details like SKU, price, Quantity per Source and Salable Quantity.
User Story #4 : Using an observer when a page loads, log the list of available routers in the system.log file. Create a router that redirects urls like “/FrontnameControllernameAction” to frontname/controllername/action. Redirect a “not found” page to the Contact Us page. Create an URL rewrite for the Contact Us page so that “/contactuspage.html” redirects to the Contact Us page.
User Story #5 : Create a frontend controller that displays “Hello World” text. Using a plugin, modify the contents of the catalog product view page. Create an admin controller that is only accessible if the “access” GET parameter is set to True. Redirect the above frontend controller to a specific product page.
User Story #6 : Create a block that implements _toHtml() and _afterToHtml() and render it in a new controller. Use a plugin for Magento\Catalog\Block\Product\View\Description to set custom description “sample description” that is rendered from the template for all products. 
User Story #7 : Create a block called Message that is placed in the product.info.main container on the product view page. Render the message from the template file declared for the above block in the layout file. Place the block before the product.info.price container. Render additional message from the block’s _afterTohtml() method. Create a template with the text “This is displayed on all pages” and have it display on all pages using the default.xml file. Create a new controller with 2 page layout and alter the catalog_product_view from 1 column to 3 columns layout. 
User Story #8 : Create a table employee_table with columns – employee_id, first_name, last_name, email_id.  Create a model, resource model and a collection for the above table. Create a frontend controller that renders two template blocks. The save block should render a form in its template and the table block must render a table on the frontend with the data from the employee_table. The controller must save the data to the employee table using the resource model every time the form is submitted.
User Story #8a : In the table employee_table add 2 more columns address and phone number, with validation in each column like employee_id should like EMP1, EMP2…and so on and it should be autoincremented when a new employee is added, first_name and last_name should have less than 30 alphabets with no numbers, proper validation for email_id, address should be greater than 30 characters and phone number should be 10 digits only. Implement a sorting feature for the table block that allows users to sort the employee data by different columns. Implement a delete button on each row too so that any row can be deleted.
User Story #9 : Create custom configuration with two fields in the Mod9 tab under the general section. Add a dropdown yes/no with label “Enable” and a text field with label “text to display”. Create a frontend controller that displays the text stored in the above configuration only if the enable dropdown has a value of yes. Create a custom menu with a submenu item that links to the above controller.
User story #10 : Install magento 2.4.3 on your local machine. Install stripe payment gateway version 3.0.0 (https://marketplace.magento.com/tnw-module-stripe.html).
Configure stripe payment and enable google pay. You need to create a stripe payment gateway test account and configure accordingly (Refer – https://dashboard.stripe.com/login). Verify google pay, it will give you an error ‘Could not place order: Please specify a shipping method’.
Debug and solve this issue on your local machine
Github repository for stripe : https://github.com/stripe/stripe-magento2-releases
User Story #11 :
Akeneo setup:
Download and setup akeneo PIM on your local machine (https://www.akeneo.com/download-akeneo-pim-community-edition/). Create connections for configuring Magento2 connector.
Refer doc : https://docs.akeneo.com/4.0/install_pim/manual/installation_ce.html
Magento setup
Install akeneo connector (https://github.com/akeneo/magento2-connector-community) on your magento instance and configure it using akeneo connections.
Refer doc : https://help.akeneo.com/magento2-connector/index.html
Task: Create some Categories, families, attributes, attribute options and products in akeneo. Run manual import from magento. All the categories, families, attributes and products should be reflected in magento2.
User Story #12 :
Scope of work:
AutoComplete & Address Verification (Validation and Autocomplete): https://www.smarty.com/products/us-address-verification & https://www.smarty.com/products/us-address-autocomplete
For RDI
For shipping address – Add a field (not visible to customer) – https://devdocs.magento.com/guides/v2.4/howdoi/checkout/checkout_new_field.html
When a new address is created & submitted, the field (RDI) needs to be updated as “Residential” or “commercial” – The value will be received from the RDI field of meta data from the call made to the API.
Why is this needed? This will be needed for the shipping costs (it is charged higher by the shipping provider for residential area vs commercial area).
There is an existing extension which is not working with Magento2: https://github.com/thinkpyxl/magento2-Pyxl_SmartyStreets
Install and configure the module (Install required dependency libraries – php-sdk, via composer)
For API keys refer to – https://www.smarty.com/.
Create a free account with your personal email address (For eg: abc@gmail.com)
For PHP-SDK examples refer to – https://www.smarty.com/docs/sdk/php
Expected output: 
1) Fix the extension code
2) Screenshots of the validation fail & validation success scenarios
3) Test data used for testing
User Story #13 :
Setting up the Warden, setup the Magento 2 instance by following the instructions given in this 
https://www.youtube.com/watch?v=aircAruvnKk&list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi 

Warden Setup Assignment:
Create a section in the assignment document – name of the section should be “Warden Setup”.
Add the screenshot of the Magento 2 instance setup with “Luma” or “Blank” theme homepage on local instance with Warden.
Note: In call screenshots of the course your domain name should be visible. The domain name should be .test For e.g: If your first name is abc, it should be abc.test
User Story #14 : Implement a custom event that triggers when the quantity of a product falls below a specified threshold. Attach an observer to this event to send a notification to the store owner or warehouse manager, providing details about the product and the low inventory situation.
User Story #15 : Implement a custom event named custom_order_placement, this event should be triggered specifically after an order is successfully placed by a customer belonging to a designated customer group. The primary goal is to store the total sales amount associated with the customer group in the database. This stored data should be retrievable for future use.
User Story #16 : Create a custom configuration under Mod9 called as “Mod16” that has color picker label and an apply button which basically changes the color of entire page according to the color selected in configuration.
User Story #17 : Create a custom layered navigation that includes “rating filter” for the ease of customer to view products according to product ratings.
User Story #18 : Implement price adjustments on the product page, category page, cart page and checkout page. Add $1.79 to each product price to all of the products, the adjusted price should be reflected in product page, category page, cart page and checkout page.
User Story #19 :
Setup Magento v2.4.6.
Create a module that will display cross sell products in the minicart section based on the products added to cart.
Show only first two products from any item added to cart.
User Story #20 :
Set up a magento v2.4.6 instance.
Create a module that will display ‘Call For Availability’ button in place of ‘Add to Cart’ on PDP and PLP page based on the salable quantity.
If the salable quantity is zero, button should display ‘Call For Availability’.
Call for availability button should redirect the user to contact us page.
User Story #21 :
Present distinct product detail page (pdp) layouts based on whether customers arrive via the general product URL or an affiliated product URL.
The affiliated URL format is ‘https://domain.com/{product identifier}?affiliate=true’.
The general product URL excludes the ‘Reviews’ section, while the affiliated product URL includes the ‘Reviews’ section.
User Story #22:
Complete this 
https://www.youtube.com/watch?v=tIVDUe3RqY4&t=729s 

and perform the handson along with it, in this video there is custom section made in admin panel and throuh that pop ups are displayed in front end which are stored in database.
User Story #23:
Complete this 
https://www.youtube.com/watch?v=yr3eQcWCbTQ&t=2s 

 and perform the handson along with it, in this video there is custom checkout step added called as Contact step.
User Story #24: Using AMD (requirejs), console log the values stored in store configuration such as sales emails, payment methods, etc.
Note:
You need to pass data from module to AMD file
Use x-magento-init/data-mage-init
Links to refer:
https://developer.adobe.com/commerce/frontend-core/javascript/requirejs/
You can also refer to mage2tv requirejs section (https://www.mage2.tv/content/javascript/requirejs-fundamentals/)
User Story #25:
This user story require Magic360 extension. In this assignment you need to install the extension which is “Magic 360 for Magento” developed by MagicToolBox. Download the extension : https://www.magictoolbox.com/magic360/modules/magento/?v=79cba1185463 add it in your instance.
This extension allows you to show the 360 degree view of the product, now create the product using magic360. Download product 360 image from : https://www.webrotate360.com/products/webrotate-360-product-viewer.aspx
Actual Task
In the PDP page 
The product should revolve 360 degree on scroll
You can see the list of clickable images where the first image of magic360 displays the default image. You need to change this image to the product’s base first image.
Before:


After 



