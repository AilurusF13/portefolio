<?php
require_once "assets/locales/trad.php";
?>
<footer id="footer-section">
    <div class="footer-container">
        <!-- Section Contact -->
        <div class="contact-info footer-part">
            <h3>Contact</h3>
            <p>Email : <a href="mailto:&#102;&#114;&#97;&#110;&#99;&#107;&#46;&#114;&#101;&#100;&#104;&#111;&#111;&#100;&#64;&#103;&#109;&#97;&#105;&#108;&#46;&#99;&#111;&#109;">
    &#102;&#114;&#97;&#110;&#99;&#107; [at] gmail [dot] com
</a></p>
            <p>GitHub :<a href="https://github.com/fschoenfelder113" target="_blank">github.com/fschoenfelder113</a></p>
        </div>
        <!-- Section Glossaire & Ressources -->
        <div class="glossary footer-part">
            <h3><?= $t["footer"]["glossary"] ?></h3>
            <ul>
                <li><a href="https://developer.mozilla.org/fr/" target="_blank"><?= $t["footer"]["mdn"]["name"] ?></a> –<?= $t["footer"]["mdn"]["name"] ?></li>
                <li><a href="https://www.w3schools.com/" target="_blank"><?= $t["footer"]["w3s"]["name"] ?></a> –<?= $t["footer"]["w3s"]["descr"] ?></li>
                <li><a href="https://wireframe.cc/" target="_blank"><?= $t["footer"]["wireframe"]["name"]?></a> -<?= $t["footer"]["wireframe"]["descr"]?></li>
                <li><p><?= $t["footer"]["copilot"]?></p></li>
            </ul>
        </div>

        <!-- Section Mention Légale -->
        <div class="legal footer-part">
            <h3><?= $t["footer"]["legs"]?></h3>
            <p><?= $t["footer"]["cr"]?></p>
        </div>
    </div>
</footer>
