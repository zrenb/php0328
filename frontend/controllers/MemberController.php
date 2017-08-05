<?php

namespace frontend\controllers;


use frontend\models\Abres;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
class MemberController extends \yii\web\Controller
{
    public $layout = true;
    public $enableCsrfValidation = false;
    public function actionIndexMember()
    {
        return $this->render('index-member');
    }
    //用户注册

    //验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

    public function actionRegistMember()
    {
       $model = new Member();
        return $this->render('regist-member',['model'=>$model]);
    }

    //ajax 处理表单提交的数据
    public function actionAjaxMember()
    {

        $model = new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            $code = \Yii::$app->session->get('code_'.$model->tel);
            if($code == $model->tel_code)
            {
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->pwd);
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->created_at = time();
                $model->status = 1;
                $model->save(false);
                return Json::encode(['status'=>true,'msg'=>'注册成功']);
            }else{
                return Json::encode(['status'=>false,'msg'=>'手机验证码不正确']);
            }

        }else{
            return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
        }
    }



    public function actionLoginMember()
    {
        $model = new LoginForm();
        return $this->render('login-member',['model'=>$model]);
    }


    //安全退出
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['member/login-member']);
    }


    //ajax 处理登录表单传过来的数据  登录
    public function actionLoginAjaxMember()
    {
        $login = new LoginForm();
        if ($login->load(\Yii::$app->request->post()) && $login->validate())
        {
            $member = Member::findOne(['username' => $login->username]);
                    //\Yii::$app->user->login($member);       //登录
                    \Yii::$app->user->login($member, $login->safe_login ? 3600 * 24 * 30 : 0);
                    $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                    $member->last_login_time = time();
                    $member->save(false);


 /**************************同步cookie 数据*************************************/
                    $cookie = \Yii::$app->request->cookies;
                    $cart = $cookie->get('cat');
                    if($cart){
                        $carts = unserialize($cart->value);
                        foreach ($carts as $goods_id=>$amount){
                            $car = Cart::find()->andWhere(['=','member_id',$member->id])->andWhere(['=','goods_id',$goods_id])->asArray()->all();
                            if($car){
                                $car = Cart::findOne(['goods_id'=>$goods_id]);
                                $car->amount+=$amount;
                                $car->save();
                                //return $this->redirect(['goods/show-cart']);
                            }else{
                                $car = new Cart();
                                $car->goods_id = $goods_id;
                                $car->amount = $amount;
                                $car->member_id = $member->id;
                                $car->save();
                                // return $this->redirect(['goods/show-cart']);
                            }
                          //清除cookie数据
                            $cookie = \Yii::$app->response->cookies;
                            $cookie->remove('cat');
                        }
                    }
 /**************************同步cookie 数据*************************************/
            return Json::encode(['status' => true, 'msg' => '登录成功']);

            }
         else{
             return Json::encode(['status' => false, 'msg' =>$login->getErrors()]);
         }
        //return Json::encode(['status' => false, 'msg' =>'用户名不存在']);

    }

    //修改
    public function actionEditMember($id=1)
    {
        $model = Member::findOne(['id'=>$id]);
        //var_dump($model);exit;
        return $this->render('edit-member',['model'=>$model]);
    }



    //收货地址
    public function actionAdres()
    {
        $model = new Abres();
        if($model->load(\Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $model->user_id=\Yii::$app->user->getId();
                $model->save();
                return Json::encode(['status'=>true,'msg'=>'添加成功']);
            }else{
                return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
            }
        }
        return $this->render('index-address',['model'=>$model]);

    }

    //收货地址列表
    public function actionAddressIndex()
    {
        $member_id = \Yii::$app->user->id;
        if($member_id == null){
            return $this->redirect(['member/login-member']);
        }
        $model = new Abres();
        $addresses = Abres::find()->where(['=','user_id',$member_id])->all();
        //var_dump($addresses);
        return $this->render('index-address',['model'=>$model,'addresses'=>$addresses]);

    }


    //收货地址修改
    public function actionAddressEdit($id)
    {
        $model = new Abres();
       $address = Abres::findOne(['id'=>$id]);

       if($model->load(\Yii::$app->request->post()))
       {
          if($model->validate())
          {
              $model->save();
              return Json::encode(['status'=>true,'msg'=>'修改成功']);
          }else{
              return Json::encode(['status'=>false,'msg'=>$model->getErrors()]);
          }
       }



       if($address == null)
       {
           throw new NotFoundHttpException('warning','你修改的地址不存在');
       }
       return $this->render('edit-address',['model'=>$model,'address'=>$address]);
    }



    //收货地址删除
    public function actionAddressDel($id)
    {
        $address = Abres::findOne(['id'=>$id]);
        if($address == null)
        {
            throw new NotFoundHttpException('warning','你删除的地址不存在');
        }
        $address->delete();
        return $this->redirect('address-index');
    }

    public function actionSms($tel)
    {
        $code=rand(1000,9999);
        \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        \Yii::$app->session->set('code_'.$tel,$code);
    }

}
