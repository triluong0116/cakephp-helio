<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FavouritesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FavouritesTable Test Case
 */
class FavouritesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FavouritesTable
     */
    public $Favourites;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.favourites',
        'app.objects'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Favourites') ? [] : ['className' => FavouritesTable::class];
        $this->Favourites = TableRegistry::getTableLocator()->get('Favourites', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Favourites);

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
