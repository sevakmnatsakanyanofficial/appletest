<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%apple}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $color
 * @property int $status
 * @property float|null $eat_percent
 * @property int|null $fell_at
 * @property int $created_at
 *
 * @property User $user
 */
class Apple extends \yii\db\ActiveRecord
{
    public const COLOR_GREEN = 'green';
    public const COLOR_RED = 'red';
    public const COLOR_YELLOW = 'yellow';

    public const STATUS_ON_TREE = 1;
    public const STATUS_ON_GROUND = 2;
    public const STATUS_ROTTEN = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'color'], 'required'],
            [['user_id', 'status', 'fell_at', 'created_at'], 'integer'],
            [['eat_percent'], 'number'],
            [['status'], 'default', 'value' => self::STATUS_ON_TREE],
            [['eat_percent'], 'default', 'value' => 0],
            [['color'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'color' => Yii::t('app', 'Color'),
            'status' => Yii::t('app', 'Status'),
            'eat_percent' => Yii::t('app', 'Eat Percent'),
            'fell_at' => Yii::t('app', 'Fell At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\queries\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\AppleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\queries\AppleQuery(get_called_class());
    }
}
