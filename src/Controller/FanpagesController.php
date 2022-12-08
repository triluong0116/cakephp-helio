<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Fanpages Controller
 *
 * @property \App\Model\Table\FanpagesTable $Fanpages
 * @property \App\Model\Table\CombosTable $Combos 
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\HotelsTable $Hotels 
 * @property \App\Model\Table\LandToursTable $LandTours
 * 
 * @property \App\Model\Table\UserSharesTable $UserShares
 *
 * @method \App\Model\Entity\Fanpage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FanpagesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $fanpages = $this->paginate($this->Fanpages);

        $this->set(compact('fanpages'));
    }

    /**
     * View method
     *
     * @param string|null $id Fanpage id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $fanpage = $this->Fanpages->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('fanpage', $fanpage);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $fanpage = $this->Fanpages->newEntity();
        if ($this->request->is('post')) {
            $fanpage = $this->Fanpages->patchEntity($fanpage, $this->request->getData());
            if ($this->Fanpages->save($fanpage)) {
                $this->Flash->success(__('The fanpage has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fanpage could not be saved. Please, try again.'));
        }
        $users = $this->Fanpages->Users->find('list', ['limit' => 200]);
        $this->set(compact('fanpage', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Fanpage id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null) {
        $fanpage = $this->Fanpages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $fanpage = $this->Fanpages->patchEntity($fanpage, $this->request->getData());
            if ($this->Fanpages->save($fanpage)) {
                $this->Flash->success(__('The fanpage has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The fanpage could not be saved. Please, try again.'));
        }
        $users = $this->Fanpages->Users->find('list', ['limit' => 200]);
        $this->set(compact('fanpage', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Fanpage id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $fanpage = $this->Fanpages->get($id);
        if ($this->Fanpages->delete($fanpage)) {
            $this->Flash->success(__('The fanpage has been deleted.'));
        } else {
            $this->Flash->error(__('The fanpage could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getListFanpage() {
        $this->viewBuilder()->enableAutoLayout(false);
        $user_id = $this->Auth->user('id');
        $fanpages = $this->Fanpages->find('list')->where(['user_id' => $user_id])->toArray();
        $this->set(compact('fanpages'));
    }

    public function postFacebook() {
        $this->loadModel('UserShares');
        $this->viewBuilder()->enableAutoLayout(false);
        $response = ['success' => false, 'message' => ''];
        $data = $this->request->getData();

        if ($data['fb_post_type']) {
            $isAllowPost = true;
            $url_photo = $url_feed = $access_token = $list_image = $content = '';
            switch ($data['fb_post_type']) {
                case '1':
                    $access_token = $this->Auth->user('access_token');
                    $url_photo = '/me/photos/';
                    $url_feed = '/me/feed/';
                    break;
                case '2':
                    if (isset($data['fanpage_id']) && !empty($data['fanpage_id'])) {
                        $fanpage = $this->Fanpages->get($data['fanpage_id']);
                        $access_token = $fanpage->access_token;
                        $url_photo = '/' . $fanpage->page_id . '/photos/';
                        $url_feed = '/' . $fanpage->page_id . '/feed/';
                    } else {
                        $isAllowPost = false;
                        $response['message'] = 'Phải chọn Fanpage để đăng lên';
                    }
                    break;
                default;
                    $isAllowPost = false;
                    $response['message'] = 'Chưa hỗ trợ tính năng này';
                    break;
            }

            if ($isAllowPost) {
                $fb = $this->viewVars['fbGlobal'];

                switch ($data['object_type']) {
                    case COMBO:
                        $this->loadModel('Combos');
                        $object = $this->Combos->get($data['object_id'], ['contain' => ['Hotels']]);
                        $list_images = json_decode($object->media, true);

                        if (count($list_images) == 0) {
                            $list_images = [];
                            foreach ($object->hotels as $hotel) {
                                if ($hotel->media) {
                                    $hotel_medias = json_decode($hotel->media, true);
                                } else {
                                    $hotel_medias = [];
                                }
                                $list_images = array_merge($list_images, $hotel_medias);
                            }
                        }
                        $list_image = json_encode($list_images, JSON_UNESCAPED_UNICODE);
                        $urlShare = '<p>' . \Cake\Routing\Router::url(['_name' => 'combo.view', 'slug' => $object->slug, 'ref' => $this->Auth->user('ref_code')], true) . '</p>';
                        if ($object->fb_content) {
                            $tmpContent = html_entity_decode($object->fb_content) . "\n" . $urlShare;
                        } else {
                            $tmpContent = html_entity_decode($object->description) . "\n" . $urlShare;
                        }
                        $content = strip_tags($tmpContent);

                        break;
                    case VOUCHER:
                        $this->loadModel('Vouchers');
                        $object = $this->Vouchers->get($data['object_id'], ['contain' => ['Hotels']]);
                        $list_images = json_decode($object->media, true);

                        if (count($list_images) == 0) {
                            $list_images = [];
                            if ($object->hotel->media) {
                                $hotel_medias = json_decode($object->hotel->media, true);
                            } else {
                                $hotel_medias = [];
                            }
                            $list_images = array_merge($list_images, $hotel_medias);
                        }
                        $list_image = json_encode($list_images, JSON_UNESCAPED_UNICODE);
                        $urlShare = '<p>' . \Cake\Routing\Router::url(['_name' => 'voucher.view', 'slug' => $object->slug, 'ref' => $this->Auth->user('ref_code')], true) . '</p>';
                        if ($object->fb_content) {
                            $tmpContent = html_entity_decode($object->fb_content) . "\n" . $urlShare;
                        } else {
                            $tmpContent = html_entity_decode($object->description) . "\n" . $urlShare;
                        }
                        $content = strip_tags($tmpContent);
                        break;
                    case HOTEL:
                        $this->loadModel('Hotels');
                        $object = $this->Hotels->get($data['object_id']);
                        $list_image = $object->media;
                        $urlShare = '<p>' . \Cake\Routing\Router::url(['_name' => 'hotel.view', 'slug' => $object->slug, 'ref' => $this->Auth->user('ref_code')], true) . '</p>';
                        if ($object->fb_content) {
                            $tmpContent = html_entity_decode($object->fb_content) . "\n" . $urlShare;
                        } else {
                            $tmpContent = html_entity_decode($object->description) . "\n" . $urlShare;
                        }
                        $content = strip_tags($tmpContent);
                        break;
                    case LANDTOUR:
                        $this->loadModel('LandTours');
                        $object = $this->LandTours->get($data['object_id']);
                        $list_image = $object->media;
                        $urlShare = '<p>' . \Cake\Routing\Router::url(['_name' => 'landtour.view', 'slug' => $object->slug, 'ref' => $this->Auth->user('ref_code')], true) . '</p>';
                        if ($object->fb_content) {
                            $tmpContent = html_entity_decode($object->fb_content) . "\n" . $urlShare;
                        } else {
                            $tmpContent = html_entity_decode($object->description) . "\n" . $urlShare;
                        }
                        $content = strip_tags($tmpContent);
                        break;
                }
                $result = $this->Util->postToFacebook($fb, $access_token, $url_photo, $url_feed, $list_image, $content);
                if ($result['id']) {
                    $response['success'] = true;
                    $response['message'] = 'Đăng lên Facebook thành công';
                    $userShare = $this->UserShares->newEntity();
                    $data_share = [
                        'user_id' => $this->Auth->user('id'),
                        'type' => FACEBOOK_POST_TYPE,
                        'object_type' => $data['object_type'],
                        'object_id' => $data['object_id']
                    ];
                    $userShare = $this->UserShares->patchEntity($userShare, $data_share);
                    $this->UserShares->save($userShare);
                } else {
                    $response['message'] = 'Có lỗi xảy ra vui lòng thử lại';
                }
            }
        } else {
            $response['message'] = 'Bạn phải chọn hình thức đăng lên Facebook';
        }

        $res = $this->response;
        $res = $res->withType('json');
        $res = $res->withStringBody(json_encode($response));
        return $res;
    }

    public function isAuthorized($user) {
        // All registered users can add articles
        // Admin can access every action
        if ($this->Auth->user()) {
            return true;
        }
        return parent::isAuthorized($user);
    }

}
