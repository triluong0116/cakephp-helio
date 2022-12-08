<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * LandtourPaymentFee Entity
 *
 * @property int $id
 * @property string $detail
 * @property string $partner_name
 * @property string $partnet_information
 * @property int $single_price
 * @property int $amount
 * @property int $total
 * @property int $payment_status
 * @property int $payment_type
 * @property \Cake\I18n\FrozenTime $date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class LandtourPaymentFee extends Entity
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
