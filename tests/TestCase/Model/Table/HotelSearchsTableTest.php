<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HotelSearchsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HotelSearchsTable Test Case
 */
class HotelSearchsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HotelSearchsTable
     */
    public $HotelSearchs;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.hotel_searchs',
        'app.rooms',
        'app.prices'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HotelSearchs') ? [] : ['className' => HotelSearchsTable::class];
        $this->HotelSearchs = TableRegistry::getTableLocator()->get('HotelSearchs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HotelSearchs);

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
