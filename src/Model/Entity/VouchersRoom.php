<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VouchersRoom Entity
 *
 * @property int $id
 * @property int $voucher_id
 * @property int $room_id
 *
 * @property \App\Model\Entity\Voucher $voucher
 * @property \App\Model\Entity\Room $room
 */
class VouchersRoom extends Entity
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
        'voucher_id' => true,
        'room_id' => true,
        'voucher' => true,
        'room' => true
    ];
}
