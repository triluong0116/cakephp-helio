<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LandToursTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LandToursTable Test Case
 */
class LandToursTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LandToursTable
     */
    public $LandTours;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.land_tours',
        'app.users',
        'app.departures',
        'app.destinations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LandTours') ? [] : ['className' => LandToursTable::class];
        $this->LandTours = TableRegistry::getTableLocator()->get('LandTours', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LandTours);

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
