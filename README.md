# Instrukcja wdrozenia TODO App na Azure

## Wymagania
- Konto Azure z aktywna subskrypcja
- Publiczne repozytorium GitHub z kodem aplikacji

---

## Krok 1: Utworzenie Resource Group

1. Zaloguj sie do [Azure Portal](https://portal.azure.com)
2. Wyszukaj **Resource groups** w pasku wyszukiwania
3. Kliknij **Create**
4. Wypelnij:
   - **Subscription**: Twoja subskrypcja
   - **Resource group**: `todo-app-rg`
   - **Region**: `West Europe`
5. Kliknij **Review + create** → **Create**

---

## Krok 2: Utworzenie MySQL Flexible Server

1. Wyszukaj **Azure Database for MySQL** w pasku wyszukiwania
2. Kliknij **Create** → **Flexible Server**
3. Wypelnij zakladke **Basics**:
   - **Resource group**: `todo-app-rg`
   - **Server name**: `todo-mysql-TWOJANAZWA` (unikalna nazwa)
   - **Region**: `West Europe`
   - **Workload type**: `For development or hobby projects` (najtansza)
   - **Compute + storage**: Kliknij **Configure server**
     - **Compute tier**: `Burstable`
     - **Compute size**: `Standard_B1ms` (1 vCore, najtansza)
     - Kliknij **Save**
   - **Authentication method**: `MySQL authentication`
   - **Admin username**: `adminuser`
   - **Password**: Wymysl silne haslo (zapisz je!)
4. Przejdz do zakladki **Networking**:
   - **Connectivity method**: `Public access`
   - Zaznacz **Allow public access from any Azure service within Azure**
   - Kliknij **Add current client IP address**
5. Kliknij **Review + create** → **Create**
6. Poczekaj ok. 5-10 minut na utworzenie serwera

---

## Krok 3: Utworzenie bazy danych

1. Po utworzeniu serwera, przejdz do niego
2. W menu bocznym wybierz **Databases**
3. Kliknij **Add**
4. Wpisz nazwe: `tododb`
5. Kliknij **Save**

---

## Krok 4: Utworzenie tabeli (Azure Cloud Shell)

1. W Azure Portal kliknij ikone terminala (>_) w gornym pasku → **Azure Cloud Shell**
2. Wybierz **Bash**
3. Polacz sie z baza danych:
   ```bash
   mysql -h todo-mysql-TWOJANAZWA.mysql.database.azure.com -u adminuser -p
   ```
4. Wpisz haslo gdy zostaniesz poproszony
5. Po polaczeniu wykonaj komendy:
   ```sql
   USE tododb;

   CREATE TABLE todos (
       id INT AUTO_INCREMENT PRIMARY KEY,
       title VARCHAR(255) NOT NULL,
       is_completed TINYINT(1) DEFAULT 0,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   exit;
   ```

---

## Krok 5: Utworzenie App Service

1. Wyszukaj **App Services** w pasku wyszukiwania
2. Kliknij **Create** → **Web App**
3. Wypelnij zakladke **Basics**:
   - **Resource group**: `todo-app-rg`
   - **Name**: `todo-app-TWOJANAZWA` (unikalna nazwa, bedzie URL)
   - **Publish**: `Code`
   - **Runtime stack**: `PHP 8.3` (lub najnowsza dostepna)
   - **Operating System**: `Linux`
   - **Region**: `West Europe`
   - **Pricing plan**: Kliknij **Create new**
     - Wybierz **Free F1** (darmowy) lub **Basic B1** (platny ale lepszy)
4. Kliknij **Review + create** → **Create**

---

## Krok 6: Konfiguracja zmiennych srodowiskowych

1. Przejdz do utworzonego App Service
2. W menu bocznym wybierz **Settings** → **Environment variables**
3. W zakladce **App settings** kliknij **Add** dla kazdej zmiennej:

   | Name | Value |
   |------|-------|
   | `DB_HOST` | `todo-mysql-TWOJANAZWA.mysql.database.azure.com` |
   | `DB_NAME` | `tododb` |
   | `DB_USER` | `adminuser` |
   | `DB_PASS` | `TWOJE_HASLO_DO_MYSQL` |

4. Kliknij **Apply** na dole strony
5. Potwierdz restart aplikacji

---

## Krok 7: Deployment z GitHub

1. W App Service przejdz do **Deployment** → **Deployment Center**
2. W zakladce **Settings**:
   - **Source**: `GitHub`
   - Kliknij **Authorize** i zaloguj sie do GitHub
   - **Organization**: Twoja nazwa uzytkownika
   - **Repository**: Wybierz repozytorium z kodem
   - **Branch**: `main`
3. Kliknij **Save**
4. Azure automatycznie pobierze kod i uruchomi aplikacje

---

## Krok 8: Sprawdzenie aplikacji

1. W App Service przejdz do **Overview**
2. Kliknij link w sekcji **Default domain** (np. `https://todo-app-TWOJANAZWA.azurewebsites.net`)
3. Aplikacja powinna sie wyswietlic

---

## Rozwiazywanie problemow

### Blad polaczenia z baza danych
1. Sprawdz czy zmienne srodowiskowe sa poprawne
2. W MySQL Server → **Networking** dodaj regule firewalla:
   - Start IP: `0.0.0.0`
   - End IP: `255.255.255.255`

### Strona nie dziala / bialy ekran
1. App Service → **Diagnose and solve problems**
2. App Service → **Log stream** - sprawdz logi na zywo

### Deployment nie dziala
1. Deployment Center → **Logs** - sprawdz status
2. Upewnij sie ze branch `main` istnieje w repozytorium

---

## Koszty (szacunkowo)

| Usluga | Tier | Koszt miesiecznie |
|--------|------|-------------------|
| App Service | Free F1 | $0 |
| App Service | Basic B1 | ~$13 |
| MySQL Flexible | Burstable B1ms | ~$6-12 |

**Uwaga**: Darmowy tier App Service ma ograniczenia (60 min CPU/dzien, brak custom domain).

---

## Usuniecie zasobow

Aby uniknac oplat, usun cala Resource Group:
1. Resource groups → `todo-app-rg`
2. Kliknij **Delete resource group**
3. Wpisz nazwe grupy dla potwierdzenia
4. Kliknij **Delete**
