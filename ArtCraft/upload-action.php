<?php
require __DIR__.'/bootstrap.php';
require __DIR__.'/artwork-fields.php';
$user = require_user();
$error = null;
if (post()) {
    if (!$_POST && !$_FILES) { http_response_code(413); exit('Upload too large. Choose a JPG or PNG under 5 MB.'); }
    check_csrf();
    try {
        $fields = artwork_fields();
        $file = $_FILES['image'] ?? [];
        if (($file['error'] ?? null) !== UPLOAD_ERR_OK || !is_string($file['tmp_name']) || !is_uploaded_file($file['tmp_name']) || filesize($file['tmp_name'])>5*1024*1024) {
            throw new DomainException('Choose a JPG or PNG image up to 5 MB.');
        }
        $info = @getimagesize($file['tmp_name']);
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!$info || ($info['mime'] ?? '') !== $mime || !in_array($mime,['image/jpeg','image/png'],true) || $info[0]*$info[1]>40000000) {
            throw new DomainException('Use a valid JPG or PNG image under 40 megapixels.');
        }
        sql('INSERT INTO artworks (title,category_id,description,materials,user_id,mime,image) VALUES (?,?,?,?,?,?,?)',
            [...$fields,$user['id'],$mime,file_get_contents($file['tmp_name'])]);
        flash('Artwork submitted for review.');
        go('myartwork.php');
    } catch (DomainException | PDOException $exception) {
        http_response_code(422);
        $error = form_error($exception);
    }
}
