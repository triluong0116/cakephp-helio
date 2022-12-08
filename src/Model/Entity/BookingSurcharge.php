<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BookingSurcharge Entity
 *
 * @property int $id
 * @property int $booking_id
 * @property int $surcharge_type
 * @property string $name
 * @property int $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Booking $booking
 */
class BookingSurcharge extends Entity
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
