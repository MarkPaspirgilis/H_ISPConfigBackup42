<h1>INDEX</h1>
<ul>
    <?php
    foreach (scandir('/var/www/') as $domain) {
        if (!strstr($domain, '/client') && $domain != '.' && $domain != '..') {
            ?>
            <li>
                <?= $domain ?>
            </li>
            <?php
        }
    }
    ?>
</ul>
 