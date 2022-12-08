<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LandTourDrivesurchagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LandTourDrivesurchagesTable Test Case
 */
class LandTourDrivesurchagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\LandTourDrivesurchagesTable
     */
    public $LandTourDrivesurchages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.land_tour_drivesurchages',
        'app.land_tours'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('LandTourDrivesurchages') ? [] : ['className' => LandTourDrivesurchagesTable::class];
        $this->LandTourDrivesurchages = TableRegistry::getTableLocator()->get('LandTourDrivesurchages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LandTourDrivesurchages);

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
