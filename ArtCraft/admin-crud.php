<?php

if (!isset($admin, $kind) || $admin['role'] !== 'admin') { http_response_code(404); exit; }

$pages = [
    'users'=>['Manage Users','Create, search and manage student accounts','manage_users.php'],
    'artworks'=>['Manage Artworks','Review, approve or reject submitted artworks','manage_artworks.php'],
    'categories'=>['Manage Categories','Organize artworks by category','manage_categories.php'],
    'exhibitions'=>['Manage Exhibitions','Create and manage showcases and exhibitions','manage_exhibitions.php'],
];
[$heading,$subtitle,$page] = $pages[$kind];
$error = null;
$key = id($_GET['edit'] ?? null);
$edit = $key ? sql("SELECT * FROM `$kind` WHERE id=?".($kind==='users' ? " AND role='student'" : ''), [$key])->fetch() : null;
if ($key && !$edit) { http_response_code(404); exit('Record not found.'); }
$showForm = isset($_GET['new']) && $kind !== 'artworks' || $edit;
if (post()) {
    check_csrf();
    try {
        $key = id(val('id'));
        $action = val('action');
        if (!in_array($action,['save','delete','approve','reject'],true)) throw new DomainException('Unknown action.');
        $record = $key ? sql("SELECT id FROM `$kind` WHERE id=?".($kind==='users' ? " AND role='student'" : ''), [$key])->fetch() : null;
        if ($key && !$record || (!$key && ($action !== 'save' || $kind === 'artworks')) || (!$key && val('id') !== '0')) {
            throw new DomainException('The record is unavailable or cannot be changed here.');
        }
        if ($action === 'delete') {
            sql("DELETE FROM `$kind` WHERE id=?".($kind==='users' ? " AND role='student'" : ''), [$key]);
        } elseif ($kind === 'artworks' && in_array($action,['approve','reject'],true)) {
            sql('UPDATE artworks SET status=?,featured=0 WHERE id=?',[$action==='approve'?'approved':'rejected',$key]);
        } elseif ($action !== 'save') {
            throw new DomainException('Unknown action.');
        } elseif ($kind === 'users') {
            [$name,$sid,$email] = account_fields();
            $status = val('status');
            if (!in_array($status,['active','inactive'],true)) throw new DomainException('Select an account status.');
            $password = val('password',false);
            if (!$key) {
                sql('INSERT INTO users (fullname,studentid,email,status,password_hash) VALUES (?,?,?,?,?)',[$name,$sid,$email,$status,new_hash($password)]);
            } elseif ($password !== '') {
                sql("UPDATE users SET fullname=?,studentid=?,email=?,status=?,password_hash=? WHERE id=? AND role='student'",[$name,$sid,$email,$status,new_hash($password),$key]);
            } else {
                sql("UPDATE users SET fullname=?,studentid=?,email=?,status=? WHERE id=? AND role='student'",[$name,$sid,$email,$status,$key]);
            }
        } elseif ($kind === 'categories') {
            $name = val('name');
            if (!valid_text($name,80)) throw new DomainException('Enter a category name of up to 80 characters.');
            if ($key) sql('UPDATE categories SET name=? WHERE id=?',[$name,$key]);
            else sql('INSERT INTO categories (name) VALUES (?)',[$name]);
        } elseif ($kind === 'exhibitions') {
            $title = val('title'); $date = val('event_date'); $venue = val('venue'); $description = val('description');
            if (!valid_text($title,150) || !valid_text($venue,150) || !valid_text($description,3000,false)) {
                throw new DomainException('Enter a title and venue (up to 150 characters each) and an optional description (up to 3,000 characters).');
            }
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/D',$date,$m) || (int)$m[1]<1000 || !checkdate((int)$m[2],(int)$m[3],(int)$m[1])) {
                throw new DomainException('Enter a valid exhibition date.');
            }
            if ($key) sql('UPDATE exhibitions SET title=?,event_date=?,venue=?,description=? WHERE id=?',[$title,$date,$venue,$description,$key]);
            else sql('INSERT INTO exhibitions (title,event_date,venue,description) VALUES (?,?,?,?)',[$title,$date,$venue,$description]);
        } elseif ($kind === 'artworks') {
            [$title,$category,$description,$materials] = artwork_fields();
            $status = val('status');
            $featured = val('featured') === '1';
            if (!in_array($status,['pending','approved','rejected'],true)) throw new DomainException('Select a moderation status.');
            if ($featured && $status!=='approved') throw new DomainException('Approve the artwork before featuring it.');
            sql('UPDATE artworks SET title=?,category_id=?,description=?,materials=?,status=?,featured=? WHERE id=?',[$title,$category,$description,$materials,$status,(int)$featured,$key]);
        }
        flash($action === 'delete' ? 'Record deleted.' : 'Changes saved.');
        go($page);
    } catch (DomainException | PDOException $exception) {
        http_response_code(422);
        $error = form_error($exception);
    }
}

function field_value(string $key, string $default = ''): string {
    global $edit, $error;
    return $error && val('action') === 'save' ? val($key) : (string)($edit[$key] ?? $default);
}
function field(string $key, string $label, int $max, string $type = 'text', bool $required = true): void {
    $value = $type === 'password' ? '' : field_value($key);
    echo '<label>'.e($label).'<input name="'.e($key).'" type="'.e($type).'" value="'.e($value).'" maxlength="'.$max.'"'.($required?' required':'').($type==='password'?' autocomplete="new-password"':'').'></label>';
}
function select_field(string $key, string $label, array $options): void {
    echo '<label>'.e($label).'<select name="'.e($key).'">';
    $value = field_value($key);
    foreach ($options as $option) echo '<option'.($value===$option?' selected':'').' value="'.e($option).'">'.e(ucfirst($option)).'</option>';
    echo '</select></label>';
}
$q = $_GET['q'] ?? '';
$q = is_string($q) && valid_text($q,120,false) ? trim($q) : '';
$filter = $_GET['status'] ?? '';
$statuses = $kind==='artworks' ? ['pending','approved','rejected'] : ['active','inactive'];
$filter = in_array($filter,$statuses,true) ? $filter : '';
$categoryFilter = id($_GET['category'] ?? null);
$like = '%'.strtr($q,['!'=>'!!','%'=>'!%','_'=>'!_']).'%';
$params = [];
if ($kind === 'artworks') {
    $query = ART_QUERY." WHERE (a.title LIKE ? ESCAPE '!' OR u.fullname LIKE ? ESCAPE '!' OR c.name LIKE ? ESCAPE '!')";
    $params = [$like,$like,$like];
    if ($filter !== '') { $query .= ' AND a.status=?'; $params[] = $filter; }
    if ($categoryFilter) { $query .= ' AND a.category_id=?'; $params[] = $categoryFilter; }
    $query .= ' ORDER BY a.id DESC';
} elseif ($kind === 'users') {
    $query = "SELECT id,fullname,studentid,email,role,status FROM users WHERE (fullname LIKE ? ESCAPE '!' OR email LIKE ? ESCAPE '!' OR studentid LIKE ? ESCAPE '!')";
    $params = [$like,$like,$like];
    if ($filter !== '') { $query .= ' AND status=?'; $params[] = $filter; }
    $query .= ' ORDER BY id DESC';
} elseif ($kind === 'categories') {
    $query = "SELECT c.id,c.name,(SELECT COUNT(*) FROM artworks WHERE category_id=c.id) AS count FROM categories c WHERE c.name LIKE ? ESCAPE '!' ORDER BY c.name";
    $params = [$like];
} else {
    $query = "SELECT e.id,e.title,e.event_date,e.venue,(SELECT COUNT(*) FROM participation WHERE exhibition_id=e.id) AS count FROM exhibitions e WHERE e.title LIKE ? ESCAPE '!' OR e.venue LIKE ? ESCAPE '!' ORDER BY e.event_date DESC";
    $params = [$like,$like];
}
$rows = sql($query,$params)->fetchAll();
