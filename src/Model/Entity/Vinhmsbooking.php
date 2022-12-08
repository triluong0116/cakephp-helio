<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Vinhmsbooking Entity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $surname
 * @property string $phone
 * @property string $email
 * @property string $nation
 * @property string $country
 * @property \Cake\I18n\FrozenTime $checkin
 * @property \Cake\I18n\FrozenTime $checkout
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\VinhmsbookingGroupInfor[] $vinhmsbooking_group_infors
 * @property \App\Model\Entity\VinhmsbookingRoom[] $vinhmsbooking_rooms
 */
class Vinhmsbooking extends Entity
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
