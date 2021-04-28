<?php 
$currentpage = "info";
include('src/php/header.php');

?>

<div class="container main-content">
    <h1>DHBW Social</h1>
    <p>
        Willkommen zu unserem PHP-Projekt "PHP-Social" - Eine Abwandlung der bekannten Social-Media Plattform Twitter.
        </br>
        Benutzerhandbuch <a href="assets/pdfs/manual.pdf" download="Benutzerhandbuch">herunterladen</a>
    </p>
    <h2>Contributors:</h2>
    <ul>
        <li><a href="https://github.com/NamidM" target="_blank">Namid Faro Marxen</a></li>
        <li><a href="https://github.com/UdolfSeelenfrost" target="_blank">Johannes Emanuel Timter</a></li>
        <li><a href="https://github.com/NurNils" target="_blank">Nils-Christopher Wiesenauer</a></li>
    </ul>

    <h2>Über das Projekt</h2>
    <p>
        Das Projekt wurde von DHBW Studenten im 3. Semester im Rahmen der Vorlesung "PHP" entwickelt.
        Es dient als kleine Abwandlung der Plattform "Twitter" zum Erweitern unserer PHP-Skills. 
        Welche Funktionen insgesamt umgesetzt wurden folgen im unserem vordefinierten Feature-Set:
    </p>
    <p>
        <strong>Must have</strong>
        <ul>
            <li>
                Login</br>
                Wenn der Benutzer bereits registriert ist, kann er sich einloggen und bleibt eingeloggt
            </li>
            <li>
                Register</br>
                Benutzer werden die Möglichkeit haben sich mit einem Namen, einer Mail und einem Passwort registrieren zu können. Dabei muss er seine Mail bestätigen
            </li>
            <li>
                Feed-Seite</br>
                Es wird eine Seite geben, auf der die neusten Posts von allen Usern angezeigt
                werden denen man folgt, ansonsten werden andere interessante Posts angezeigt
            </li>
            <li>
                Profil-Seite einstellen</br>
                Der Benutzer soll ein Profilbild hochladen können und eine Profilbeschreibung hinzufügen können
            </li>
            <li>
                Posts erstellen</br>
                Ein User kann ein Bild oder Video (oder keins von beiden) mit Text posten, dafür gibt es eine eigene Seite für jeden Post
            </li>
            <li>
                Likes/Dislikes</br>
                Benutzer können mit dem Klicken auf zwei verschiedene Icons (Daumen hoch und Daumen runter) einem Post entweder ein Like oder Dislike vergeben
            </li>
            <li>
                Hashtag-System</br>
                Posts erhalten selbstdefinierte Kategorien mithilfe von Hashtags. Dadurch ist es möglich, Posts mithilfe von Hashtags zu finden
            </li>
            <li>
                Kommentare Schreiben</br>
                Benutzer können Kommentare unter einem Post verfassen
            </li>
            <li>
                Info-Seite</br>
                Es sollte eine kleine Seite geben auf der das ganze System erklärt wird
            </li>
        </ul>
        <strong>Nice to have</strong>
        <ul>
            <li>
                Passwort Reset</br>
                Wenn der Nutzer sein Passwort vergessen hat, kann er durch “Passwort vergessen” eine E-mail an seine Adressen erhalten. In der Mail steht ein Link zu einer Seite, auf der der Nutzer sein neues Passwort vergeben kann.
            </li>
            <li>
                Privatchat</br>
                Benutzer sollen die Möglichkeit haben miteinander zu schreiben, ein Chat kann über das Profil gestartet werden
            </li>
            <li>
                Stories</br>
                Bilder/Video Funktion analog zu Snapchat Stories
            </li>
            <li>
                Personalisierte Userpage</br>
                Hintergrund, Hauptfarbe, Hintergrundmusik beim Öffnen kann gesetzt werden
            </li>
            <li>
                Follower</br>
                Follower-Listen: Wem folge ich und wer folgt mir
            </li>
            <li>
                Notifications</br>
                Mitteilungen über verschiedene Ereignisse: Likes, neuer Follower, Markierungen
            </li>
            <li>
                Admin-User</br>
                Kann Posts löschen
            </li>
            <li>
                User taggen</br>
                Andere Benutzer können auf Posts markiert werden. Getaggter Benutzer erhält
                Notification mit Verweis auf den Post
                </li>
            <li>
                Live-Aktualisierung der Kommentare/Likes</br>
                Wenn man sich auf einem Post befindet (oder im Feed) werden Kommentare und
                Likes ohne neuzuladen aktualisiert
            </li>
            <li>
                Kommentare liken</br>
                Kommentare können von anderen Nutzern geliked werden
            </li>
            <li>
                Light/Darkmode</br>
                Wenn der Nutzer sein Passwort vergessen hat, kann er durch “Passwort vergessen” eine E-mail an seine Adressen erhalten. In der Mail steht ein Link zu einer Seite, auf der der Nutzer sein neues Passwort vergeben kann.
            </li>
            <li>
                Passwort Reset</br>
                Der Benutzer kann zwischen einer hellen und dunklen Ansicht wechseln
            </li>
        </ul>
    </p>
    <h2>Technologien</h2>
    <h3>PHP</h3>
    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
    <h3>MySQL</h3>
    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
    <h3>HTML</h3>
    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
    <h3>CSS</h3>
    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
    <h3>JavaScript</h3>
    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
    <h3>Bootstrap</h3>
    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
</div>

<?php include('src/php/footer.php'); ?>