<?php

namespace common\models;

use common\components\RuleHelper;
use common\traits\FixDateTo;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    use FixDateTo;

    public string|null $date_from = null;
    public string|null $date_to = null;
    public string|null $date_to_fixed = null;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        $model = new self();

        return [
            RuleHelper::date($model, 'date_from'),

            RuleHelper::date($model, 'date_to'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
    public function search(array $params): ActiveDataProvider
    {
        $query = User::find()->select(['*', User::getStatusTextForSelect()]);

        // add conditions that should always apply here

        $attrs = array_keys($this->attributeLabels());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['grid_view']['default_page_size'],
            ],
        ]);

        $this->load($params);

        $this->fixDateTo();

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['>=', 'DATE(FROM_UNIXTIME(created_at))', $this->date_from])
            ->andFilterWhere(['<=', 'DATE(FROM_UNIXTIME(created_at))', $this->date_to_fixed]);

        return $dataProvider;
    }
}
