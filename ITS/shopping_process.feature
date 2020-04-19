Feature: E-commerce site shopping process

	Background:
		Given a customer account
		And logged in as 'John Smith'

	Scenario: Customer buys item
		Given I am at frontpage of the site
		And I see group of random products at the bottom
		When I try to buy item
		And I click on the 'add to cart' button
		Then I should see 'Success: You have added item to your shopping cart!'
		And I should see added item in my shopping cart

	Example:
		1 item(s) - $123

	Scenario: Customer search product on the site
		Given I am on the frontpage of the site
		When I search product category through navmenu
		Then I should see products of this category
		But I shouldn't see products from another categories

	Scenario: Customer buys item at product page
		Given I am on the single product page 
		And I see product detail
		And I see product name
		And I see product description
		And I see product price
		When I change Qty to 2
		And I click on 'add to cart' button
		Then I should see 'Success: You have added item to your shopping cart!'
		And I should see added items in my shopping cart'

	Example:
		2 item(s) - $222

	Scenario: Customer remove item from the shopping cart
		Given I am on the shopping cart page
		And I see items in my shopping cart
		When I click on 'remove' button
		Then I should see ' Success: You have modified your shopping cart!'
		And I should see modified shopping cart without removed item

	Scenario: Customer update quantity of items at the shopping cart
		Given I am on the shopping cart page
		And I see item in my shopping cart
		When I change quantity to 2 
		And I click to 'update' button
		Then I should see modified shopping cart

	Scenario: Customer checkout items
		Given I am on the checkout window
		And my billing details has been added
		And my delivery details has been added
		And my delivery method has been chosen
		And my payment method has been chosen
		And terms&condition has been ticked
		When I confirm my checkout
		Then I should see 'Your order has been placed!'

	Scenario: Customer adds item to wish list
		Given I am at frontpage of the site
		And I see group of random products at the bottom
		When I try to add product to my wish list
		Then I should see 'Success: You have added product to your wish list!'
		And I should see product in my wish list

	Scenario: Customer views order history
		Given I am on my personal account window
		And I see all my orders
		When I click to 'View' button
		Then I should see my order details

	Scenario: Customer returns items
		Given I am on return webpage
		And my address is filled
		And return options are ticked
		When I try to return items
		Then I should see 'Product Returns'
		And I should see message about processing

	Scenario: Customer view returns history
		Given I am on my personal account window
		And I see all my returns
		When I try to view my return
		Then I should see my return detail

	Scenario: Customer views transactions
		Given I have alreade some transactions
		When I try to view my transactions
		Then I should see my transactions at transactions window

	Scenario: Customer subscribes newsletter
		Given I am at my personal account
		When I try to subscribe newsletter
		Then I should see ' Success: Your newsletter subscription has been successfully updated!'

	Scenario: Delete customer address
		Given I have only one address
		When I try to delete my address
		Then I should see 'Warning: You can not delete your default address!'

	Scenario: Add new customer address
		Given I have filled address
		When I try to create new address
		And I filled my address details
		Then I am redirected to my address book
		And I should see all my addresses

	Scenario: Customer sorts products
		Given I should see group of products in product category
		When I try to sort products by price low to high
		Then I should see products sorted by a price from low to high