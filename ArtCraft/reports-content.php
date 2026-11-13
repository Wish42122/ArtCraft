<?php
$months = sql("SELECT DATE_FORMAT(created_at,'%Y-%m') AS month, COUNT(*) AS total, SUM(status='pending') AS pending, SUM(status='approved') AS approved, SUM(status='rejected') AS rejected FROM artworks GROUP BY month ORDER BY month DESC")->fetchAll();
$events = sql('SELECT e.id,e.title,e.event_date,COUNT(p.user_id) AS registrations FROM exhibitions e LEFT JOIN participation p ON p.exhibition_id=e.id GROUP BY e.id,e.title,e.event_date ORDER BY e.event_date DESC')->fetchAll();
if (isset($_GET['csv']) && in_array($_GET['csv'],['submissions','participation'],true)) {
    $submissions = $_GET['csv']==='submissions';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="'.($submissions?'submissions':'participation').'.csv"');
    $out = fopen('php://output','wb');
    fputcsv($out,$submissions ? ['Month','Total','Pending','Approved','Rejected'] : ['ID','Exhibition','Date','Registrations'],',','"','');
    foreach ($submissions ? $months : $events as $row) {
        $safe = array_map(static fn($v)=>preg_match('/^[\s]*[=+\-@]|^[\t\r\n]/u',(string)$v) ? "'".$v : $v, $row);
        fputcsv($out,array_values($safe),',','"','');
    }
    exit;
}
?>
