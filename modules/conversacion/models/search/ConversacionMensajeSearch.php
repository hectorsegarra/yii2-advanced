<?php

namespace modules\conversacion\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use modules\conversacion\models\ConversacionMensaje;

/**
 * ConversacionMensajeSearch represents the model behind the search form of `modules\conversacion\models\ConversacionMensaje`.
 */
class ConversacionMensajeSearch extends ConversacionMensaje
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'conversacion_id', 'sender_id', 'grupal'], 'integer'],
            [['mensaje', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ConversacionMensaje::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'conversacion_id' => $this->conversacion_id,
            'sender_id' => $this->sender_id,
            'created_at' => $this->created_at,
            'grupal' => $this->grupal,
        ]);

        $query->andFilterWhere(['like', 'mensaje', $this->mensaje]);

        return $dataProvider;
    }
}
