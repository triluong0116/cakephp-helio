<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BookingsUser Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $booking_id
 * @property int $revenue
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Booking $booking
 */
class BookingsUser extends Entity
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
        'id' => true,
        'user_id' => true,
        'booking_id' => true,
        'revenue' => true,
        'user' => true,
        'booking' => true
    ];
}
