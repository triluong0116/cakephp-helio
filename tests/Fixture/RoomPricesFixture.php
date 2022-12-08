<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RoomPricesFixture
 *
 */
class RoomPricesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'room_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'room_day' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'price' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'available' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'type' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'id_room_id_price_available_type' => ['type' => 'index', 'columns' => ['id', 'room_id', 'price', 'available', 'type'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
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
                'room_id' => 1,
                'room_day' => '2019-10-24',
                'price' => 1,
                'available' => 1,
                'type' => 1,
                'created' => '2019-10-24 09:56:19',
                'modified' => '2019-10-24 09:56:19'
            ],
        ];
        parent::init();
    }
}
