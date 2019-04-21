<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class DriverSearch extends Model
{
    public $page = 0;
    public $id;

    public function rules()
    {
        return [
            [['page', 'id'], 'integer'],
        ];
    }

    public function search()
    {
        $query = Driver::find()
            ->with('buses');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
                'page' => $this->page,
            ]
        ]);

        if (!$this->validate()) {
            $query->where('false');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);

        return $dataProvider;
    }
}