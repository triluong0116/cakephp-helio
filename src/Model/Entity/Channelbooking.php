<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Channelbooking Entity
 *
 * @property int $id
 * @property string $code
 * @property int $user_id
 * @property int $sale_id
 * @property int $accountant_id
 * @property int $hotel_id
 * @property string $first_name
 * @property string $sur_name
 * @property \Cake\I18n\FrozenTime|null $start_date
 * @property \Cake\I18n\FrozenTime|null $end_date
 * @property \Cake\I18n\FrozenTime|null $complete_date
 * @property int $gender
 * @property string $phone
 * @property string $email
 * @property string $nationality
 * @property string $nation
 * @property int $status
 * @property int $price
 * @property int $sale_revenue
 * @property int $price_default
 * @property int $sale_revenue_default
 * @property string $information
 * @property int $agency_pay
 * @property int $is_paid
 * @property int $confirm_agency_pay
 * @property int $pay_hotel
 * @property string $note_for_hotel_payment
 * @property int $sale_discount
 * @property int $agency_discount
 * @property int $is_send_customer
 * @property int $is_send_channel
 * @property int $mail_type
 * @property int $pay_hotel_type
 * @property int $creator_type
 * @property string $note
 * @property int $change_price
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Sale $sale
 * @property \App\Model\Entity\Accountant $accountant
 * @property \App\Model\Entity\Hotel $hotel
 * @property \App\Model\Entity\ChannelbookingRoom[] $channelbooking_rooms
 */
class Channelbooking extends Entity
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
