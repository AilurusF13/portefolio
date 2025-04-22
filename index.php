<?php
$myName = "Ailurus" ;
?>

<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porte Folio</title>
    <link rel="icon" type="image/svg" href="assets/images/logoA.svg">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<?php
include_once "assets/php/header.php"; /*Head bar*/
?>

<section id="showcase-section">
    <div class="showcase about-me">
        <div class="me image no-select">
            <img src="assets/images/logo.svg" draggable="false" alt="temporary">
        </div>
        <div class="me resume">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque euismod, nisi eget consectetur finibus, sapien risus gravida neque, ut bibendum erat augue vel justo. Nulla facilisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae
        </div>
        <div class="me info">
            <ul>
                <li>-Franck</li>
                <li>-etudiant a Université de strasbuyrg</li>
                <li>-naissance</li>
                <li>-residence</li>
            </ul>
        </div>
    </div>
    <div class="project-slider">
        <?php
            include_once "assets/php/slider.php";
        ?>

    </div>

</section>

<section id="more-section">
    <?php
        include_once "assets/php/more-section.php";
    ?>
</section>

<?php
include_once "assets/php/footer.php"; /*Section a proro: footer*/
?>
</body>
