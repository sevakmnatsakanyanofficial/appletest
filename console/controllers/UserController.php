<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;

/**
 * Class UserController
 *
 * User controller to manage users via console command
 *
 * @package console\controllers
 */
class UserController extends Controller
{
    /**
     * Method to register backend users
     * @param $username
     * @param $email
     * @param $password
     */
    public function actionRegister($username, $email, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        if ($user->save()) {
            echo 'User registered successfully';
        } else {
            echo 'error';
        }
    }
}