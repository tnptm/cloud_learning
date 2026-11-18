
# Julkaisu VPS-palvelimelle GitHub Actionsilla

[Julkaisutyönkulku](.github/workflows/deploy.yml) kopioi sivuston tiedostot
SSH:n ja rsyncin kautta Debian-palvelimelle, kun `master`-haaraan tulee muutos.
Pull requestin yhdistäminen `master`-haaraan käynnistää sen; myös suora push
käynnistäisi julkaisun. Suojaa siksi `master` GitHubin branch protection
-säännöllä tai rulesetillä: vaadi pull request ja sen katselmointi sekä estä
suorat pushit. Työnkulku ei aja testejä.

Julkaisu päivittää tiedostot suoraan kohdehakemistoon, ei atomisesti. Se ei
poista palvelimelta tiedostoja, jotka on poistettu repositoriosta. Tarkista
vanhentuneet tiedostot ja varmuuskopiot itse; älä käytä samaa hakemistoa
salaisuuksille, käyttäjien latauksille tai muulle sivustolle. Nginxiä ei
käynnistetä uudelleen eikä sen asetuksia muuteta.

## 1. Valmistele palvelin

1. Varmista, että Nginx ja PHP-FPM jo toimivat ja että `rsync` sekä
   OpenSSH-palvelin ovat asennettuina. Työnkulku edellyttää, että molemmat
   käynnistyvät ja ovat saavutettavissa GitHubin ylläpitämiltä ajajilta.
2. Luo erillinen kirjautumiskäyttäjä, esimerkiksi `deploy`, ilman sudo- tai
   muita tarpeettomia oikeuksia. Alla oleva esimerkki luo uuden käyttäjän ja
   uuden, vain tälle sivustolle tarkoitetun hakemiston:

   ```sh
   sudo adduser --disabled-password --gecos "" deploy
   sudo install -d -o deploy -g deploy -m 755 /var/www/pilvikurssit
   ```

   Jos käyttäjä tai hakemisto on jo olemassa, tarkista niiden omistajuus ja
   oikeudet ensin. Käytä omaan palvelimeesi sopivia nimiä ja polkua.
3. Määritä Nginxin sivuston `root` osoittamaan kyseiseen hakemistoon ja
   varmista, että Nginxin käyttäjä voi lukea tiedostot. Käytä esimerkiksi
   `index course-index.php;`, jos haluat etusivun avautuvan juuresta.
   Varmista olemassa olevasta PHP-FPM-asetuksesta, että `.php`-pyynnöt
   suoritetaan eivätkä paljasta PHP-lähdekoodia. Jos muutat Nginxin asetuksia,
   tarkista ne komennolla `sudo nginx -t` ja lataa ne uudelleen oman
   palvelimesi käytännön mukaan.
4. Varmista, että palomuuri sallii SSH-yhteydet GitHub Actionsilta. GitHubin
   ylläpitämien ajajien IP-osoitteet vaihtuvat; tarvittaessa käytä niiden
   [virallista IP-listaa](https://docs.github.com/en/actions/reference/runners/github-hosted-runners#ip-addresses)
   tai omaa turvallisesti verkkoon sijoitettua ajajaa. Älä poista palomuuria
   tai avaa SSH:ta tarpeettomasti.

## 2. Luo ja asenna SSH-avain

Luo **omalla koneellasi** tätä julkaisua varten erillinen avainpari:

```sh
ssh-keygen -t ed25519 -f ~/.ssh/pilvikurssit_deploy -C github-actions-pilvikurssit -N ''
```

Komento luo automaatiolle salasanattoman yksityisavaimen ja `.pub`-päätteisen
julkisen avaimen. Älä korvaa olemassa olevaa avainta. Pidä yksityisavain
salassa, älä tallenna sitä repositorioon tai näytä sitä lokissa. Lisää vain
julkinen avain VPS:n `deploy`-käyttäjän `~/.ssh/authorized_keys`-tiedostoon
turvallisen olemassa olevan hallintayhteyden kautta. Varmista, että kyseinen
käyttäjä voi kirjoittaa vain tarvitsemiinsa kohteisiin. Avaimen mahdollinen
vuoto antaa sen haltijalle kyseisen käyttäjän oikeudet; poista tai vaihda
avain heti, jos epäilet vuotoa.

## 3. Vahvista palvelimen SSH-isäntäavain

Työnkulku tarkistaa palvelimen identiteetin eikä hyväksy tuntematonta avainta.
Hanki VPS:n SSH-isäntäavaimen sormenjälki luotettavaa, erillistä reittiä
pitkin, esimerkiksi palveluntarjoajan konsolista:

```sh
sudo ssh-keygen -lf /etc/ssh/ssh_host_ed25519_key.pub
```

Kerää omalla koneellasi saman avaintyypin `known_hosts`-rivi käyttäen oikeaa
palvelimen nimeä ja porttia (korvaa esimerkit):

```sh
ssh-keyscan -p 22 -t ed25519 vps.example.com > vps_known_hosts
ssh-keygen -lf vps_known_hosts
```

Vertaa sormenjälkiä. **Älä käytä riviä, jos ne eivät täsmää**: verkosta haettu
avain ei yksin todista palvelimen identiteettiä. Muulla kuin portilla 22
`known_hosts`-rivillä on yleensä muoto `[vps.example.com]:PORTTI`. Jos
palvelimen isäntäavain myöhemmin vaihtuu, varmista uusi sormenjälki samalla
tavalla ennen GitHub-asetuksen päivittämistä.

Testaa yhteys ja kohdehakemiston kirjoitusoikeus omalta koneeltasi samoilla
arvoilla. Tämä komento käyttää vain äsken vahvistettua `known_hosts`-tiedostoa:

```sh
ssh -i ~/.ssh/pilvikurssit_deploy -p 22 \
  -o IdentitiesOnly=yes -o StrictHostKeyChecking=yes \
  -o UserKnownHostsFile=./vps_known_hosts \
  deploy@vps.example.com 'test -d /var/www/pilvikurssit && test -w /var/www/pilvikurssit'
```

Komento onnistuu ilman tulostetta. Korvaa käyttäjä, osoite, portti ja polku
omillasi. Tarkista lisäksi, että `rsync` on käytettävissä palvelimella.

## 4. Lisää asetukset GitHubiin

Luo repositorion **Settings → Environments** -sivulla ympäristö nimeltä
`production`. Lisää sinne seuraavat **environment variables** -arvot
(esimerkit eivät ole oikeita tunnuksia):

| Muuttuja | Esimerkki | Merkitys |
|---|---|---|
| `VPS_HOST` | `vps.example.com` | SSH-palvelimen nimi tai IPv4-osoite |
| `VPS_PORT` | `22` | SSH-portti |
| `VPS_USER` | `deploy` | Julkaisukäyttäjä |
| `VPS_DEPLOY_PATH` | `/var/www/pilvikurssit` | Olemassa oleva sivuston juurihakemisto |

Polun on oltava absoluuttinen eikä se saa olla `/`; yksinkertaisuuden vuoksi
työnkulku hyväksyy vain tavalliset hakemistonimet (kirjaimet, numerot, `_`,
`-` ja `.`), ei kuitenkaan pisteellä alkavia nimiä tai `..`-merkintää. Lisää saman
ympäristön **environment secrets** -osioon:

| Secret | Sisältö |
|---|---|
| `DEPLOY_SSH_KEY` | Koko `~/.ssh/pilvikurssit_deploy`-yksityisavaimen sisältö, myös alku- ja loppurivit |
| `DEPLOY_KNOWN_HOSTS` | Edellä sormenjäljellä vahvistettu `vps_known_hosts`-rivi |

Syötä yksityisavain vain GitHubin Secrets-käyttöliittymään, ei repositoryyn,
muuttujiin, PR-kommenttiin tai lokiin. Voit rajata `production`-ympäristön
vain `master`-haaraan ja vaatia julkaisulle hyväksynnän, jos GitHub-pakettisi
tukee näitä ympäristön suojaussääntöjä. Rajoita myös sitä, ketkä voivat
muuttaa työnkulkua ja ympäristön salaisuuksia.

## 5. Julkaise ja tarkista

Yhdistä työnkulun sisältävä pull request `master`-haaraan. Seuraa sen jälkeen
repositoriossa **Actions → Deploy to VPS** -ajoa. Jos käytössä on ympäristön
hyväksyntä, hyväksy odottava julkaisu. Avaa sivuston osoite tai
`/course-index.php` ja tarkista myös materiaalilinkit ja CSS. Ensimmäistä
julkaisua varten Nginxin `root`-hakemiston on vastattava `VPS_DEPLOY_PATH`ia.

Jos ajo epäonnistuu, tarkista ensin Actions-lokista virhe, ympäristön muuttujat
ja salaisuudet, SSH-portti, palvelimen vahvistettu isäntäavain sekä
`deploy`-käyttäjän oikeudet kohdehakemistoon. Nginx- tai PHP-FPM-ongelmissa
tarkista palvelimen omat lokit. Julkaisu kopioi vain työnkulussa luetellut
sivustotiedostot ja `css`-hakemiston; lisää mahdolliset uudet julkaistavat
tiedostot myös työnkulun rsync-komentoon.