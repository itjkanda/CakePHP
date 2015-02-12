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

	public $uses = array();

	public function index() {

	}

	// 会員登録系
	public function join() {

		if ($this->Session->check('data')){
			$this->set('data', $this->Session->read('data'));
		}

		// postされた場合
		if ($this->request->is('post')) {

			$data = $this->request->data;

			// 書き直し用にsessionに保存
			$this->Session->write('data', $data);

			// 画像のアップロード
			// user-defined function
			$this->User->imgUpload($data);

			// バリデーション
			$this->User->set($data);
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

			// picture
			$this->request->data['User']['picture'] = $this->Session->read('img_name');

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

				// ログインに使用したメールアドレスからIDを取得後、ログイン時間と一緒にSession,Cookieに保存
				$userData = $this->Auth->user();
				$user_id = $this->User->getUserIdFromEmail($userData);
				$this->User->updateSession();
				$this->User->updateCookie();

				$this->redirect('/posts/');

			} else {

				$this->request->data['Login']['password'] = '';
				$this->set('error', 'Cookieみたけどログインできなかったお');

			}
		}

		// cookieが残っていなかった場合
		if ($this->request->is('post')) {

			$data = $this->request->data;

			// ログインチェック
			if ($this->Auth->login($data)) {

				// 自動ログインにチェックがあった場合
				if ($data['User']['autoLogin']) {
					$this->User->updateCookie();
				}

				// ログインに使用したメールアドレスからIDを取得後、ログイン時間と一緒にSessionに保存
				$userData = $this->Auth->user();
				$user_id = $this->User->getUserIdFromEmail($userData);
				$this->User->updateSession();

				$this->redirect('/posts/index');

			} else {

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

	public function sample() {

		// Authのテスト用

	}

}
