<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RoomsCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RoomsCategoriesTable Test Case
 */
class RoomsCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RoomsCategoriesTable
     */
    public $RoomsCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.rooms_categories',
        'app.rooms',
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
        $config = TableRegistry::getTableLocator()->exists('RoomsCategories') ? [] : ['className' => RoomsCategoriesTable::class];
        $this->RoomsCategories = TableRegistry::getTableLocator()->get('RoomsCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RoomsCategories);

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
