Feature: 測試 新增帳戶 儲值 下注 流程

Scenario: 測試沒有登入不能進入遊戲
    Given I am on "/"
    And I follow "Play"
    Then I should be on "/login"
    And I am on "/home"
    Then I should be on "/login"

Scenario: 測試新增帳號並儲值金額1000 應該要有1000 顯示在上方 
    Given I am on "/register"
    When I fill in "User" for "username"
    And I fill in "example@em.com" for "email"
    And I fill in "password" for "password"
    And I fill in "password" for "password_confirmation"
    And I press "Register"
    Then I should be on "/home"
    When I press "Store"
    Then I should be on "/amount"
    When I fill in "1000" for "amount"
    And I press "儲存"
    Then I should be on "/show"
    Then I should see "儲值成功"
    And I should see "您的金額還有："
    And I should see "1000"
    Then I press "Logout"

@javascript
Scenario: 測試下單會出現alert訊息 並且按下取消 會回到/show頁面
    Given I am on "/show"
    When I fill in "User" for "username"
    And I fill in "password" for "password"
    And I press "Login"
    Then I should be on "/show"
    And wait for JS
    Then I fill in "1000" for "bankerS1"
    And I click on "action"
    Then I should see "你確定嗎？" in popup
    Then I should see "項目：莊家" in popup
    Then I should see "金額：1000" in popup
    Then I should see "預估獲利：2000" in popup
    When I cancel the popup
    Then I should be on "/show"
    And wait for JS
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試下單 項目：莊家贏 金額：1000 出現alert訊息 並且按下確認 會顯示結果
    Given I am on "/show"
    When I fill in "User" for "username"
    And I fill in "password" for "password"
    And I press "Login"
    Then I should be on "/show"
    And wait for JS
    Then I fill in "1000" for "bankerS1"
    And I click on "action"
    Then I should see "你確定嗎？" in popup
    Then I confirm the popup
    And wait for JS
    And I should see "結果："
    And I should see "項目：贏 莊家"
    Then I should be on "/show"
    And wait for JS
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試金額不足時下單 應顯示存款不足
    Given I am on "/show"
    When I fill in "User" for "username"
    And I fill in "password" for "password"
    And I press "Login"
    Then I should be on "/show"
    And wait for JS
    Then I fill in "1000" for "playerS2"
    And I click on "action"
    Then I should see "項目：閒家輸 | 賠率為：2.0000 | 金額：1000," in popup
    Then I confirm the popup
    Then I should see "您的存款不足" in popup

Scenario: 該測試結束 將資料清除
    And I rollback all testing data
