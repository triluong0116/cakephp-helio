<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HotelSearch Entity
 *
 * @property int $id
 * @property string $NAME
 * @property string|null $location_name
 * @property int|null $room_id
 * @property int|null $price_id
 * @property string|null $room_name
 * @property \Cake\I18n\FrozenDate|null $single_day
 * @property string $WEEKDAY
 * @property string $weekend
 * @property string|null $day_name
 * @property int|null $price_day
 * @property int|null $price_day_app
 * @property int|null $price_type
 *
 * @property \App\Model\Entity\Room $room
 * @property \App\Model\Entity\Price $price
 */
class HotelSearch extends Entity
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
