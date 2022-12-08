<?php
namespace App\Controller\Api\V600;

/**
 * HomeStays Controller
 *
 *
 *
 * @method \App\Model\Entity\Homestay[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HomestaysController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['lists', 'detail', 'calPrice', 'calPriceBooking']);
    }

    public function lists() {
        $this->loadModel('HomeStays');
        $location_id = $this->getRequest()->getQuery('location_id');
        $rating = $this->getRequest()->getQuery('rating');
        $price = $this->getRequest()->getQuery('price');
        $clientId = $this->getRequest()->getQuery('clientId');
        $fromDate = $this->getRequest()->getQuery('fromDate');
        $toDate = $this->getRequest()->getQuery('toDate');
        $keyword = $this->getRequest()->getQuery('keyword');
        $page = $this->getRequest()->getQuery('page');
        $condition = [];
        if ($location_id) {
            $condition['location_id'] = $location_id;
        }
        if ($rating) {
            $condition['rating'] = $rating;
        }
        if ($price) {
            $price_arr = explode('-', $price);
            //do later
        }
        $condition['HomeStays.name LIKE'] = '%' . $keyword . '%';
        $list_homestays = $this->HomeStays->find()->contain(['Locations',
            'Favourites' => function ($q) use ($clientId) {
                return $q->where(['clientId' => $clientId]);
            },
            'PriceHomeStays'
        ])->where($condition)->toArray();
        $homestays = [];
        foreach ($list_homestays as $homestay) {
            if ($homestay->favourites) {
                $homestay->is_favourite = true;
            } else {
                $homestay->is_favourite = false;
            }
            unset($homestay->favourites);
            if ($fromDate) {
                $fromDate = date('Y-m-d', strtotime($fromDate));
            } else {
                $fromDate = date('Y-m-d');
            }

            $singlePrice = $this->Util->countingHomeStayPrice($fromDate, $homestay);
            $homestay->singlePrice = $singlePrice;
            unset($homestay->price_home_stays);
            if (count($price_arr) == 2) {
                if ($homestay->singlePrice >= $price_arr[0] && $homestay->singlePrice <= $price_arr[1]) {
                    $homestays[] = $homestay;
                }
            } else {
                if ($price_arr[0] == '2000000') {
                    if ($homestay->singlePrice <= $price_arr[0]) {
                        $homestays[] = $homestay;
                    }
                }
                if ($price_arr[0] == '10000000') {
                    if ($homestay->singlePrice >= $price_arr[0]) {
                        $homestays[] = $homestay;
                    }
                }
            }
        }
        $homestays = array_slice($homestays, 10 * ($page - 1), 10);

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $homestays,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function detail($id) {
        $this->loadModel('HomeStays');
        $clientId = $this->getRequest()->getQuery('clientId');
        $homestay = $this->HomeStays->get($id, ['contain' => ['Locations',
            'Favourites' => function ($query) use ($clientId) {
                return $query->where(['clientId' => $clientId]);
            }
        ]]);
        $listCaption = json_decode($homestay->caption, true);
        foreach($listCaption as $key => $caption){
            $listCaption[$key]['content'] = strip_tags(html_entity_decode($caption['content']));
        }
        $homestay->caption = $listCaption;
        $homestay->media = json_decode($homestay->media, true);
        $homestay->term = json_decode($homestay->term, true);
        if ($homestay->favourites) {
            $homestay->is_favourite = true;
        } else {
            $homestay->is_favourite = false;
        }
        unset($homestay->favourites);

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $homestay,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPrice() {
        $this->loadModel('HomeStays');
        $data = $this->getRequest()->getQuery();
        $homestay = $this->HomeStays->get($data['homestay_id'], ['contain' => 'PriceHomeStays']);

        $calSDate = $data['fromDate'];
        $calEDate = date('d-m-Y', strtotime($data['toDate']. "-1 days"));
        $dates = $this->Util->_dateRange($calSDate, $calEDate);

        $revenue = count($dates) * $homestay->price_customer ;

        $priceWeekDay = $priceWeekEnd = 0;
        $priceType = [];
        foreach ($homestay->price_home_stays as $price_home_stay) {
            if ($price_home_stay->type == WEEK_DAY) {
                $priceWeekDay = $price_home_stay->price;
            }
            if ($price_home_stay->type == WEEK_END) {
                $priceWeekEnd = $price_home_stay->price;
            }
            $singlePrice = $price_home_stay->price + $homestay->price_agency + $homestay->price_customer;
            $price_home_stay->singlePrice = $singlePrice;
            unset($price_home_stay->price);
            $priceType[] = $price_home_stay;
        }

        $totalPrice = 0;
        foreach ($dates as $date) {
            $unixTimestamp = strtotime($date);
            $weekday = date("l", $unixTimestamp);
            if ($weekday == 'Monday' || $weekday == 'Tuesday' || $weekday == 'Wednesday' || $weekday == 'Thursday') {
                $totalPrice += $priceWeekDay + $homestay->price_agency + $homestay->price_customer;
            } else {
                $totalPrice += $priceWeekEnd  + $homestay->price_agency + $homestay->price_customer;
            }
        }

        $response['priceType'] = $priceType;
        $response['totalPrice'] = $totalPrice;
        $response['revenue'] = $revenue;

        $this->set([
            'status' => STT_SUCCESS,
            'message' => 'Success',
            'data' => $response,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }

    public function calPriceBooking() {
        $this->loadModel('HomeStays');
        $this->loadModel('Users');
        $this->loadComponent('Util');
        $res = ['status' => STT_ERROR, 'message' => '', 'data' => []];
        $data = $this->getRequest()->getData();
        $homestay = $this->HomeStays->find()->contain('PriceHomeStays')->where(['id' => $data['item_id']])->first();
        if ($homestay) {
            $startDate = $data['start_date'];
            $endDate = $data['end_date'];
            $calSDate = $data['start_date'];
            if ($startDate && $endDate ) {
                $calEDate = date('d-m-Y', strtotime($data['end_date']. "-1 days"));
                $dates = $this->Util->_dateRange($calSDate, $calEDate);
                $priceWeekDay = $priceWeekEnd = 0;
                foreach ($homestay->price_home_stays as $price_home_stay) {
                    if ($price_home_stay->type == WEEK_DAY) {
                        $priceWeekDay = $price_home_stay->price;
                    } else if ($price_home_stay->type == WEEK_END) {
                        $priceWeekEnd = $price_home_stay->price;
                    }
                }
                $arrayDate = $this->Util->_dateRange($calSDate, $calEDate);
                $arrayWeek = [];
                $totalPrice = 0;
                foreach ($arrayDate as $date) {
                    $unixTimestamp = strtotime($date);
                    $weekday = date("l", $unixTimestamp);
                    if ($weekday == 'Monday' || $weekday == 'Tuesday' || $weekday == 'Wednesday' || $weekday == 'Thursday') {
                        $arrayWeek[$weekday] = WEEK_DAY;
                        $totalPrice += $priceWeekDay + $homestay->price_agency + $homestay->price_customer;
                    } else {
                        $arrayWeek[$weekday] = WEEK_END;
                        $totalPrice += $priceWeekEnd  + $homestay->price_agency + $homestay->price_customer;
                    }
                }
                $profit = 0;
                $user = $this->Users->find()->where(['id' => $data['user_id']])->first();
                if ($user && $user->role_id == 3) {
                    $profit = (sizeof($dates)) * $homestay->price_customer ;
                    if (isset($data['payment_method']) && $data['payment_method'] == AGENCY_PAY) {
                        $totalPrice = $totalPrice - $profit;
                        $profit = 0;
                    }
                }
                $response['price'] = $totalPrice;
                $response['profit'] = $profit;
                $res['status'] = STT_SUCCESS;
            } else {
                $res['status'] = STT_INVALID;
                $res['message'] = 'Thời gian check in và check out không thể để trống';
            }
        } else {
            $res['status'] = STT_NOT_FOUND;
            $res['message'] = 'Không tìm thấy thông tin homestay';
        }
        $this->set([
            'status' => $res['status'],
            'message' => $res['message'],
            'data' => ($res['status'] == STT_SUCCESS) ? $response : null,
            '_serialize' => ['status', 'message', 'data']
        ]);
    }
}

