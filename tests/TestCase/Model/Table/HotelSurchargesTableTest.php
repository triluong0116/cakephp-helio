<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HotelSurchargesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HotelSurchargesTable Test Case
 */
class HotelSurchargesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HotelSurchargesTable
     */
    public $HotelSurcharges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.hotel_surcharges',
        'app.hotels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('HotelSurcharges') ? [] : ['className' => HotelSurchargesTable::class];
        $this->HotelSurcharges = TableRegistry::getTableLocator()->get('HotelSurcharges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HotelSurcharges);

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
