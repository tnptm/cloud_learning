# Pilvikurssit

[![Deploy to VPS](https://github.com/tnptm/cloud_learning/actions/workflows/deploy.yml/badge.svg)](https://github.com/tnptm/cloud_learning/actions/workflows/deploy.yml)

Suomenkielisiä, tekoälyavusteisesti kehitettyjä oppimateriaaleja AWS:stä,
DevOpsista ja Terraformista. Materiaalit kokoavat käsitteitä, käytännön
esimerkkejä ja harjoituksia itsenäisen opiskelun tueksi.

## Materiaalit

- [AWS DevOps -pikakurssi](aws-devops-kurssi.html): AWS:n keskeiset palvelut,
  Terraform, kontit, tietoturva ja CI/CD.
- [Selkeytetty AWS DevOps -kurssi](aws-devops-kurssi-selkeytetty.html):
  pikakurssin oppimistavoitteilla, opiskelujärjestyksellä, arkkitehtuurin
  perusteilla, palveluvalintojen harjoituksilla ja sanastolla täydennetty versio.
- [Terraform-opetusmateriaali](terraform-opetusmateriaali.html): HCL-syntaksi,
  resurssit, tilanhallinta, komennot ja käytännön harjoitukset.

## Käyttö

Materiaalit voi avata suoraan selaimessa HTML-tiedostoina. PHP-pohjainen
[etusivu](course-index.php) kokoaa materiaalit yhteen. Sen käyttämiseen
tarvitaan PHP; muita riippuvuuksia ei ole. Käynnistä paikallinen palvelin
projektin juurihakemistossa [käynnistysskriptillä](start-server.sh):

```sh
./start-server.sh
```

Siirry osoitteeseen <http://127.0.0.1:8000/course-index.php>. Palvelimen voi
pysäyttää painamalla Ctrl+C. CSS-tiedostot ovat [css-hakemistossa](css/).

Katso [DEPLOY.md](DEPLOY.md).

## Ennen harjoituksia

AWS-resurssien käyttö voi aiheuttaa kustannuksia. Tarkista ajantasaiset hinnat
ja ilmaistason ehdot AWS:n dokumentaatiosta, suojaa tunnukset ja salaisuudet
sekä poista tarpeettomat resurssit harjoitusten jälkeen. Esimerkit ovat
opetuskäyttöön: tarkista niiden turvallisuus ja ajantasaisuus ennen käyttöä
omassa ympäristössäsi.

## Tekijänoikeus ja lisenssit

Copyright © 2026 Toni Patama.

- Oppimateriaalit (HTML-sivujen teksti, esimerkit ja harjoitukset) on
  lisensoitu [Creative Commons Nimeä 4.0 Kansainvälinen -lisenssillä
  (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/). Lisenssin
  [virallinen oikeudellinen teksti](LICENSE-CC-BY-4.0.txt) on mukana.
  Mainitse tekijä, linkitä lisenssiin ja kerro mahdollisista muutoksista.
- Projektin lähdekoodi, mukaan lukien PHP, CSS ja mahdollinen JavaScript, on
  lisensoitu [MIT-lisenssillä](LICENSE).

Lisenssit koskevat projektin omaa aineistoa; kolmansien osapuolten nimet,
tavaramerkit ja mahdollinen erikseen lisensoitu aineisto kuuluvat niiden
oikeudenhaltijoille. AWS ja Terraform ovat omistajiensa tavaramerkkejä.


