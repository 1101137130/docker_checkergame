Feature: 新增品項流程


@Laracasts
Scenario: 測試新增品項者 必須要有品項管理的權限 測試新增帳戶無權限訪問品項時
    Given I am on "/register"
    When I fill in "User" for "username"
    And I fill in "example@em.com" for "email"
    And I fill in "password" for "password"
    And I fill in "password" for "password_confirmation"
    And I press "Register"
    Then I should be on "/home"
    Then I am on "/item"
    Then I should see "您不是管理員"
    Then I should be on "/home"
    Then I press "Logout"

@Laracasts
Scenario: 測試品項管理者 可以訪問"/item"
    Given I am on "/login"
    When I fill in "root" for "username"
    And I fill in "123456" for "password"
    And I press "Login"
    Then I should see "ITEMMANAGE"
    And I follow "itemManage"
    Then I should be on "/item"
@javascript
Scenario: 測試新增品項
    Given I am on "/item"
    When I fill in "root" for "username"
    And I fill in "123456" for "password"
    And I press "Login"
    Then I go to "/item"
    When I fill in "全都3以上" for "itemname"
    And I fill in "4" for "rate"
    And I fill in "5000" for "limit_amount"
    Then I check the "special" radio button
    Then I check "specialCards13"
    And I check "specialCards14"
    And I check "specialCards15"
    Then I check "specialCards23"
    And I check "specialCards24"
    And I check "specialCards25"
    Then I check "specialCards33"
    And I check "specialCards34"
    And I check "specialCards35"
    When I press "確認"
    And wait for JS
    Then I should see "全都3以上"
    Then I should confirm "itemrules" has new data with "345" for "special_one"
    Then I should confirm "itemrules" has new data with "345" for "special_two"
    Then I should confirm "itemrules" has new data with "345" for "special_three"
