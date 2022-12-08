<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PriceHomeStaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PriceHomeStaysTable Test Case
 */
class PriceHomeStaysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PriceHomeStaysTable
     */
    public $PriceHomeStays;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.price_home_stays',
        'app.home_stays'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PriceHomeStays') ? [] : ['className' => PriceHomeStaysTable::class];
        $this->PriceHomeStays = TableRegistry::getTableLocator()->get('PriceHomeStays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PriceHomeStays);

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
