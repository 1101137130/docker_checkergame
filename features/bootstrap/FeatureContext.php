<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laracasts\Behat\Context\DatabaseTransactions;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    use AuthenticatesUsers;
    protected static $CSRFtoken = '';
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given show Page
     */
    public function showPage()
    {
        var_dump($this->getSession()->getPage()->getHtml());
    }

    /**
     * @When wait for JS
     */
    public function waitForJs()
    {
        $time = 2000; // time should be in milliseconds
        $this->getSession()->wait($time, 'typeof jQuery != "undefined" && 0 === jQuery.active');
    }

    /**
     * @Given I click dropdown
     */
    public function iClickOnDropdown2()
    {
        $a = $this->getSession()->getPage()->find('css', 'a[data-toggle="dropdown"]');
        $a->click();
    }

    /**
     * @Then I click on :arg1
     */
    public function iClickOn2($arg1)
    {
        $a = $this->getSession()->getPage()->find('css', 'a[id='.$arg1.']');
        $a->click();
    }

    /**
     * @Then I should see :arg1 in popup
     */
    public function iShouldSeeInPopup($message)
    {
        return $message == $this->getSession()->getDriver()->getWebDriverSession()->getAlert_text();
    }

    /**
     * @When I cancel the popup
     */
    public function iCancelThePopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->dismiss_alert();
    }

    /**
     * @Then I confirm the popup
     */
    public function iConfirmThePopup()
    {
        $this->getSession()->getDriver()->getWebDriverSession()->accept_alert();
    }
    /**
     * @Given I rollback all testing data
     */
    public function iRollbackAllTestingData()
    {
        shell_exec("php artisan migrate:refresh --seed");
    }

    /**
     * @When /^I check the "([^"]*)" radio button$/
     */
    public function iCheckTheRadioButton($labelText)
    {
        $page = $this->getSession()->getPage();
        $radioButton = $page->find('named', ['radio', $labelText]);
        if ($radioButton) {
            $select = $radioButton->getAttribute('name');
            $option = $radioButton->getAttribute('value');
            $page->selectFieldOption($select, $option);
            return;
        }

        throw new \Exception("Radio button with label {$labelText} not found");
    }

    /**
     * @Then I should confirm :table has new data with :value for :column
     */
    public function iShouldConfirmHasNewDataWithFor($table, $value, $column)
    {
        
    }
}
