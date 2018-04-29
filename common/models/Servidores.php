<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "servidores".
 *
 * @property int $id
 * @property string $url
 * @property int $puerto
 * @property int $usuario_id
 *
 * @property Usuarios $usuario
 */
class Servidores extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servidores';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'puerto', 'usuario_id'], 'required'],
            [['puerto', 'usuario_id'], 'default', 'value' => null],
            [['usuario_id'], 'integer'],
            [['puerto'], 'number', 'min' => 1],
            [['url'], 'string', 'max' => 255],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Dirección Web',
            'puerto' => 'Puerto',
            'usuario_id' => 'Usuario ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('servidores');
    }
}