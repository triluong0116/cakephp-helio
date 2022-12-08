<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChannelbookingRoom Entity
 *
 * @property int $id
 * @property int $room_index
 * @property int $channelbooking_id
 * @property int $channelroom_id
 * @property string $channelroom_code
 * @property int $channelrateplane_id
 * @property string $channelrateplan_code
 * @property int $num_adult
 * @property int $num_kid
 * @property int $price
 * @property int $sale_revenue
 * @property int $status
 * @property \Cake\I18n\FrozenTime $checkin
 * @property \Cake\I18n\FrozenTime $checkout
 * @property string $customer_note
 *
 * @property \App\Model\Entity\Channelbooking $channelbooking
 * @property \App\Model\Entity\Channelroom $channelroom
 * @property \App\Model\Entity\Channelrateplane $channelrateplane
 */
class ChannelbookingRoom extends Entity
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
