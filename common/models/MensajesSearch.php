<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mensajes;

/**
 * MensajesSearch represents the model behind the search form of `common\models\Mensajes`.
 */
class MensajesSearch extends Mensajes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'remitente_id', 'destinatario_id', 'estado_dest'], 'integer'],
            [['asunto', 'contenido', 'created_at', 'remitente.nombre', 'destinatario.nombre'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'remitente.nombre',
            'destinatario.nombre'
        ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param array $cond
     *
     * @return ActiveDataProvider
     */
    public function search($params, $cond)
    {
        if ($cond == 'recibidos') {
            $arrayWhere = [
                'destinatario_id' => Yii::$app->user->id,
            ];
            $arrayAndWhere = ['<>', 'estado_dest', Mensajes::ESTADO_BORRADO];
            $arrayJoin = ['remitente', 'remitente.usuario'];
        } else {
            $arrayWhere = ['remitente_id' => Yii::$app->user->id];
            $arrayAndWhere = ['<>', 'estado_rem', Mensajes::ESTADO_BORRADO];
            $arrayJoin = ['destinatario', 'destinatario.usuario'];
        }
        $query = Mensajes::find()->where($arrayWhere)
            ->andWhere($arrayAndWhere)
            ->joinWith($arrayJoin);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (isset($params['remitente_nombre'])) {
            $params['remitente.nombre'] = $params['remitente_nombre'];
        }
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->defaultOrder = ['created_at' => SORT_DESC];

        $dataProvider->sort->attributes['remitente.nombre'] = [
            'asc' => ['usuarios.username' => SORT_ASC],
            'desc' => ['usuarios.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['destinatario.nombre'] = [
            'asc' => ['usuarios.username' => SORT_ASC],
            'desc' => ['usuarios.username' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'remitente_id' => $this->remitente_id,
            'destinatario_id' => $this->destinatario_id,
            'estado_dest' => $this->estado_dest,
        ]);
        // grid filtering conditions
        $array = explode(' a ', $this->created_at);
        if ($array[0] != '') {
            $inicio = Yii::$app->formatter->asDate($array[0], 'php:Y-m-d');
            $final = Yii::$app->formatter->asDate($array[1], 'php:Y-m-d');
        } else {
            $inicio = '';
            $final = '';
        }
        $query->andFilterWhere([
            'between',
            'CAST(mensajes.created_at AS date)',
            $inicio,
            $final
        ]);

        $query->andFilterWhere(['ilike', 'asunto', $this->asunto])
            ->andFilterWhere(['ilike', 'contenido', $this->contenido])
            ->andFilterWhere(['ilike', 'usuarios.username', $this->getAttribute('remitente.nombre')])
            ->andFilterWhere(['ilike', 'usuarios.username', $this->getAttribute('destinatario.nombre')]);

        return $dataProvider;
    }
}
