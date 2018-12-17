<?php
namespace common\action;

use Yii;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Created by PhpStorm.
 * User: hero
 * Date: 2018/12/10
 * Time: 12:30 AM
 */

class Captcha extends \yii\captcha\CaptchaAction
{

    /**
     * rewrite
     */
    public function run()
    {
        if (Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) !== null) {
            // AJAX request for regenerating code
            $code = $this->getVerifyCode(true);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'hash1' => $this->generateValidationHash($code),
                'hash2' => $this->generateValidationHash(strtolower($code)),
                // we add a random 'v' parameter so that FireFox can refresh the image
                // when src attribute of image tag is changed
                'url' => Url::to([$this->id, 'v' => uniqid('', true)]),
            ];
        }

        $this->setHttpHeaders();
        Yii::$app->response->format = Response::FORMAT_RAW;

        return $this->renderImage($this->getVerifyCode(true));
    }
}