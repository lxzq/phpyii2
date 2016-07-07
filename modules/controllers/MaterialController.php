<?php

namespace app\modules\controllers;

use app\models\ArticleStyle;
use app\models\ArticleStyleGroup;
use app\models\MaterialText;
use app\models\Picture;
use app\models\UploadForm;
use Yii;
use app\models\MaterialNews;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;
use app\models\PublicList;
use app\models\MaterialImage;
use yii\web\UrlManager;
use app\modules\weixin\BaseController;

/**
 * MaterialController implements the CRUD actions for MaterialNews model.
 */
class MaterialController extends BaseController
{
    public $enableCsrfValidation = false;//yii默认表单csrf验证，如果post不带改参数会报错！
    public $layout  = 'layout';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),

                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'create','update','delete','get-news','material-data','picture','add-picture','del-picture','picture-data','text','add-text','del-text','text-data','get-text','syc-from-wechat','syc-to-wechat','syc-from-image','syc-to-image','get-style'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all MaterialNews models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model=new MaterialNews();
        $token =(new PublicList)->get_token();
        //$user_id=Yii::$app->user->getId();
        $info=(new \yii\db\Query())->select(['title','count(material_news.id) as count','author','introduction','material_news.add_time','group_id','path','material_news.id'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->where(['token'=>$token])->groupBy('group_id')->orderBy('group_id DESC');
        $pages = new Pagination(['totalCount' => $info->count(), 'pageSize' => '8']);
        $list = $info->offset($pages->offset)->limit($pages->limit)->all();
        foreach ( $list as &$vo ) {
            if($vo['count']>1)
            {
                $vo ['child'] =(new \yii\db\Query())->select(['title','author','introduction','material_news.add_time','group_id','path'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->where(['group_id'=>$vo['group_id']])->andWhere(['!=','material_news.id',$vo['id']])->all();
            }

        }
        return $this->render('index', [
            'news_list' => $list,
            'pages'=>$pages
        ]);
    }

    /**
     * Creates a new MaterialNews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (Yii::$app->request->ispost) {
            $textArr = array (
                1 => '一',
                2 => '二',
                3 => '三',
                4 => '四',
                5 => '五',
                6 => '六',
                7 => '七',
                8 => '八',
                9 => '九',
                10 => '十'
            );
            $data = json_decode (Yii::$app->request->post('dataStr'), true );
            foreach ( $data as $key => $vo ) {
                $save = array ();
                foreach ( $vo as $k => $v ) {
                    if($k==0){
                        if($v['name']=='id'){
                            $model=MaterialNews::findOne((int)$v['value']);
                        }else{
                            $model=new MaterialNews();
                            $model->$v['name']=safe($v['value']);
                        }
                    }
                    if($k>0){
                        $model->$v['name']=safe($v['value']);
                    }

                }
                if (empty ( $model->title )) {
                    $info['info'] = '请填写第' . $textArr [$key + 1] . '篇文章的标题' ;
                    break;
                }
                if (empty ( $model->cover_id )) {
                    $info['info'] = '请上传第' . $textArr [$key + 1] . '篇文章的封面图片' ;
                    break;
                }
                if (empty ( $model->content)) {
                    $info['info']  = '请填写第' . $textArr [$key + 1] . '篇文章的正文内容' ;
                    break;
                }
                if (! empty ( $model->id )) { // 更新数据
                    $model->save();
                } else { // 新增加
                    $model->add_time=time();
                    $model->user_id=Yii::$app->user->getId();
                    $model->token= (new PublicList())->get_token();
                    $model->save();
                    if ($model->id) {
                        $ids [] = $model->id;
                    } else {
                        if (! empty ( $ids )) {
                            MaterialNews::deleteAll(['in','id',$ids]);
                        }
                        $info['info'] ='增加第' . $textArr [$key + 1] . '篇文章失败，请检查数据后重试' ;

                    }
                }
            }
            $group_id=!empty($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
            if (! empty ( $ids )) {
                empty ( $group_id ) && $group_id = $ids [0];
                MaterialNews::updateAll(['group_id'=>$group_id],['in','id',$ids]);
            }
            if(!empty($info)){
                $info['status']='0';
                echo json_encode($info);
            }else{
                $info['status']=1;
                if(!empty($_REQUEST['group_id'])){
                    $info['info']="更新成功";
                }else{
                    $info['info']="添加成功";
                }
                $info['url']=Url::toRoute(['material/index']);;
                echo json_encode($info);
            }
            
        } else {
            if(!empty($_REQUEST['group_id'])){
                $token =(new PublicList)->get_token();
                $list=(new Query())->select(['title','author','introduction','material_news.add_time','group_id','path','cover_id','material_news.id','link','content'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->where(['token'=>$token,'group_id'=>$_REQUEST['group_id']])->orderBy('material_news.id asc')->all();
                $first=$list[0];
                unset($list[0]);
                if (! empty ( $list )) {
                    $others = $list;
                }else{
                    $others='';
                }
                return $this->render('create', [
                    'first' => $first,'others'=>$others,'group_id'=>$_REQUEST['group_id']
                ]);
            }else{
                $model = new MaterialNews();
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

        }
    }



    /**
     * Deletes an existing MaterialNews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete()
    {

        $flag=MaterialNews::deleteAll('group_id=:group_id',[':group_id'=>Yii::$app->request->post('group_id')]);
        if($flag){
            $info['status']=1;
            $info['info']="删除成功";
            $info['url']=Url::toRoute(['material/index']);
        }else{
            $info['status']=0;
            $info['info']="删除失败，请重试!";
            $info['url']=Url::toRoute(['material/index']);
        }
        echo json_encode($info);
    }

    /**
     *  获取所有图文数据
     */
    public function actionMaterialData() {
        $token =(new PublicList)->get_token();
        $user_id=Yii::$app->user->getId();
        $list=(new \yii\db\Query())->select(['title','count(material_news.id) as count','author','introduction','material_news.add_time','group_id','path','material_news.id'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->where(['user_id'=>$user_id,'token'=>$token])->groupBy('group_id')->orderBy('group_id DESC')->all();
        foreach ( $list as &$vo ) {
            if($vo['count']>1)
            {
                $vo ['child'] =(new \yii\db\Query())->select(['title','author','introduction','material_news.add_time','group_id','path'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->where(['group_id'=>$vo['group_id']])->andWhere(['!=','material_news.id',$vo['id']])->all();
            }

        }
        return $this->render('material-data',['list'=>$list]);
    }

    /**
     * 根据图文group_id获取图文数据
     */
    public function actionGetNews(){
        $request=Yii::$app->request;
        $group_id=$request->post('group_id');
        if($group_id){
            $user_id=Yii::$app->user->getId();
            $token = (new PublicList)->get_token();
            $data=(new \yii\db\Query())->select(['title','introduction','path','material_news.id'])->from('material_news')->leftJoin("picture",'material_news.cover_id=picture.id')->where(['user_id'=>$user_id,'token'=>$token,'group_id'=>$group_id])->all();
            foreach ($data as $vo){
                if ($vo['id']==$group_id){
                    $articles[]=array(
                        'id'=>$vo['id'],
                        'title'=>$vo['title'],
                        'intro'=>$vo['introduction'],
                        'img_url'=>$vo['path']
                    );
                }else{
                    //文章内容
                    $art['id']=$vo['id'];
                    $art ['title'] = $vo['title'];
                    $art ['img_url']=$vo['path'];
                    $articles[]=$art;
                }
            }
        }

       echo json_encode($articles);
    }
    /**
     *图片素材
     */
    public function actionPicture()
    {
        $token=(new PublicList())->get_token();
        $user_id=Yii::$app->user->getId();
        $model=MaterialImage::find()->select(['id','cover_url'])->where(['token'=>$token,'user_id'=>$user_id])->indexBy('id')->all();
        return $this->render('picture',['list'=>$model]);
    }

    /**
     * 添加图片素材
     */
    public function actionAddPicture() {
        $request=Yii::$app->request;
        $model=new MaterialImage();
        $model->cover_id=$request->post('cover_id');
        $model->cover_url=$request->post('src');
        $model->add_time=time();
        $model->user_id=Yii::$app->user->getId();
        $model->token=(new PublicList())->get_token();
        if($model->save())
        {
            $info['status']=1;
        }else{
            $info['status']=0;
            $info['info']="添加失败，请重试！";
        }
        echo json_encode($info);
    }

    /**
     * 删除图片素材
     */
    public function actionDelPicture() {
        $model=MaterialImage::findOne(Yii::$app->request->post('id'));
        $model->delete();
    }

    /**
     * 获取图片素材
     */
    public function actionPictureData() {
        $user_id=Yii::$app->user->getId();
        $token=(new PublicList())->get_token();
        $model=MaterialImage::find()->select(['id','cover_id','cover_url'])->where(['user_id'=>$user_id,'token'=>$token])->orderBy('id desc')->all();

        return $this->render('picture-data',['list'=>$model]);
    }
    /**
     * 文本素材
     */
    public function actionText()
    {
        $token=(new PublicList())->get_token();
        $user_id=Yii::$app->user->getId();
        $model=MaterialText::find()->select(['id','content'])->where(['token'=>$token,'user_id'=>$user_id])->indexBy('id')->all();
        return $this->render('text',['list'=>$model]);
    }
    /**
     * 添加文本素材
     */
    public function actionAddText() {
        $request=Yii::$app->request;
        if($request->isPost)
        {
            if($request->post('id'))
            {
                $model=MaterialText::findOne($request->post('id'));
                $model->content=$request->post('content');
                $model->user_id=Yii::$app->user->getId();
            }else{
                $model=new MaterialText();
                $model->content=$request->post('content');
                $model->user_id=Yii::$app->user->getId();
                $model->token=(new PublicList())->get_token();
                $model->is_use=1;
            }
            if($model->save())
            {
                $info['status']=1;
                $info['info']='添加成功！';
                $info['url']=Url::toRoute(['material/text']);
            }else{
                $info['status']=0;
                $info['info']="添加失败，请重试！";
            }

            echo json_encode($info);
        }else
        {
            if($request->get('text_id'))
            {
                $model=MaterialText::findOne($request->get('text_id'));
            }else{
                $model=new MaterialText();
            }
            return $this->render('add-text',['model'=>$model]);
        }

    }

    /**
     * 删除文本素材
     */
    public function actionDelText() {
        $model=MaterialText::findOne(Yii::$app->request->get('text_id'));
        $model->delete();
    }

    /**
     * 获取文本素材
     */
    public function actionTextData() {
        $user_id=Yii::$app->user->getId();
        $token=(new PublicList())->get_token();
        $model=MaterialText::find()->select(['id','content'])->where(['user_id'=>$user_id,'token'=>$token])->orderBy('id desc')->all();

        return $this->render('text-data',['list'=>$model]);
    }

    /**
     * 获取文本内容
     */

    public function actionGetText()
    {
        $model=MaterialText::find()->select(['content'])->where(['id'=>Yii::$app->request->post('id')])->one();
        echo $model->content;
    }
    /**
     * 拉取微信素材到本地
     */
    public function actionSycFromImage()
    {
        $data=$this->get_material_list('image',0,20);
        if(!empty($data['errcode']) && $data['errcode']!=0){

            $return['status']=0;
            $return['info']=$data['errmsg'];
        }else {
            if ($data['total_count'] > 20) {
                $this->write_material('image', $data);
                for ($i = $data['total_count']; $i > 0; $i = $i - 20) {
                    if ($i > 20) {
                        $info = $this->get_material_list('image', 0, 20);
                        $this->write_material('image', $info);
                    } else {
                        $info = $this->get_material_list('image', 0, $i);
                        $this->write_material('image', $info);
                    }
                }
            } else {
                $this->write_material('image', $data);
            }
            $return['status']=1;
            $return['info']='拉取微信素材成功！';
        }
        echo json_encode($return);
    }
    // 上传图片素材
    public function actionSycToImage() {
        // 上传本地素材
        $token=(new PublicList())->get_token();
        $user_id=Yii::$app->user->getId();
        $list=(new Query())->select(['id','cover_id'])->from('material_image')->orderBy('group_id DESC')->where(['user_id'=>$user_id,'token'=>$token,'media_id'=>0])->orderBy('add_time desc')->all();
        foreach ( $list as $vo ) {
            $mediaId = $this->image_media_id ( $vo ['cover_id'] );
            if ($mediaId) {
                $model=MaterialImage::findOne($vo['id']);
                $model->media_id=$mediaId;
                $model->save();
            }
        }
        $return['status']=1;
        $return['info']='发送微信素材成功！';
        echo json_encode($return);
    }
    /**
     * 拉取微信素材到本地
     */
    public function actionSycFromWechat()
    {
        $data=$this->get_material_list('news',0,20);
        if(!empty($data['errcode']) && $data['errcode']!=0){
            $return['status']=0;
            $return['info']=$data['errmsg'];
        }else{
            if(!empty($data['total_count']) && $data['total_count']>20){
                $this->write_material('news',$data);
                for($i=$data['total_count'];$i>0;$i=$i-20){
                    if($i>20){
                        $info=$this->get_material_list('news',0,20);
                        $this->write_material('news',$info);
                    }else{
                        $info=$this->get_material_list('news',0,$i);
                        $this->write_material('news',$info);
                    }

                }
            }else{
                $this->write_material('news',$data);
            }
            $return['status']=1;
            $return['info']='拉取微信素材成功！';
        }
        echo json_encode($return);
    }
    /**
     * 发送本地图文消息到微信服务器
     */
    public function actionSycToWechat()
    {
        $token=(new PublicList())->get_token();
        $info=(new Query())->select(['title','count(material_news.id) as count','author','introduction','content','group_id','thumb_media_id','url','id','cover_id','link'])->from('material_news')->groupBy('group_id')->orderBy('group_id DESC')->where(['token'=>$token,'media_id'=>'0'])->all();
        foreach($info as $v){
            if($v['count']>1){
                $model=MaterialNews::find()->where(['group_id'=>$v['group_id']])->asArray()->all();
                $data=$this->send_material('news',$model);
                if(!empty($data['errcode']) && $data['errcode']!=0){
                    $return['status']=0;
                    $return['info']=error_msg($data);
                    echo json_encode($return);
                    exit;
                }else{
                    MaterialNews::updateAll(['media_id'=>$data['media_id']],['group_id'=>$v['group_id']]);
                }

            }else{
                $data=$this->send_material('news',$v);
                if(!empty($data['errcode']) && $data['errcode']!=0){
                    $return['status']=0;
                    $return['info']=error_msg($data);
                    echo json_encode($return);
                    exit;
                }else{
                    $model=MaterialNews::findOne($v['id']);
                    $model->media_id=$data['media_id'];
                    $model->save();
                }

            }
        }
        $return['status']=1;
        $return['info']='发送微信素材成功！';
        echo json_encode($return);
    }
    /**
     * 获取样式
     */
    public function actionGetStyle()
    {
        $request=Yii::$app->request;
        $group_id = !empty($request->get('group_id'))?$request->get('group_id'):0;
        $groupList = ArticleStyleGroup::find()->asArray()->all();
        if($groupList){
            if($group_id==0){
                $groupList[0]['class'] = "current";
                $group_id = $groupList[0]['id'];
            }else{
                foreach($groupList as &$v){
                    if($v['id']==$group_id){
                        $v['class'] = "current";
                    }
                }
            }
            $list=ArticleStyle::find()->where(['group_id'=>$group_id])->asArray()->all();
        }
        return $this->render('style',['group_list'=>$groupList,'list'=>$list]);
    }
}
