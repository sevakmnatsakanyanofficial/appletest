<?php

namespace backend\controllers;

use Yii;
use common\models\Apple;
use common\models\AppleSearch;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * ApplesController implements the CRUD actions for Apple model.
 */
class ApplesController extends CommonController
{
    /**
     * Lists all Apple models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new random Apple models.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $randCount = rand(1, 10);

        for ($x = 1; $x <= $randCount; $x++) {
            $model = new Apple();
            $model->user_id = Yii::$app->user->id;
            $model->color = Apple::randColor();
            $model->status = Apple::STATUS_ON_TREE;
            $model->save();
            unset($model);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds Apple model by id and set its status as fill
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionFall($id)
    {
        $model = $this->findModel($id);

        if (!$model->fall()) {
            Yii::$app->session->setFlash('warning', 'Яблоко не может упасть');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds Apple model by id and eat by percent
     *
     * @param $id
     * @param $percent
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function actionEat($id, $percent)
    {
        if (!isset($percent) || !$percent) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        if (!is_numeric($percent)) {
            throw new BadRequestHttpException(Yii::t('app', 'Percent must be correct.'));
        }

        $model = $this->findModel($id);

        if (!$model->eat($percent)) {
            Yii::$app->session->setFlash('warning', 'Яблоко не может быть съедено');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Apple::find()
            ->where('[[id]]=:id', [':id' => $id])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
