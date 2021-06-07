<?php
/**
 * File: info.php
 * Info page with description of the whole project
 *
 * @author NamidM <inf19054@lehre.dhbw-stuttgart.de>
 * @author NurNils <inf19161@lehre.dhbw-stuttgart.de>
 * @author UdolfSeelenfrost <inf19220@lehre.dhbw-stuttgart.de>
 *
 * @copyright Copyright (c) 2021
 */
$currentpage = 'info';
include 'src/php/header.php';
?>

<!-- Info Container -->
<div class="container">
    <!-- Introduction -->
    <h1><img src="assets/images/logo.png" width="50"> DHBW Social</h1>
    <p>
        Willkommen zu unserem PHP-Projekt "DHBW Social" - Eine Abwandlung der bekannten Social-Media Plattform Twitter.
        </br>
        (Benutzerhandbuch <a href="assets/pdfs/manual.pdf" download="Benutzerhandbuch">herunterladen</a>)
    </p>
    <!-- Contributors -->
    <h2>Contributors:</h2>
    <ul>
        <li><a href="https://github.com/NamidM" target="_blank">Namid Faro Marxen</a></li>
        <li><a href="https://github.com/UdolfSeelenfrost" target="_blank">Johannes Emanuel Timter</a></li>
        <li><a href="https://github.com/NurNils" target="_blank">Nils-Christopher Wiesenauer</a></li>
    </ul>
    <!-- Overview -->
    <h2>Über das Projekt</h2>
    <p>
        Das Projekt wurde von DHBW-Studenten im 4. Semester im Rahmen des Wahlfachs "PHP" entwickelt.
        Es dient als kleine Abwandlung der Plattform "Twitter" zum Erweitern der PHP-Skills. 
        Welche Funktionen insgesamt umgesetzt wurden, folgen im folgenden vordefinierten Feature-Set:
    </p>
    <!-- Must have -->
    <p>
        <h4>Must have</h4>
        <ul>
            <li>
                Login ✔️</br>
                Wenn der Benutzer bereits registriert ist, kann er sich einloggen und bleibt eingeloggt
            </li>
            <li>
                Register ✔️</br>
                Benutzer haben die Möglichkeit sich mit einem Benutzername, einer E-Mail und einem Passwort zu registrieren
            </li>
            <li>
                Feed-Seite ✔️</br>
                Es gibt eine Seite, auf der die neusten Posts von allen Usern angezeigt sind, falls man ihnenn folgt, ansonsten werden andere interessante Posts angezeigt
            </li>
            <li>
                Profil-Seite einstellen ✔️</br>
                Der Benutzer kann sein Profilbild, seinen Banner und seine Profilbeschreibung bearbeiten und setzen
            </li>
            <li>
                Posts erstellen ✔️</br>
                Ein User kann ein Bild oder Video (oder keins von beiden) mit Text posten, dafür gibt es eine eigene Seite für jeden Post
            </li>
            <li>
                Likes/Dislikes ✔️</br>
                Benutzer können mit dem Klicken auf zwei verschiedene Icons (Daumen hoch und Daumen runter) einem Post entweder ein Like oder Dislike hinterlassen
            </li>
            <li>
                Hashtag-System ✔️</br>
                Posts erhalten selbstdefinierte Kategorien mithilfe von Hashtags. Dadurch ist es möglich, Posts mithilfe von Hashtags zu finden
            </li>
            <li>
                Kommentare Schreiben ✔️</br>
                Benutzer können Kommentare unter einem Post verfassen
            </li>
            <li>
                Info-Seite ✔️</br>
                Eine Seite, in der alle Features des ganzen Systems gelistet und erklärt sind
            </li>
        </ul>
        <!-- Nice to have -->
        <h4>Nice to have</h4>
        <ul>
            <li>
                Passwort Reset ❌</br>
                Wenn der Nutzer sein Passwort vergessen hat, kann er durch “Passwort vergessen” eine E-Mail an seine Adressen erhalten. In der Mail steht ein Link zu einer Seite, auf der der Nutzer sein neues Passwort vergeben kann.
            </li>
            <li>
                Privatchat ✔️</br>
                Benutzer sollen die Möglichkeit haben, miteinander zu schreiben. Ein Chat kann über das Profil gestartet werden
            </li>
            <li>
                Stories ❌</br>
                Bilder/Video Funktion analog zu Snapchat Stories
            </li>
            <li>
                Personalisierte Userpage ❌</br>
                Hintergrund, Hauptfarbe, Hintergrundmusik beim Öffnen kann gesetzt werden
            </li>
            <li>
                Follower ✔️</br>
                Follower-Listen: Wem folge ich und wer folgt mir
            </li>
            <li>
                Notifications ✔️</br>
                Mitteilungen über verschiedene Ereignisse: Likes, neuer Follower, Markierungen und Antworten
            </li>
            <li>
                Admin-User ✔️</br>
                Kann Posts löschen
            </li>
            <li>
                User taggen ✔️</br>
                Andere Benutzer können auf Posts markiert werden. Getaggter Benutzer erhält Notification mit Verweis auf den Post
                </li>
            <li>
                Live-Aktualisierung der Kommentare/Likes ❌</br>
                Wenn man sich auf einem Post befindet (oder im Feed) werden Kommentare und
                Likes ohne neuzuladen aktualisiert
            </li>
            <li>
                Kommentare liken ✔️</br>
                Kommentare können von anderen Nutzern geliked werden
            </li>
            <li>
                Light/Darkmode ✔️</br>
                Der Benutzer kann zwischen einer hellen und dunklen Ansicht wechseln
           </li>
        </ul>
    </p>
    <!-- Technologies -->
    <h3>Technologien</h3>
    <h4>PHP</h4>
    <p>PHP ist eine Skriptsprache mit einer an C und Perl angelehnten Syntax, die hauptsächlich zur Erstellung dynamischer Webseiten oder Webanwendungen verwendet wird. PHP wird als freie Software unter der PHP-Lizenz verbreitet.</p>
    <h4>MySQL</h4>
    <p>MySQL ist eines der weltweit verbreitetsten relationalen Datenbankverwaltungssysteme. Es ist als Open-Source-Software sowie als kommerzielle Enterpriseversion für verschiedene Betriebssysteme verfügbar und bildet die Grundlage für viele dynamische Webauftritte.</p>
    <h4>HTML</h4>
    <p>Die Hypertext Markup Language ist eine textbasierte Auszeichnungssprache zur Strukturierung elektronischer Dokumente wie Texte mit Hyperlinks, Bildern und anderen Inhalten. HTML-Dokumente sind die Grundlage des World Wide Web und werden von Webbrowsern dargestellt.</p>
    <h4>CSS</h4>
    <p>Cascading Style Sheets ist eine Stylesheet-Sprache für elektronische Dokumente und zusammen mit HTML und JavaScript eine der Kernsprachen des World Wide Webs. Sie ist ein sogenannter „living standard“ und wird vom World Wide Web Consortium beständig weiterentwickelt.</p>
    <h4>JavaScript</h4>
    <p>JavaScript ist eine Skriptsprache, die ursprünglich 1995 von Netscape für dynamisches HTML in Webbrowsern entwickelt wurde, um Benutzerinteraktionen auszuwerten, Inhalte zu verändern, nachzuladen oder zu generieren und so die Möglichkeiten von HTML und CSS zu erweitern.</p>
    <h4>Bootstrap</h4>
    <p>Bootstrap ist ein freies Frontend-CSS-Framework. Es enthält auf HTML und CSS basierende Gestaltungsvorlagen für Typografie, Formulare, Buttons, Tabellen, Grid-Systeme, Navigations- und andere Oberflächengestaltungselemente sowie zusätzliche, optionale JavaScript-Erweiterungen.</p>
    <br>
</div>

<?php include 'src/php/footer.php'; ?>
