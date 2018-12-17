<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 2018/12/11
 * Time: 1:48 PM
 */

namespace backend\modules\rbac\models;

use Yii;

/**
 * This is the model class for table "c_admin_menu".
 *
 * @property int $id
 * @property int $pid
 * @property string $name
 * @property string $icon
 * @property string $url
 * @property int $sort
 * @property string $description 描述
 * @property int $status
 */
class AdminMenu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%admin_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'sort', 'status'], 'integer'],
            [['name', 'icon'], 'string', 'max' => 32],
            [['url', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => 'Name',
            'icon' => 'Icon',
            'url' => 'Url',
            'sort' => 'Sort',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }
}