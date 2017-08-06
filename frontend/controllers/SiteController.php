<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\DataModel;
use yii\data\ActiveDataProvider;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays test page.
     *
     * @return mixed
     */
    public function actionTest()
    {
        $params = Yii::$app->request->queryParams;
        $card = $params['card'] ?? '';
        $year = $params['year'] ?? '';
        $month = $params['month'] ?? '';

        $model = new DataModel();
        $tree = $model->prepareShow($card);

        $query = DataModel::find()
            ->orderBy(['date' => SORT_ASC])
            ->where(['like', 'card_number', $card . '%', false]);

        if ($year) {
            $query->andWhere(['year(date)' => $year]);
        }

        if ($month) {
            $query->andWhere(['month(date)' => $month]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('test', [
            'tree' => $tree,
            'dataProvider' => $dataProvider,
            'params' => $params,
        ]);
    }
}
