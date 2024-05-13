<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use common\models\SalaryCalculator;

class SalaryController extends Controller
{
    public function actionCalculate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $hourlyRate = $request->post('hourlyRate');

        $calculator = new SalaryCalculator();
        $calculator->hourlyRate = $hourlyRate;
        
        $totalSalary = $calculator->calculateMonthlySalary();

        return $totalSalary;
       
    }
}
