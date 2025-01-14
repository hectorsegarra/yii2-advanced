<?php

namespace modules\conversacion\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use modules\conversacion\models\ConversacionParticipantes;

/**
 * ConversacionParticipantesSearch represents the model behind the search form of `modules\conversacion\models\ConversacionParticipantes`.
 */
class ConversacionParticipantesSearch extends ConversacionParticipantes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'conversacion_id', 'usuario_id', 'administrador'], 'integer'],
            [['entra_el', 'ultima_leida'], 'safe'],
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
        $query = ConversacionParticipantes::find();

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
            'usuario_id' => $this->usuario_id,
            'entra_el' => $this->entra_el,
            'administrador' => $this->administrador,
            'ultima_leida' => $this->ultima_leida,
        ]);

        return $dataProvider;
    }
}
