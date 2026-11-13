<?php
function artwork_fields(): array {
    $title=val('title'); $category=id(val('category')); $description=val('description'); $materials=val('materials');
    if (!valid_text($title,150) || !valid_text($description,3000) || !valid_text($materials,180,false)) {
        throw new DomainException('Enter a title (up to 150 characters), a description (up to 3,000 characters), and optional materials (up to 180 characters).');
    }
    if (!$category || !sql('SELECT id FROM categories WHERE id=?',[$category])->fetchColumn()) throw new DomainException('Choose an available category.');
    return [$title,$category,$description,$materials];
}
