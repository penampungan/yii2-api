<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\backend\transaksi\models\PenjualanClosing */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Penjualan Closings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penjualan-closing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'CREATE_BY',
            'CREATE_AT',
            'UPDATE_BY',
            'UPDATE_AT',
            'STATUS',
            'CLOSING_ID',
            'ACCESS_UNIX',
            'CLOSING_DATE',
            'OUTLET_ID',
            'TTL_MODAL',
            'TTL_UANG',
            'TTL_QTY',
            'TTL_STORAN',
            'TTL_SISA',
        ],
    ]) ?>

</div>
