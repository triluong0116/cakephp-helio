<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HomeStaysCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HomeStaysCategoriesTable Test Case
 */
class HomeStaysCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HomeStaysCategoriesTable
     */
    public $HomeStaysCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.home_stays_categories',
        'app.home_stays',
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
        $config = TableRegistry::getTableLocator()->exists('HomeStaysCategories') ? [] : ['className' => HomeStaysCategoriesTable::class];
        $this->HomeStaysCategories = TableRegistry::getTableLocator()->get('HomeStaysCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HomeStaysCategories);

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
