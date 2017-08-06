<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "data".
 *
 * @property integer $id
 * @property string $card_number
 * @property string $date
 * @property double $volume
 * @property string $service
 * @property integer $address_id
 */
class DataModel extends \yii\db\ActiveRecord
{
    private $monthName = [
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'volume', 'service'], 'required'],
            [['date'], 'safe'],
            [['volume'], 'number'],
            [['address_id'], 'integer'],
            [['card_number'], 'string', 'max' => 20],
            [['service'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_number' => 'Card Number',
            'date' => 'Date',
            'volume' => 'Volume',
            'service' => 'Service',
            'address_id' => 'Address ID',
        ];
    }

    /**
     * @param string $card
     * @return array
     *
     * Prepare left tree with counters
     */
    public function prepareShow($card = '')
    {
        $items = [];
        $firstBreak = true;

        foreach (self::find()
                    ->where(['like', 'card_number', $card . '%', false])
                    ->orderBy(['date' => SORT_ASC])
                    ->all()
                    as $row)
        {
            if ($firstBreak) {

                $myDate = $row['date'];
                $year = substr($myDate, 0, 4);
                $month = intval(substr($myDate, 5, 2));

                $tmpYear = $year;
                $tmpMonth = $month;

                $cntYear = 0;
                $cntMonth = 0;

                $firstBreak = false;
            }

            $myDate = $row['date'];
            $year = substr($myDate, 0, 4);
            $month = intval(substr($myDate, 5, 2));

            if ($tmpYear != $year) {
                $items[] = $this->makeYearTotal($tmpYear, $cntYear);
                $tmpYear = $year;
                $cntYear = 0;

                $items[] = $this->makeMonthTotal($tmpYear, $tmpMonth, $cntMonth);
                $tmpMonth = $month;
                $cntMonth = 0;
            }

            if ($tmpMonth != $month) {
                $items[] = $this->makeMonthTotal($tmpYear, $tmpMonth, $cntMonth);
                $tmpMonth = $month;
                $cntMonth = 0;
            }

            $cntYear ++;
            $cntMonth ++;
        }

        if (!$firstBreak) {
            $items[] = $this->makeMonthTotal($tmpYear, $tmpMonth, $cntMonth);
            $items[] = $this->makeYearTotal($tmpYear, $cntYear);
        }

        return $items;
    }

    private function makeMonthTotal($year,$nom, $count)
    {
        return [
            'name' => $this->monthName[$nom],
            'count' => $count,
            'year' => $year,
            'month' => $nom,
        ];
    }

    private function makeYearTotal($name, $count)
    {
        return [
            'name' => $name,
            'count' => $count,
            'year' => $name,
            'month' => 0,
        ];
    }
}
