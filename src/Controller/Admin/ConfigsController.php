<?php

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * Configs Controller
 *
 * @property \App\Model\Table\ConfigsTable $Configs
 *
 * @method \App\Model\Entity\Config[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ConfigsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $configs = $this->paginate($this->Configs);

        $this->set(compact('configs'));
    }

    /**
     * View method
     *
     * @param string|null $id Config id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $config = $this->Configs->get($id, [
            'contain' => []
        ]);

        $this->set('config', $config);
    }

    public function policy()
    {
        $config = $this->Configs->find()->where(['type' => "chinh-sach-cong-tac-vien"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'policy']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function bankaccount()
    {
        $config = $this->Configs->find()->where(['type' => "bank-invoice"])->first();
        $config_account = $this->Configs->find()->where(['type' => "bank-account"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            $bank_accounts = [];
            if (isset($request['bank_account'])) {
                foreach ($request['bank_account'] as $key => $bank_acc) {
                    if ($request['bank_account'][$key]['bank_logo']['error'] == 0) {
                        $logo_account = $this->Upload->uploadSingle($request['bank_account'][$key]['bank_logo']);
                        $request['bank_account'][$key]['bank_logo'] = $logo_account;
                    } else {
                        $request['bank_account'][$key]['bank_logo'] = $request['bank_account'][$key]['bank_logo_edit'];
                    }
                    $bank_accounts[] = $request['bank_account'][$key];
                }
                $bank_account = json_encode($bank_accounts, JSON_UNESCAPED_UNICODE);
                $data_account['value'] = $bank_account;
                $data_account['status'] = 0;
                $data_account['type'] = "bank-account";
                if (!$config_account) {
                    $config_account = $this->Configs->newEntity();
                    $config_account = $this->Configs->patchEntity($config_account, $data_account);
                    if ($this->Configs->save($config_account)) {
                        $this->Flash->success(__('The config has been saved.'));
                    } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
                } else {
                    $config_account = $this->Configs->patchEntity($config_account, $data_account);
                    if ($this->Configs->save($config_account)) {
                        $this->Flash->success(__('The config has been saved.'));
                    } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
                }
            }
            if ($request['bank_invoice']['bank_logo']['error'] == 0) {
                $logo_invoice = $this->Upload->uploadSingle($request['bank_invoice']['bank_logo']);
                $request['bank_invoice']['bank_logo'] = $logo_invoice;
            } else {
                $request['bank_invoice']['bank_logo'] = $request['bank_invoice']['bank_logo_edit'];
            }
            $bank_invoice = json_encode($request['bank_invoice'], JSON_UNESCAPED_UNICODE);
            $data['value'] = $bank_invoice;
            $data['type'] = "bank-invoice";
            $data['status'] = 0;
            if (!$config) {
                $config = $this->Configs->newEntity();
                $config = $this->Configs->patchEntity($config, $data);
                if ($this->Configs->save($config)) {
                    $this->Flash->success(__('The config has been saved.'));
                    return $this->redirect(['action' => 'bankaccount']);
                } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
            } else {
                $config = $this->Configs->patchEntity($config, $data);
                if ($this->Configs->save($config)) {
                    $this->Flash->success(__('The config has been saved.'));
                    return $this->redirect(['action' => 'bankaccount']);
                } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
            }
        }
        if ($config) {
            $bank_invoice= json_decode($config->value, true);
            $bank_invoice['bank_logo_edit'] = $bank_invoice['bank_logo'];
            $this->set(compact('bank_invoice'));
        }
        if ($config_account) {
            $bank_accounts = json_decode($config_account->value, true);
            foreach ($bank_accounts as $key => $bank_account){
                $bank_accounts[$key]['bank_logo_edit'] =  $bank_account['bank_logo'];
            }
            $this->set(compact('bank_accounts'));
        }
    }

    public function bankaccountLandtour()
    {
        $config = $this->Configs->find()->where(['type' => "bank-invoice-landtour"])->first();
        $config_account = $this->Configs->find()->where(['type' => "bank-account-landtour"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            $bank_accounts = [];
            if (isset($request['bank_account'])) {
                foreach ($request['bank_account'] as $key => $bank_acc) {
                    if ($request['bank_account'][$key]['bank_logo']['error'] == 0) {
                        $logo_account = $this->Upload->uploadSingle($request['bank_account'][$key]['bank_logo']);
                        $request['bank_account'][$key]['bank_logo'] = $logo_account;
                    } else {
                        $request['bank_account'][$key]['bank_logo'] = $request['bank_account'][$key]['bank_logo_edit'];
                    }
                    $bank_accounts[] = $request['bank_account'][$key];
                }
                $bank_account = json_encode($bank_accounts, JSON_UNESCAPED_UNICODE);
                $data_account['value'] = $bank_account;
                $data_account['status'] = 0;
                $data_account['type'] = "bank-account-landtour";
                if (!$config_account) {
                    $config_account = $this->Configs->newEntity();
                    $config_account = $this->Configs->patchEntity($config_account, $data_account);
                    if ($this->Configs->save($config_account)) {
                        $this->Flash->success(__('The config has been saved.'));
                    } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
                } else {
                    $config_account = $this->Configs->patchEntity($config_account, $data_account);
                    if ($this->Configs->save($config_account)) {
                        $this->Flash->success(__('The config has been saved.'));
                    } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
                }
            }
            if ($request['bank_invoice']['bank_logo']['error'] == 0) {
                $logo_invoice = $this->Upload->uploadSingle($request['bank_invoice']['bank_logo']);
                $request['bank_invoice']['bank_logo'] = $logo_invoice;
            } else {
                $request['bank_invoice']['bank_logo'] = $request['bank_invoice']['bank_logo_edit'];
            }
            $bank_invoice = json_encode($request['bank_invoice'], JSON_UNESCAPED_UNICODE);
            $data['value'] = $bank_invoice;
            $data['type'] = "bank-invoice-landtour";
            $data['status'] = 0;
            if (!$config) {
                $config = $this->Configs->newEntity();
                $config = $this->Configs->patchEntity($config, $data);
                if ($this->Configs->save($config)) {
                    $this->Flash->success(__('The config has been saved.'));
                    return $this->redirect(['action' => 'bankaccountLandtour']);
                } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
            } else {
                $config = $this->Configs->patchEntity($config, $data);
                if ($this->Configs->save($config)) {
                    $this->Flash->success(__('The config has been saved.'));
                    return $this->redirect(['action' => 'bankaccountLandtour']);
                } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
            }
        }
        if ($config) {
            $bank_invoice= json_decode($config->value, true);
            $bank_invoice['bank_logo_edit'] = $bank_invoice['bank_logo'];
            $this->set(compact('bank_invoice'));
        }
        if ($config_account) {
            $bank_accounts = json_decode($config_account->value, true);
            foreach ($bank_accounts as $key => $bank_account){
                $bank_accounts[$key]['bank_logo_edit'] =  $bank_account['bank_logo'];
            }
            $this->set(compact('bank_accounts'));
        }
    }

    public function rechargeAgentInfor() {
        $this->loadModel('Configs');
        $configBank = $this->Configs->find()->where(['type' => 'bank-recharge-infor'])->first();
        $bankAccounts = [];
        if ($configBank->value) {
            $bank_accounts = json_decode($configBank->value, true);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $request = $this->request->getData();
            $bank_accounts = [];
            if (isset($request['bank_account'])) {
                foreach ($request['bank_account'] as $key => $bank_acc) {
                    if ($request['bank_account'][$key]['bank_logo']['error'] == 0) {
                        $logo_account = $this->Upload->uploadSingle($request['bank_account'][$key]['bank_logo']);
                        $request['bank_account'][$key]['bank_logo'] = $logo_account;
                    } else {
                        $request['bank_account'][$key]['bank_logo'] = $request['bank_account'][$key]['bank_logo_edit'];
                    }
                    $bank_accounts[] = $request['bank_account'][$key];
                }
                $bank_account = json_encode($bank_accounts, JSON_UNESCAPED_UNICODE);
                $data_account['value'] = $bank_account;
                $data_account['status'] = 0;
                $data_account['type'] = "bank-recharge-infor";
                if (!$configBank) {
                    $configBank = $this->Configs->newEntity();
                    $configBank = $this->Configs->patchEntity($configBank, $data_account);
                    if ($this->Configs->save($configBank)) {
                        $this->Flash->success(__('The config has been saved.'));
                    } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
                } else {
                    $configBank = $this->Configs->patchEntity($configBank, $data_account);
                    if ($this->Configs->save($configBank)) {
                        $this->Flash->success(__('The config has been saved.'));
                    } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
                }
            }
        }
        $this->set(compact('bank_accounts'));
    }

    public function addAccount()
    {
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render('add_account');
    }

    public function paymentmethod()
    {
        $config = $this->Configs->find()->where(['type' => "huong-dan-thanh-toan"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'paymentmethod']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
        $this->set(compact('config'));
    }

    public function secretpolicy()
    {
        $config = $this->Configs->find()->where(['type' => "chinh-sach-rieng-tu-bao-mat"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'secretpolicy']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function usemethod()
    {
        $config = $this->Configs->find()->where(['type' => "dieu-khoan-su-dung"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'usemethod']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function bestprice()
    {
        $config = $this->Configs->find()->where(['type' => "chinh-sach-cam-ket-ga-tot-nhat"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'bestprice']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function simplequestion() {
        $config = $this->Configs->find()->where(['type' => "cau-hoi-thuong-gap"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'simplequestion']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function dispute() {
        $config = $this->Configs->find()->where(['type' => "giai-quyet-tranh-chap"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'dispute']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function mustgo() {
        $config = $this->Configs->find()->where(['type' => "must-go-la-gi"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'mustgo']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    public function header()
    {
        $config = $this->Configs->find()->where(['type' => "anh-background"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            if ($data['thumbnail']['error'] == 0) {
                $thumbnail = $this->Upload->uploadSingle($data['thumbnail']);
                $data['thumbnail'] = $thumbnail;
            } else {
                unset($data['thumbnail']);
            }
            $config = $this->Configs->patchEntity($config, ['value' => $data['thumbnail']]);
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'header']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
//        dd($config->toArray());
        $this->set(compact('config'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $config = $this->Configs->newEntity();
        if ($this->request->is('post')) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
        $this->set(compact('config'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Config id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $config = $this->Configs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $config = $this->Configs->patchEntity($config, $this->request->getData());
            if ($this->Configs->save($config)) {
                $this->Flash->success(__('The config has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The config could not be saved. Please, try again.'));
        }
        $this->set(compact('config'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Config id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $config = $this->Configs->get($id);
        if ($this->Configs->delete($config)) {
            $this->Flash->success(__('The config has been deleted.'));
        } else {
            $this->Flash->error(__('The config could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function experiedhotelday()
    {
        $config = $this->Configs->find()->where(['type' => "ngay-het-han-khach-san"])->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['type'] = "ngay-het-han-khach-san";
            $data['status'] = 0;
            if (!$config) {
                $config = $this->Configs->newEntity();
                $config = $this->Configs->patchEntity($config, $data);
                if ($this->Configs->save($config)) {
                    $this->Flash->success(__('The config has been saved.'));
                    return $this->redirect(['action' => 'experiedhotelday']);
                } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
            } else {
                $config = $this->Configs->patchEntity($config, $data);
                if ($this->Configs->save($config)) {
                    $this->Flash->success(__('The config has been saved.'));
                    return $this->redirect(['action' => 'experiedhotelday']);
                } else $this->Flash->error(__('The config could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('config'));
    }

}
