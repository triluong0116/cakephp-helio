<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HomeStaysTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HomeStaysTable Test Case
 */
class HomeStaysTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HomeStaysTable
     */
    public $HomeStays;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.home_stays',
        'app.locations',
        'app.price_home_stays',
        'app.categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HomeStays') ? [] : ['className' => HomeStaysTable::class];
        $this->HomeStays = TableRegistry::getTableLocator()->get('HomeStays', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HomeStays);

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
