<?php
$courses = [
    [
        'number' => '01',
        'category' => 'AWS · DEVOPS',
        'title' => 'AWS DevOps -pikakurssi',
        'description' => 'Tutustu AWS:n keskeisiin palveluihin ja rakenna pilvi-infrastruktuuria Terraformilla. Mukana kontit, verkot, tietoturva ja CI/CD.',
        'topics' => ['AWS', 'Terraform', 'CI/CD'],
        'href' => 'aws-devops-kurssi.html',
        'action' => 'Avaa pikakurssi',
    ],
    [
        'number' => '02',
        'category' => 'AWS · OPPIMISPOLKU',
        'title' => 'Selkeytetty AWS DevOps -kurssi',
        'description' => 'Etene tavoitteiden ja suositellun opiskelujärjestyksen avulla AWS:n perusteista syventäviin aiheisiin ja kokoavaan harjoitukseen.',
        'topics' => ['Oppimistavoitteet', 'Harjoitukset', 'Sanasto'],
        'href' => 'aws-devops-kurssi-selkeytetty.html',
        'action' => 'Avaa oppimispolku',
    ],
    [
        'number' => '03',
        'category' => 'INFRASTRUCTURE AS CODE',
        'title' => 'Terraform-opetusmateriaali',
        'description' => 'Opi Terraformin syntaksi, komennot ja käytännön työnkulku. Perehdy myös tilanhallintaan, moduuleihin ja harjoitustehtäviin.',
        'topics' => ['HCL', 'State', 'Harjoitukset'],
        'href' => 'terraform-opetusmateriaali.html',
        'action' => 'Avaa Terraform-materiaali',
    ],
];

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Suomenkieliset AWS DevOps- ja Terraform-opetusmateriaalit yhdessä paikassa.">
    <title>Pilvikurssit | AWS &amp; Terraform</title>
    <link rel="stylesheet" href="css/course-index.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="course-index.php"><span class="brand-mark" aria-hidden="true">✦</span> PILVIKURSSIT</a>
            <a class="header-link" href="#materiaalit">Tutustu materiaaleihin <span aria-hidden="true">↗</span></a>
        </div>
    </header>

    <main>
        <section class="hero container" aria-labelledby="hero-title">
            <div class="hero-content">
                <p class="eyebrow"><span class="status-dot"></span> SUOMENKIELISET OPPIMATERIAALIT</p>
                <h1 id="hero-title">Pilveen, <span>askel kerrallaan.</span></h1>
                <p class="hero-description">Opi AWS:n ja Terraformin perusteet käytännönläheisten materiaalien avulla. Valitse itsellesi sopiva polku ja rakenna osaamistasi omaan tahtiisi.</p>
                <a class="primary-button" href="#materiaalit">Selaa materiaaleja <span aria-hidden="true">↓</span></a>
            </div>
            <div class="hero-visual" aria-hidden="true">
                <div class="orbit orbit-one"></div>
                <div class="orbit orbit-two"></div>
                <div class="cloud-symbol">☁</div>
                <div class="visual-label label-aws">AWS <span>→</span></div>
                <div class="visual-label label-iac">IaC <span>✦</span></div>
                <div class="visual-caption">OPI · RAKENNA · KEHITÄ</div>
            </div>
        </section>

        <section class="materials" id="materiaalit" aria-labelledby="materials-title">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">ALOITA TÄSTÄ</p>
                        <h2 id="materials-title">Valitse oppimispolkusi</h2>
                        <p>Kolme materiaalia pilviosaamisen tueksi, perusteista käytäntöön.</p>
                    </div>
                    <span class="section-count">01 — 03</span>
                </div>
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                    <article class="course-card">
                        <div class="card-top">
                            <span class="card-icon" aria-hidden="true">✳</span>
                            <span class="card-number"><?= escape($course['number']) ?></span>
                        </div>
                        <p class="card-category"><?= escape($course['category']) ?></p>
                        <h3><?= escape($course['title']) ?></h3>
                        <p class="card-description"><?= escape($course['description']) ?></p>
                        <ul class="tags" aria-label="Aiheet">
                            <?php foreach ($course['topics'] as $topic): ?>
                            <li><?= escape($topic) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a class="card-link" href="<?= escape($course['href']) ?>"><?= escape($course['action']) ?> <span aria-hidden="true">↗</span></a>
                    </article>
                    <?php endforeach; ?>
                </div>
                <aside class="notice">
                    <span class="notice-icon" aria-hidden="true">!</span>
                    <p><strong>Ennen kuin aloitat.</strong> AWS-harjoitukset voivat aiheuttaa kustannuksia. Tarkista hinnat, suojaa tunnuksesi ja poista tarpeettomat resurssit harjoittelun jälkeen.</p>
                </aside>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p><strong>✦ PILVIKURSSIT</strong><br>© 2026 Toni Patama</p>
            <p>Oppimateriaalit: <a href="https://creativecommons.org/licenses/by/4.0/">CC BY 4.0</a><br>Koodi: <a href="LICENSE">MIT-lisenssi</a></p>
        </div>
    </footer>
</body>
</html>