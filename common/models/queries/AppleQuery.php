<?php

namespace common\models\queries;

/**
 * This is the ActiveQuery class for [[\common\models\Apple]].
 *
 * @see \common\models\Apple
 */
class AppleQuery extends \yii\db\ActiveQuery
{
    /**
     * Condition to choose only not eaten apples
     * @return AppleQuery
     */
    public function notEaten()
    {
        return $this->andWhere('eat_percent < 100');
    }
}
