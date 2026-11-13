<?php
require __DIR__.'/bootstrap.php';
require __DIR__.'/artwork-fields.php';
$user = require_user();
$error = null;
$key = id($_GET['edit'] ?? null);
$edit = $key ? sql(ART_QUERY.' WHERE a.id=? AND a.user_id=?',[$key,$user['id']])->fetch() : null;
if ($key && !$edit) { http_response_code(404); exit('Artwork not found.'); }
if (post()) {
    check_csrf();
    try {
        $target = id(val('id'));
        if (val('action') === 'delete') {
            $stmt = sql('DELETE FROM artworks WHERE id=? AND user_id=?',[$target,$user['id']]);
            if (!$stmt->rowCount()) throw new DomainException('Artwork not found.');
            flash('Artwork deleted.');
        } elseif (val('action') === 'edit' && $edit && $target === $key) {
            $fields = artwork_fields();
            sql("UPDATE artworks SET title=?,category_id=?,description=?,materials=?,status='pending',featured=0 WHERE id=? AND user_id=?",
                [...$fields,$target,$user['id']]);
            flash('Changes submitted for review.');
        } else throw new DomainException('Artwork not found.');
        go('myartwork.php');
    } catch (DomainException | PDOException $exception) { http_response_code(422); $error = form_error($exception); }
}
$artworks=sql(ART_QUERY.' WHERE a.user_id=? ORDER BY a.id DESC',[$user['id']])->fetchAll();
$counts=array_count_values(array_column($artworks,'status'));
