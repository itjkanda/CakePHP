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
        
        
	public function beforeFilter() {
            /**
             * beforeFilterはすべてのメソッドの一番上に書きましょう
             */

		parent::beforeFilter();
		Security::setHash('md5');

	}

	// 会員登録系
	public function join() {
                /**
                 * この方法だとdataが存在しない場合がある
                 * つまりview側でif文をかかなきゃいけないので
                 * 非推奨です
                 *　
                 * どう対応するかちょっと考えてわからなかったらjoin.ctp見てみてください
                 * 
                 * またこの処理はpostされてvalidation通った際は不要な処理なはずなので
                 * 記載位置を考えてみて(PHPの変数はやたらメモリ食うので、極力不要な定義はしない)
                 */
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
            
                /**
                 * Sessionが存在しない状態でここにアクセスするとSesionが存在しないので
                 * View側でdata と img_nameが存在いない状態になっちゃいうね
                 * 
                 * どう対応しようか？
                 */

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
                        
                        /**
                         * この方式だと
                         * postされてる内容にuser_idとかプライマリーキーを入れられていると(開発者ツールでuser_idとかのツール使って勝手にフォーム生成されたりして)
                         * UPDATE文になり、勝手にレコードを書き換えることが可能だったりします
                         * 
                         * cakeでsaveを利用するとinsertとupdateをプライマリーキーで判断してくれちゃうので
                         * insertをしたい時はpostデータから必要なデータだけをとりだしてsave
                         * もしくはpostデータからプライマリーキーの配列を削除してsaveをするようにしましょう
                         */
			$this->User->save($this->request->data);

			$this->Session->destroy('check');

		}

	}

	public function login() {

		// ログイン情報が残っていた場合
            
                /**
                 * このページの機能はログアウトを除き
                 * ログインしている場合は不要なため
                 * ログインチェックをbeforeFilterで行ったほうがすっきりしそう
                 * また、beforeFilterで行わない場合も
                 * 
                 * ログインしたいたらredirectしてそこで処理を終わらせたほうが見やすいコードになる
                 * 
                 * )
                 * if ($this->Auth->loggedIn()) {
                 *      $this->redirect('/posts/');
                 *      return;
                 * }
                 * 
                 * if ($this->request->is('post')) {
                 * ～～～～～～～～～～～～～
                 * 
                 * みたいな感じ
                 * 
                 */
		if ($this->Auth->loggedIn()) {

			$this->redirect('/posts/');

		} else {

			if ($this->request->is('post')) {

				// ログインチェック
				if ($this->Auth->login()) {

					$this->redirect('/posts/index');

				} else {

					$this->request->data['User']['password'] = '';
					$this->set('error', 'メールアドレスとパスワードの組み合わせが間違っています');

				}
			}

		}

	}

	public function logout() {

		$this->Auth->logout();
		$this->Session->destroy();
		$this->redirect(array('action' => 'login'));

	}

	public function sample() {

		// Authのテスト用

	}

}
