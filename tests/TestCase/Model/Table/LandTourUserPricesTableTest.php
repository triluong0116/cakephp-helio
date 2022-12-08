<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LandTourUserPricesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LandTourUserPricesTable Test Case
 */
class LandTourUserPricesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LandTourUserPricesTable
     */
    public $LandTourUserPrices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.land_tour_user_prices',
        'app.land_tours',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LandTourUserPrices') ? [] : ['className' => LandTourUserPricesTable::class];
        $this->LandTourUserPrices = TableRegistry::getTableLocator()->get('LandTourUserPrices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LandTourUserPrices);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
