<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BookingLandtoursFixture
 *
 */
class BookingLandtoursFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'booking_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'landtour_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'start_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'num_adult' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'num_children' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'amount' => ['type' => 'tinyinteger', 'length' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'child_ages' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
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
                'booking_id' => 1,
                'landtour_id' => 1,
                'start_date' => '2019-11-29',
                'num_adult' => 1,
                'num_children' => 1,
                'amount' => 1,
                'child_ages' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'created' => '2019-11-29 15:38:44',
                'modified' => '2019-11-29 15:38:44'
            ],
        ];
        parent::init();
    }
}
