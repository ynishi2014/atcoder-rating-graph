<?php
$idArray = ['yoichiro', 'rsk0315'];
$datasets = [];
foreach($idArray as $j => $id){
  $json = file_get_contents("https://atcoder.jp/users/".$id."/history/json");
  $data = json_decode($json, true);
  $line['label'] = $id;
  $line['pointRadius'] = 10;
  $line['backgroundColor'] = 'HSL('.(360/count($idArray)*$j).',100%,50%)';
  $line['data'] = [];
  foreach($data as $i => $row){
    if($row['IsRated']){
      $line['data'][] = ["x"=>$row['EndTime'],"y"=>$row['Performance']];
    }
  }
  $datasets[] = $line;
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<div style="width:1200px;height:800px">
<canvas id="myChart"></canvas>
</div>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var dataset = <?=json_encode($datasets);?>

for(let i = 0; i < dataset.length; i++){
  for(let j = 0; j < dataset[i].data.length; j++){
    dataset[i].data[j].x = new Date(dataset[i].data[j].x)
  }
}
var myScatterChart = new Chart(ctx, {
    type: 'scatter',
    data: {
      datasets: dataset
    },
    options:{
      title: {
        display: true,
          text: 'AtCoderパフォーマンス・レーティング'
      },
      scales: {
        xAxes: [{
          scaleLabel: {
            display: true,
            labelString: '日付'
          },
          ticks: {
            callback: function(value, index, values){
              var dateTime = new Date(value);
              return dateTime.toLocaleDateString();
            }
          }
        }],
        yAxes: [{
          scaleLabel: {
            display: true,
            suggestedMin: 0,
            labelString: '得点'
          },
          ticks: {
            suggestedMax: 100,
            suggestedMin: 0,
            stepSize: 400,
            callback: function(value, index, values){
              return  value +  '点'
            }
          }
        }]
      }
    }
  });
</script>
