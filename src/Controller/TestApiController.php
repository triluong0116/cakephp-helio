<?php
/**
 * Created by PhpStorm.
 * User: D4rk
 * Date: 5/15/2019
 * Time: 10:26 AM
 */

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 * @property \App\Model\Table\LocationsTable $Locations
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class TestApiController extends AppController {

    /**
     * Displays a view
     *
     * @param array ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function run() {
        $location_id = 8;
        $clientId = "ksdlk2037yroih028yp2qeh01q8";
        $price = "100000-2000000";
        $fromDate = "2019-12-16";
        $toDate = "2019-12-18";
        $rating = 4;
        $this->paginate = [
            'limit' => 10
        ];
        $this->loadModel('Rooms');
        $this->loadModel('HotelSearchs');

        $condition = [];
        $today = date('Y-m-d');
        $condition['single_day'] = $today;
        $outputSlider = '';

        if ($price) {
            $listPrice[] = $price;
            $sliderArray = explode('-', $price);
            $outputSlider = implode(',', $sliderArray);
            if($sliderArray ){
                $condition['price_day >= '] = $sliderArray[0];
                $condition['price_day <= '] = $sliderArray[1];
            }
        }


        if ($location_id) {
            $condition['location_id'] = $location_id;
        }
        if ($rating) {
            $condition['rating IN'] = $rating;
        }
        $tmpHotelSearchs = $this->HotelSearchs->find()->contain([
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            },
            'Rooms'])
            ->where([$condition]);
        $hotels = $this->paginate($tmpHotelSearchs);
        foreach ($hotels as $hotel) {
            if ($hotel->favourites) {
                $hotel->is_favourite = true;
            } else {
                $hotel->is_favourite = false;
            }
            unset($hotel->favourites);
        }
        dd($hotels);
        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $hotels,
            '_serialize' => ['status', 'message', 'data']
        ]);

    }

}
