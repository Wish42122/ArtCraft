<?php
require __DIR__.'/bootstrap.php';
$user = require_user();
$error = null;
if (post()) {
    check_csrf();
    try {
        if ($user['role'] !== 'student') throw new DomainException('Exhibition registration is available to students.');
        $key = id(val('id'));
        if (val('action') === 'join') {
            $stmt = sql('INSERT IGNORE INTO participation (exhibition_id,user_id) SELECT id,? FROM exhibitions WHERE id=? AND event_date>=? ',
                [$user['id'],$key,date('Y-m-d')]);
            if (!$stmt->rowCount()) throw new DomainException('This exhibition is unavailable or you have already joined.');
            flash('You are registered.');
        } elseif (val('action') === 'leave') {
            sql('DELETE p FROM participation p JOIN exhibitions e ON e.id=p.exhibition_id WHERE p.exhibition_id=? AND p.user_id=? AND e.event_date>=?',
                [$key,$user['id'],date('Y-m-d')]);
            flash('Registration updated.');
        } else throw new DomainException('Unknown action.');
        go('exhibitions.php');
    } catch (DomainException | PDOException $exception) { http_response_code(422); $error = form_error($exception); }
}
$events = sql('SELECT e.*,p.user_id AS joined FROM exhibitions e LEFT JOIN participation p ON p.exhibition_id=e.id AND p.user_id=? ORDER BY e.event_date DESC',[$user['id']])->fetchAll();
