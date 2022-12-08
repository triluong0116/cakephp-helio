<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Channelrateplane Entity
 *
 * @property int $id
 * @property int $channel_room_id
 * @property string $rateplan_code
 * @property string $name
 * @property int|null $guest
 * @property int|null $adult
 * @property int|null $child
 * @property int|null $maxguest
 * @property int|null $extraguest
 * @property int $sale_revenue_type
 * @property int $sale_revenue
 * @property int|null $meals
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Channelroom $channel_room
 */
class Channelrateplane extends Entity
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
        'channel_room_id' => true,
        'rateplan_code' => true,
        'name' => true,
        'guest' => true,
        'adult' => true,
        'child' => true,
        'maxguest' => true,
        'extraguest' => true,
        'sale_revenue_type' => true,
        'sale_revenue' => true,
        'meals' => true,
        'description' => true,
        'created' => true,
        'modified' => true,
        'channel_room' => true
    ];
}
