<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookingLandtourAccessoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookingLandtourAccessoriesTable Test Case
 */
class BookingLandtourAccessoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BookingLandtourAccessoriesTable
     */
    public $BookingLandtourAccessories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.booking_landtour_accessories',
        'app.bookings',
        'app.land_tour_accessories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BookingLandtourAccessories') ? [] : ['className' => BookingLandtourAccessoriesTable::class];
        $this->BookingLandtourAccessories = TableRegistry::getTableLocator()->get('BookingLandtourAccessories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookingLandtourAccessories);

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
