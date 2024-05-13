<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%employees_position}}".
 *
 * @property int $id
 * @property string|null $position
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Employees[] $employees
 */
class EmployeesPosition extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employees_position}}';
    }
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return date('d-m-Y H:i:s');
                },
            ],
            // [
            //     'class' => BlameableBehavior::class,
            //     'updatedByAttribute' => false
            // ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['created_at', 'updated_at'], 'integer'],
            [['position', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\EmployeesQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employees::class, ['position_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\EmployeesPositionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\EmployeesPositionQuery(get_called_class());
    }
}
