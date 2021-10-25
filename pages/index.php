<h1>INDEX</h1>
<ul>
    <?php
    $sql_domains = 'SELECT * FROM `web_domain`';
    $domains = array(array(), array());
    foreach ($GLOBALS['MySQL']->query_select($sql_domains) as $domain_config) {
        ?>
        <li>
            <?= $domain_config['domain'] ?>
        </li>
        <?php
    }
    ?>
</ul>
