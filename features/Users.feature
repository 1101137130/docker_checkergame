Feature: testing Users
    When finished the Checkers Game
    I want to do some test on it

Scenario: register with wrong email formation
    Given I am on "/register"
    Then I should see "Register"
    And I fill in "User" for "username"
    And I fill in "example@com" for "email"
    And I fill in "password" for "password"
    And I fill in "password" for "password_confirmation"
    And I press "Register"
    Then I should see "The email field should be like emample@examel33.com"

Scenario: register with too many username characters formation
    Given I am on "/register"
    When I fill in "UserUserUserUserUserUser" for "username"
    And I fill in "example@examel33.com" for "email"
    And I fill in "password" for "password"
    And I fill in "password" for "password_confirmation"
    And I press "Register"
    Then I should see "The username field should be shorter than 20 characters."

Scenario: register with different password insert 
    Given I am on "/register"
    Then I should see "Register"
    And I fill in "User" for "username"
    And I fill in "example@123.com" for "email"
    And I fill in "passworddd" for "password"
    And I fill in "password" for "password_confirmation"
    And I press "Register"
    Then I should see "The password field should be the same."

Scenario: register
    Given I am on "/register"
    Then I should see "Register"
    And I fill in "User" for "username"
    And I fill in "example@em.com" for "email"
    And I fill in "password" for "password"
    And I fill in "password" for "password_confirmation"
    And I press "Register"
    Then I should be on "/home"

Scenario: logout
    Given I am on "/home"
    Then I press "Logout"
    Then I should be on "/"

Scenario: Login fail. Wrong password
    Given I am on "/login"
    And I fill in "root" for "username"
    And I fill in "1234567" for "password"
    When I press "Login"
    Then I should see "auth.failed"

Scenario: Login
    Given I am on "/"
    And I should see "Login"
    And I follow "Login"
    And I should be on "/login"
    And I fill in "root" for "username"
    And I fill in "123456" for "password"
    When I press "Login"
    Then I should be on "/home"

Scenario: 該測試結束 將資料清除
    And I rollback all testing data

