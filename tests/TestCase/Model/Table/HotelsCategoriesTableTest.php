<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HotelsCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HotelsCategoriesTable Test Case
 */
class HotelsCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HotelsCategoriesTable
     */
    public $HotelsCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.hotels_categories',
        'app.hotels',
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
        $config = TableRegistry::getTableLocator()->exists('HotelsCategories') ? [] : ['className' => HotelsCategoriesTable::class];
        $this->HotelsCategories = TableRegistry::getTableLocator()->get('HotelsCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HotelsCategories);

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
