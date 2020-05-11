Feature: 新增品項流程

@javascript
Scenario: 按下項目名稱或陪率 可以對該項目進行編輯 修改名稱或賠率
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    Then wait for JS
    When I click on "itemname1" for edit
    Then wait for JS
    Then I should see "儲存"

Scenario: 該測試結束 將資料清除
    And I rollback all testing data