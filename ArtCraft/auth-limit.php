<?php
function limit_login(string $identifier): void {
    
    $window = intdiv(time(), 900);
    $address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $limits = ['address:'.$address=>80,'account:'.$address.':'.strtolower(substr($identifier,0,254))=>12];
    foreach ($limits as $scope=>$limit) {
        $key = hash('sha256',$scope);
        sql('INSERT INTO login_attempts (scope_hash,window_id,attempts) VALUES (?,?,1)
             ON DUPLICATE KEY UPDATE attempts=LEAST(attempts+1,1000)',[$key,$window]);
        $count = (int)sql('SELECT attempts FROM login_attempts WHERE scope_hash=? AND window_id=?',[$key,$window])->fetchColumn();
        if ($count > $limit) {
            header('Retry-After: '.(900-time()%900));
            http_response_code(429);
            exit('Too many sign-in attempts. Try again in a few minutes.');
        }
    }
    
    if (random_int(1,32)===1) sql('DELETE FROM login_attempts WHERE window_id<? LIMIT 1000',[$window-1]);
}
