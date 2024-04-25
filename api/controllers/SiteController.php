<?php

namespace api\controllers;

use api\models\ResendVerificationEmailForm;
use api\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\LoginForm;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use yii\filters\auth\HttpBearerAuth;

use common\models\User;
use yii\rest\Controller; //Controller API


/**
 * Site controller
 */
class SiteController extends Controller
{

    public function actionLogin()
    {
        $model = new LoginForm();
     
        // $user = $model->getUser();
        // dd($user);
        if ($model->load(Yii::$app->request->post(), '') && ($token= $model->login())) {

            // Generate access token
            // $token = Yii::$app->security->generateRandomString();
    
            // // Simpan access token ke dalam model User
            // $user->access_token = $token;
            // $user->save();
            // dd($token);
        
            return [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => time() + 1000,
            ]; 
        } else {
            return $model;
        }
    }


}
