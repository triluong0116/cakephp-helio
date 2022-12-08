<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VinhmsbookingTransportationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VinhmsbookingTransportationsTable Test Case
 */
class VinhmsbookingTransportationsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\VinhmsbookingTransportationsTable
     */
    public $VinhmsbookingTransportations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vinhmsbooking_transportations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('VinhmsbookingTransportations') ? [] : ['className' => VinhmsbookingTransportationsTable::class];
        $this->VinhmsbookingTransportations = TableRegistry::getTableLocator()->get('VinhmsbookingTransportations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VinhmsbookingTransportations);

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
}
