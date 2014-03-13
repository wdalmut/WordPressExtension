Feature: Login
    In order to login into my WP website
    As an user
    I need to know my credential

    Background:
        Given I have a vanilla wordpress installation

    Scenario: A valid user access to the platform
        When I am on "/wp-login.php"
        And I fill in "user_login" with "admin"
        And I fill in "pwd" with "test"
        And I press "Log In"
        Then I should be on "/wp-admin/"

