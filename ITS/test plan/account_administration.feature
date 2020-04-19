Feature: Customer account administration on E-commerce platform

	Scenario: Register new account
		Given I am on the account registration window
		And I filled my personal details
		And I filled my address
		And I filled my password
		And privacy policy checkbox has been ticked
		When I try to registrate my account
		Then I should see 'Your Account Has Been Created!'
		And I should see short message about successful registration

	Scenario: Logout from customer account
		Given I am on the account detail page
		When I try to logout from my account
		Then I should see 'Account Logout' 

	Scenario: Login to customer account
		Given I am on the site
		But I am not logged yet
		When I try to login
		And My credentials are filled
		Then I should see my personal account frontpage

	Scenario: Edit customer information
		Given I am on my personal account frontpage
		When I try to edit my account
		And my personal details are changed
		Then I should see 'Success: Your account has been successfully updated.'
		And I am redirected to my personal account frontpage

	Scenario: Change account password
		Given I am on my personal account frontpage
		When I try to change my password
		And my new password is filled
		Then I should see 'Success: Your password has been successfully updated.'
		And I am redirected to my personal account frontpage