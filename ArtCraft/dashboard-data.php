<?php
$counts = sql("SELECT COUNT(*) AS total,COUNT(CASE WHEN status='approved' THEN 1 END) AS approved,COUNT(CASE WHEN status='pending' THEN 1 END) AS pending FROM artworks")->fetch();
$users = sql('SELECT COUNT(*) FROM users')->fetchColumn();
$first = (new DateTimeImmutable('first day of this month'))->modify('-5 months');
$monthly = sql("SELECT DATE_FORMAT(created_at,'%Y-%m') AS month,COUNT(*) FROM artworks WHERE created_at>=? GROUP BY month", [$first->format('Y-m-d')])->fetchAll(PDO::FETCH_KEY_PAIR);
$maximum = max(5, (int)ceil(max([0, ...array_values(array_map('intval', $monthly))])/5)*5);
$points = [];
$linePath = '';
for ($index=0; $index<6; $index++) {
    $month = $first->modify("+$index months");
    $count = (int)($monthly[$month->format('Y-m')] ?? 0);
    $x = 60 + $index*96;
    $y = round(220-200*$count/$maximum, 2);
    $points[] = ['x'=>$x, 'y'=>$y, 'count'=>$count, 'label'=>$month->format('M Y')];
    $linePath .= ($index===0 ? 'M' : ' L').$x.','.$y;
}
$areaPath = $linePath.' L540,220 L60,220 Z';
$recent = sql(ART_QUERY.' ORDER BY a.id DESC LIMIT 4')->fetchAll();
