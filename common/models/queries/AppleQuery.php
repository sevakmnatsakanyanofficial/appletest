<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Apple]].
 *
 * @see \common\models\Apple
 */
class AppleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Apple[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Apple|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
