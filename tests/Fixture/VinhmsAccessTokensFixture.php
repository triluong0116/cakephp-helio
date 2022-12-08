<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * VinhmsAccessTokensFixture
 *
 */
class VinhmsAccessTokensFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'access_token' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8mb4_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'start_time' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'expire_time' => ['type' => 'timestamp', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'access_token' => 'Lorem ipsum dolor sit amet',
                'start_time' => 1616486169,
                'expire_time' => 1616486169
            ],
        ];
        parent::init();
    }
}
