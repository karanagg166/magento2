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