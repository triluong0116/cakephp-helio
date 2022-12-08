<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BookingRoom Entity
 *
 * @property int $id
 * @property int $booking_id
 * @property int $room_id
 * @property \Cake\I18n\FrozenDate $start_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $num_adult
 * @property int $num_children
 * @property int $num_room
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Booking $booking
 * @property \App\Model\Entity\Room $room
 */
class BookingRoom extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
