Feature: 新增品項流程

Scenario: 測試新增品項者 必須要有品項管理的權限 測試新增帳戶無權限訪問品項時
    Given I am on "/register"
    When I fill in the following:
        | username | User |
        | email    | example@em.com |
        | password | password |
        | password_confirmation | password |
    And I press "Register"
    Then I should be on "/home"
    Then I am on "/item"
    Then I should see "您不是管理員"
    Then I should be on "/home"
    Then I press "Logout"

Scenario: 測試品項管理者 可以訪問"/item"
    Given I am on "/login"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I should see "項目管理"
    And I follow "itemManage"
    Then I should be on "/item"
    Then I press "Logout"

@javascript
Scenario: 測試新增品項 全都3以上 並設置規則 特殊牌型 345 345 345
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    And I fill in the following:
        | itemname | 全都3以上 |
        | rate     | 4 |
        | limit_amount | 5000 |
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
    Then I should see "賠率：4"
    Then I should see "限制下注金額：5000"
    Then I should confirm "itemrules" has new data with "345" for "special_one"
    Then I should confirm "itemrules" has new data with "345" for "special_two"
    Then I should confirm "itemrules" has new data with "345" for "special_three"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試新增品項 兩局以上平手 規則為單局比較：兩局相同者獲勝
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    And I fill in the following:
        | itemname | 兩局以上平手 |
        | rate     | 3.5 |
        | limit_amount | 1000000 |
    And I check the "single" radio button
    And I check "winRequire11"
    And I check "winRequire22"
    And I check "winRequire33"
    And I check "winRequire44"
    And I check "winRequire55"
    When I press "確認"
    And wait for JS
    Then I should see "兩局以上平手"
    Then I should see "賠率：3.5"
    Then I should see "限制下注金額：1000000"
    Then I should confirm "itemrules" has new data with "1" for "one"
    Then I should confirm "itemrules" has new data with "2" for "two"
    Then I should confirm "itemrules" has new data with "3" for "three"
    Then I should confirm "itemrules" has new data with "4" for "four"
    Then I should confirm "itemrules" has new data with "5" for "five"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試新增品項 相同 規則為現有規則 三局都相同 獲勝
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    And I fill in the following:
        | itemname | 相同 |
        | rate     | 6 |
        | limit_amount | 6666 |
    And I check the "extend" radio button
    Then I select the option "selectFirst" with "4" value
    And I select the option "selectSecond" with "4" value
    And I select the option "selectThird" with "4" value
    When I press "確認"
    And wait for JS
    Then I should see "相同"
    Then I should see "賠率：6"
    Then I should see "限制下注金額：6666"
    Then I should confirm "itemrules" has new data with "4,4,4" for "extend_exist_rule"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試新增品項 總數等於10 規則為總數 等於10 獲勝
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    And I fill in the following:
        | itemname | 總數等於10 |
        | rate     | 10 |
        | limit_amount | 1000 |
    And I check the "totalRadio" radio button
    Then I select the option "operator" with "0" value
    And I fill in "10" for "total"
    When I press "確認"
    And wait for JS
    Then I should see "總數等於10"
    Then I should see "賠率：10"
    Then I should see "限制下注金額：1000"
    Then I should confirm "itemrules" has new data with "10" for "total"
    Then I should confirm "itemrules" has new data with "0" for "operator"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試刪除已存在資料 顯示 無法刪除，因為注單有此資料，將此項目改為未開啟 
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    Then wait for JS
    When I press "delete1"
    Then wait for JS
    Then I should see "無法刪除，因為注單有此資料，將此項目改為未開啟"
    And I should see "重新啟用"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試將已關閉的項目 重新啟用 
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    Then wait for JS
    When I press "reActive1"
    Then wait for JS
    Then I should see "修改成功"
    And I click dropdown
    Then I follow "Logout"
    
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
    When I fill in the following:
        | itemname1 | 修改 |
        | rateid1 | 3.000|
    Then I press "store1"
    Then wait for JS
    Then I should see "修改"
    And I click dropdown
    Then I follow "Logout"

@javascript
Scenario: 測試一次性修改多筆資料
    Given I am on "/item"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    Then I go to "/item"
    Then wait for JS
    When I click on "itemname1" for edit
    And I click on "itemname2" for edit
    And I click on "itemname3" for edit
    And I click on "itemname4" for edit
    Then wait for JS
    When I fill in the following:
        | itemname1 | 修 |
        | rateid1 | 4.000|
    When I fill in the following:
        | itemname2 | 改 |
        | rateid2 | 5.000|
    When I fill in the following:
        | itemname3 | 資 |
        | rateid3 | 6.000|
    When I fill in the following:
        | itemname4 | 料 |
        | rateid4 | 7.000|
    Then I press "allEditBtn"
    Then wait for JS
    Then I should see "修"
    Then I should see "改"
    Then I should see "資"
    Then I should see "料"
    Then I should see "7.0000"
    Then I should see "6.0000"
    Then I should see "5.0000"
    Then I should see "4.0000"
    And I should see "修改完成"
    And I click dropdown
    Then I follow "Logout"
    And I rollback all testing data

@javascript
Scenario: 測試進入品項修改記錄 並測試搜尋功能
    Given I am on "/login"
    When I fill in the following:
        | username | root |
        | password | 123456 |
    And I press "Login"
    And I go to "/item"
    Then I follow "查看紀錄"
    Then I should see "範圍搜索"
    When I press "search"
    And wait for JS
    Then I should see a datatable contains the following
        | 單號 | 修改者 | 修改項目 | 修改賠率 |
        | 1 | root | 贏 | 2 |
        | 2 | root | 輸 | 2 |
        | 3 | root | 總數大於9 | 3 |
        | 4 | root | 平 | 4 |
        | 5 | root | 總數小於9 | 3 |
        | 6 | root | 特殊-123 | 5 |
        | 7 | root | 輸贏平 | 5 |

@javascript
Scenario: 測試沒有權限不能進入品項修改記錄頁面
    Given I am on "/register"
    When I fill in the following:
        | username | User |
        | email    | example@em.com |
        | password | password |
        | password_confirmation | password |
    And I press "Register"
    And I go to "/Raterecord"
    Then I should be on "/home"

Scenario: 該測試結束 將資料清除
    And I rollback all testing data
