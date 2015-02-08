<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

	public function beforeFilter() {
		// parent::beforeFilter();
		$this->Auth->allow('join', 'check', 'complete');
	}

	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array('Controller' => 'Posts', 'action' => 'index'),
			'logoutRedirect' => array('Controller' => 'Users', 'action' => 'login'),
			'authenticate' => array(
				'Form' => array(
					'userModel' => 'User',
					'fields' => array(
						'username' => 'email'
					),
					'passwordHasher' => array(
						'className' => 'Simple',
						'hashType' => 'md5'
					)
				)
			)
		),
		'Cookie'
	);

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */

	public function index() {

	}

	// 会員登録系
	public function join() {

		if ($this->Session->check('data')){
			$this->set('data', $this->Session->read('data'));
		} else {}
		// postされた場合
		if ($this->request->is('post')) {

			$data = $this->request->data;
			// モデルにデータをセット
			$this->User->set($data);
			// 書き直し用にsessionに保存
			$this->Session->write('data', $data);

			// 画像のアップロード
			$path = IMAGES;
			// 送信された画像を取得
			$image = $this->request->data['User']['picture_tmp'];
			move_uploaded_file($image['tmp_name'], $path . DS . $image['name']);
			$this->Session->write('img_name', $image['name']);
			$this->request->data['User']['picture'] = $image['name'];
			$data = $this->request->data;

			// バリデーション
			if ($this->User->validates()) {
				// 変数をsessionにセット
				$this->Session->write('data', $data);
				$this->redirect('check');
			}

		}
		$this->Session->delete('data');

	}

	public function check() {

		// sessionからデータをセット
		if ($this->Session->check('data')) {
			$this->set('data', $this->Session->read('data'));
			$this->set('img_name', $this->Session->read('img_name'));
		}

		if ($this->request->is('post')) {
			$this->redirect('complete');
		}

	}

	public function complete() {

		if ($this->Session->check('data')) {

			// hasherのインスタンス化
			$passwordHasher = new SimplePasswordHasher();

			// session変数の代入
			$this->request->data = $this->Session->read('data');

			// passwordのhash化
			$this->request->data['User']['password'] = $passwordHasher->hash($this->request->data['User']['password']);

			// データをDBに保存
			$this->User->save($this->request->data);

		}

	}

	public function login() {

		// cookieが残っていた場合
		if ($this->Cookie->check('email')) {

			// Cookieから値を取得
			$data['User']['email'] = $this->Cookie->read('email');
			$data['User']['password'] = $this->Cookie->read('password');

			// ログインチェック
			if ($this->Auth->login()) {
				// ログイン通過

				// emailからmember_idを取得
				// ここの汚さセンスゼロ
				$userData = $this->Auth->user();
				$user_id = $this->User->find('all',
					array(
						'fields' => array('User.member_id'),
						'conditions' => array('User.email' => $userData['User']['email'])
					)
				);
				$user_id = $user_id[0]['User']['member_id'];

				// Sessionの更新
				$this->Session->write('user_id', $user_id);
				$this->Session->write('loginTime', time());

				// Cookieの更新
				$this->Cookie->write('email', $data['User']['email'], false, '+2 weeks');
				$this->Cookie->write('password', $data['User']['password'], false, '+2 weeks');

				$this->redirect('/posts/index');

			} else {
				$this->request->data['Login']['password'] = '';
				$this->set('error', 'Cookieみたけどログインできなかったお');
			}
		}

		// cookieが残っていなかった場合
		if ($this->request->is('post')) {

			// データの取得
			$data = $this->request->data;

			// 入力されたpasswordのhash化
			// →これbeforesaveに書いたほうがスッキリする？
			// $this->request->data['User']['password'] = $this->User->hashPassword($this->request->data['User']['password']);

			// ログインチェック
			if ($this->Auth->login($this->request->data)) {

				// ログイン通過
				// echo 'login successed';

				// sessionに保存
				// $this->Session->write('user_id', $data['member_id']);
				// $this->Session->write('loginTime', time());
				// 自動ログインにチェックがあった場合
				if ($data['User']['autoLogin']) {
					$this->Cookie->write('email', $data['User']['email'], false, '+2 weeks');
					$this->Cookie->write('password', $data['User']['password'], false, '+2 weeks');
				}

				$this->redirect('/posts/index');

			} else {
				echo 'ログインできてない';
				$this->request->data['User']['password'] = '';
				$this->set('error', 'メールアドレスとパスワードの組み合わせが間違っています');
			}

		}

	}

	public function logout() {

		$this->Auth->logout();

		// session削除
		$this->Session->destroy();

		$this->redirect(array('action' => 'login'));

	}

}
