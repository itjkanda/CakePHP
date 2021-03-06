<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends AppModel {

  public $useTable = 'users';
  public $primaryKey = 'user_id';
  public $alias = 'User';

  public $validate = array(

    'name' => array(
      'rule' => 'notEmpty',
      'required' => true,
      'allowEmpty' => false,
      'message' => '※ユーザー名を入力してください'
    ),
    'email' => array(
      'rule' => 'notEmpty',
      'required' => true,
      'allowEmpty' => false,
      'message' => '※メールアドレスを入力してください'
    ),
    'email' => array(
      'rule' => 'duplicateCheck',
      'message' => '※重複しているメールアドレスです'
    ),
    'password' => array(
      'rule' => array('minLength', 4),
      'required' => true,
      'allowEmpty' => false,
      'message' => '※パスワードは4文字以上で入力してください'
    )

  );

  // メールアドレスの重複チェック
  public function duplicateCheck($user) {

    // メールアドレスを検索
    $count = $this->find('count',
      array(
        'conditions' => array('email' => $user['email'])
      )
    );

    return $count == 0;

  }

  public function imgUpload($data) {

    /**
     * モデルでSessionの書き込みを行うと
     * このモデルのメソッドが利用されているコントローラ側で
     * 流れを負いにくくなるため
     * このメソッドはファイルをアップロードして
     * ファイル名を返す程度にし
     * Sessionの書き込みはコントローラ側で行うようにしましょう
     */
    App::uses('CakeSession', 'Model/Datasource');
    $Session = new CakeSession();
    $path = IMAGES;
    $image = $data['User']['picture_tmp'];
    move_uploaded_file($image['tmp_name'], $path . DS . $image['name']);
    $Session->write('img_name', $image['name']);
    $data['User']['picture'] = $image['name'];

  }

}
