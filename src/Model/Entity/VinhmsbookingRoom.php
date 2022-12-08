<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VinhmsbookingRoom Entity
 *
 * @property int $id
 * @property int $vinhmsbooking_id
 * @property string $vinhms_package_id
 * @property string $vinhms_room_id
 * @property int $room_id
 * @property \Cake\I18n\FrozenTime $checkin
 * @property \Cake\I18n\FrozenTime $checkout
 * @property int $num_adult
 * @property int $num_kid
 * @property int $num_child
 * @property string $customer_note
 * @property string $detail_by_day
 *
 * @property \App\Model\Entity\Vinhmsbooking $vinhmsbooking
 */
class VinhmsbookingRoom extends Entity
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
