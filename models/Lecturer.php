<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_lecturer".
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $join_date
 * @property bool|null $is_active
 * @property int|null $id_major
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Lecturer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tb_lecturer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['join_date', 'created_at', 'updated_at'], 'safe'],
            [['is_active'], 'boolean'],
            [['id_major'], 'default', 'value' => null],
            [['id_major'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'join_date' => 'Join Date',
            'is_active' => 'Is Active',
            'id_major' => 'Id Major',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\LecturerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\LecturerQuery(get_called_class());
    }
}