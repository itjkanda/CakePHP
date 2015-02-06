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

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class LoginsController extends AppController {

	public $components = array(
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

		// cookieのチェック
		if ($this->Cookie->check('email')) {

			// Cookieから値を取得
			$data['Login']['email'] = $this->Cookie->read('email');
			$data['Login']['password'] = $this->Cookie->read('password');

			// ログインチェック
			$memberData = $this->Login->checkLogin($data['Login']['email'], $data['Login']['password']);

			if ($memberData) {
				// ログイン通過

				// Sessionの更新
				$this->Session->write('user_id', $memberData['member_id']);
				$this->Session->write('loginTime', time());

				// Cookieの更新
				$this->Cookie->write('email', $data['Login']['email'], false, '+2 weeks');
				$this->Cookie->write('password', $data['Login']['password'], false, '+2 weeks');

				$this->redirect('/posts/index');

			} else {
				$this->request->data['Login']['password'] = '';
				$this->set('error', 'Cookieみたけどログインできなかったお');
			}
		}

		if ($this->request->is('post')) {

			// データの取得
			$data = $this->request->data;
			// ログインチェック
			$memberData = $this->Login->checkLogin($data['Login']['email'], $data['Login']['password']);

			if ($memberData) {
				// ログイン通過

				$this->Session->write('user_id', $memberData['member_id']);
				$this->Session->write('loginTime', time());
				// 自動ログインにチェックがあった場合
				if ($data['Login']['autoLogin']) {
					$this->Cookie->write('email', $data['Login']['email'], false, '+2 weeks');
					$this->Cookie->write('password', $data['Login']['password'], false, '+2 weeks');
				}

				$this->redirect('/posts/index');

			} else {
				echo 'ログインできてない';
				$this->request->data['Login']['password'] = '';
				$this->set('error', 'メールアドレスとパスワードの組み合わせが間違っています');
			}

		}

	}
}
