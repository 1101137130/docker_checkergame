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

Scenario: 測試建立管理者 若沒有資格建立管理者則畫面上不會出現 建立管理者
    Given I am on "/home"
    And I should not see "建立管理者"

Scenario: 測試建立管理者 若沒有資格 並透過網址進入的話會出現 您不是管理者！
    Given I am on "/registerManager"
    Then I should be on "/home"
    And I should see "您不能新增管理者"

Scenario: logout
    Given I am on "/home"
    Then I press "Logout"
    Then I should be on "/"

Scenario: Login fail. Wrong password
    Given I am on "/login"
    And I fill in "root" for "username"
    And I fill in "1234567" for "password"
    When I press "Login"
    Then I should see "Wrong username or password. Pleas try again"

Scenario: Login
    Given I am on "/"
    And I should see "Login"
    And I follow "Login"
    And I should be on "/login"
    And I fill in "root" for "username"
    And I fill in "123456" for "password"
    When I press "Login"
    Then I should be on "/home" 

Scenario: 測試建立管理者 建立管理者 並設定權限 可以檢視orders
    Given I am on "/home"
    And I should see "建立管理者"
    Then I follow "建立管理者"
    Then I should be on "/registerManager"
    When I fill in the following:
        | username | root1 |
        | email | root1@ee.com|
        | password | 123456 |
        | password_confirmation | 123456|
    And I check "view_orders"
    Then I press "Register"
    Then I should confirm "users" has new data with "1" for "view_orders"
    Then I should confirm "users" has new data with "root1" for "username"
    And I press "Logout"
    When I go to "/login"
    And I fill in "root1" for "username"
    And I fill in "123456" for "password"
    When I press "Login"
    Then I should see "查看所有注單"

@javascript
Scenario: 測試修改會員名稱username 
    Given I am on "/login"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    And I go to "/home"
    When I click on "username" for edit
    And wait for JS
    Then I fill in "rootroot" for "username"
    And I press "儲存"
    Then I should see "rootroot"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試修改會員名稱username Email皆不能重複
    Given I am on "/login"
    When I fill in the following:
        | username | root1 |
        | password | 123456 |
    And I press "Login"
    And I go to "/home"
    When I click on "username" for edit
    When I click on "email" for edit
    And wait for JS
    When I fill in the following:
        | username | root1 |
        | email | root@hhh.com |
    And I press "儲存"
    Then I should see "The username field is repeated."
    Then I should see "The email field is repeated."
    And I click dropdown
    Then I follow "Logout"

    

Scenario: 該測試結束 將資料清除
    And I rollback all testing data

