## Instrukcje dotyczące korzystania z roster'a oraz jego instalacji

Witaj w demo witryny roster! To miejsce jest stworzone w celu demonstracji funkcji związanych z zarządzaniem artystami, utworami muzycznymi oraz wytwórniami płytowymi. Możesz wykorzystać tę witrynę do przechowywania informacji o utworach, takich jak linki do usług streamingowych, kody ISRC i UPC oraz pliki dźwiękowe.

### Wymagania wstępne

Aby w pełni korzystać z funkcji witryny, musisz dokonać kilku dostosowań.

### Konfiguracja powiadomień e-mail

Aby umożliwić powiadomienia e-mail, proszę dokonać następujących zmian:

1. Otwórz pliki `/management/roster.php`, `/management/artist.php` oraz `/support/send-email.php`.

2. W każdym z tych plików, znajdź linię zawierającą kod:
   ```php
   $apiKey = "sendgrid api key";

i zastąp "sendgrid api key" swoim kluczem API SendGrid.

### Konfiguracja logowania administratora
Aby umożliwić logowanie administratora, wykonaj następujące kroki:

W repozytorium znajdziesz plik SQL o nazwie roster.sql. Zaimplementuj ten plik w swojej bazie danych.

Otwórz plik /admin/admin.php i znajdź linię z kodem:

   ```php
$pdo = new PDO('mysql:host=localhost;dbname=nazwa_databasea', 'użytkownik', 'hasło');
```

Zastąp localhost nazwą hosta, nazwa_databasea nazwą twojej bazy danych, użytkownik nazwą użytkownika i hasło hasłem dostępu do bazy danych.

## Logowanie jako Administrator (Logowanie jako administrator)

Aby zalogować się jako administrator i uzyskać dostęp do różnych narzędzi zarządzania, postępuj zgodnie z poniższymi krokami:

1. Otwórz przeglądarkę internetową i odwiedź stronę logowania administratora, przechodząc pod adres **twojastrona.pl/admin**.

2. Wprowadź swoje dane logowania oraz hasło, które powinny być zgodne z danymi dostępu ustawionymi w bazie danych SQL.

3. Po zalogowaniu będziesz mieć dostęp do różnych narzędzi, w tym zarządzania artystami, zarządzania wytwórnią płytową oraz zarządzania utworami muzycznymi.

Ciesz się korzystaniem z funkcji administratora na stronie!


### Testowanie
Możesz przetestować witrynę demonstracyjną, odwiedzając adres [prezentacja.clippsly.com](prezentacja.clippsly.com).

### Informacje dodatkowe
Witryna roszad została stworzona w celach informacyjnych dotyczących artystów, utworów muzycznych oraz wytwórni płytowych. Zawiera również system wsparcia oparty na PHP i API SendGrid, który umożliwia wysyłanie e-maili zarówno do klientów, jak i na adres wsparcia.

Niektóre zasoby graficzne mogą być hostowane na naszym zewnętrznym serwerze (cdn.clippsly.com). Możesz zastąpić te zasoby własnymi, ponieważ projekt jest open source.
