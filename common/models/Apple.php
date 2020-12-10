<?php

namespace common\models;

use Yii;
use common\models\queries\AppleQuery;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

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

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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
    public function rules(): array
    {
        return [
            [['user_id', 'color'], 'required'],
            [['user_id', 'status', 'fell_at'], 'integer'],
            [['eat_percent'], 'number', 'max' => 100],
            [['status'], 'default', 'value' => self::STATUS_ON_TREE],
            [['eat_percent'], 'default', 'value' => 0],
            [['fell_at'], 'default', 'value' => 0],
            [['color'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [self::STATUS_ON_TREE, self::STATUS_ON_GROUND]],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE], 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\queries\AppleQuery the active query used by this AR class.
     */
    public static function find(): AppleQuery
    {
        return new AppleQuery(get_called_class());
    }

    /**
     * Get random apple color
     *
     * @return string
     */
    public static function randColor(): string
    {
        $rand = rand(0, 2);
        $colors = [
            self::COLOR_YELLOW,
            self::COLOR_GREEN,
            self::COLOR_RED
        ];
        return $colors[$rand];
    }

    /**
     * Sets model status to STATUS ON GROUND
     * set fell time and save
     * And only apples on tree can fall
     *
     * @return bool
     * @throws Exception
     */
    public function fall(): bool
    {
        if ($this->canFall()) {
            $this->status = self::STATUS_ON_GROUND;
            $this->fell_at = time();
            return $this->save();
        }

        throw new Exception('Яблоко не на дереве, и оно не может упасть');
    }

    /**
     * Sets model eat percent by user percent data if can eat
     * Eat percent can not be more than 100% => apple must be exist
     *
     * @param $percent
     * @return bool
     * @throws Exception
     */
    public function eat($percent): bool
    {
        if ($this->canEat()) {
            $this->eat_percent += $percent;
            if ($this->eat_percent > 100) {
                $this->eat_percent = 100;
            }
            return $this->save();
        }

        throw new Exception('Яблоко не может быть съедено.');
    }

    /**
     * Check if apple can fall
     * And only apples on tree can fall
     *
     * @return bool
     */
    public function canFall(): bool
    {
        return $this->status == self::STATUS_ON_TREE;
    }

    /**
     * Check if user can eat an apple
     * can if it is not on tree, not rotten, and exist
     *
     * @return bool
     */
    public function canEat(): bool
    {
        return $this->status != self::STATUS_ON_TREE && !$this->isRotten() && $this->eat_percent < 100;
    }

    /**
     * Check if an apple is rotten
     * it is rotten if the apple fell more than 5 hours
     *
     * @return bool
     */
    public function isRotten(): bool
    {
        if ($this->fell_at === 0 ) {
            return false;
        }

        $now = time();
        $fellAt = $this->fell_at;
        $interval = ($now - $fellAt)/(60*60);

        return $interval > 5;
    }

    /**
     * Gets status label for view part
     * @return string
     */
    public function realStatusLabel(): string
    {
        if ($this->isRotten()) {
            return 'гнилое яблоко';
        } elseif ($this->status == self::STATUS_ON_TREE) {
            return 'висит на дереве';
        } elseif ($this->status == self::STATUS_ON_GROUND) {
            return 'упало/лежит на земле';
        } else {
            return '-';
        }

    }
}
