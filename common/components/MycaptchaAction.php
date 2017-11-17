<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

use Yii;
use yii\captcha\CaptchaAction;
/**
 * 重写yii\captcha\CaptchaAction类的两个方法
 * 原来的类在引用后每次不会生成新的验证码
 * 修改$regenerate = false为true后，验证的时候又会每次重新生成验证码，导致每次验证都失败
 * @author Administrator
 *
 */
class MycaptchaAction extends CaptchaAction
{
    /**
     * 将$regenerate默认值改为true，这样页面每次引用的时候都会生成一个新的验证码，其他代码不变
     * @param string $regenerate
     * @return Ambigous <>
     */
    public function getVerifyCode($regenerate = true)
    {
        if ($this->fixedVerifyCode !== null) {
            return $this->fixedVerifyCode;
        }

        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey();
        if ($session[$name] === null || $regenerate) {
            $session[$name] = $this->generateVerifyCode();
            $session[$name . 'count'] = 1;
        }

        return $session[$name];
    }

    /**
     * 将$code = $this->getVerifyCode();改为$code = $this->getVerifyCode(false);
     * 这样在检查验证码是否正确的时候
     * 其他代码不变
     * @param unknown $input
     * @param unknown $caseSensitive
     * @return boolean
     */
    public function validate($input, $caseSensitive)
    {
        $code = $this->getVerifyCode(false);
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey() . 'count';
        $session[$name] = $session[$name] + 1;
        if ($valid || $session[$name] > $this->testLimit && $this->testLimit > 0) {
            $this->getVerifyCode(true);
        }

        return $valid;
    }
}
