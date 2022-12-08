<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Channelpayment Entity
 *
 * @property int $id
 * @property int $booking_id
 * @property int $type
 * @property int $invoice
 * @property string $invoice_information
 * @property string $images
 * @property string $address
 * @property int $pay_object
 * @property int $check_type
 * @property string|null $partner_information
 * @property string|null $payment_photo
 * @property string|null $merchtxnref
 * @property int|null $onepaystatus
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Booking $booking
 */
class Channelpayment extends Entity
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
        'booking_id' => true,
        'type' => true,
        'invoice' => true,
        'invoice_information' => true,
        'images' => true,
        'address' => true,
        'pay_object' => true,
        'check_type' => true,
        'partner_information' => true,
        'payment_photo' => true,
        'merchtxnref' => true,
        'onepaystatus' => true,
        'created' => true,
        'modified' => true,
        'booking' => true
    ];
}
