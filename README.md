# proc-track

Aplikacja do monitorowania i śledzenia procesów systemowych w czasie rzeczywistym. Zbiera dane z systemu plików `/proc`, agreguje je co 10 sekund i przesyła do bazy danych przez kolejkę wiadomości.

## Stos technologiczny

- **PHP 8.2+** / **Symfony 7.4**
- **Doctrine ORM** + MariaDB
- **Symfony Messenger** + Redis (kolejka wiadomości)
- **groff** (generowanie raportów PDF)
- **Docker** (opcjonalnie)

## Wymagania

- PHP >= 8.2
- MariaDB
- Redis
- groff
- Composer

## Instalacja

```bash
git clone <repo-url>
cd proc-track
composer install
cp .env.example .env
```

Uzupełnij `.env` swoimi danymi (połączenie z bazą, Redis).

### Migracje

```bash
php bin/console doctrine:migrations:migrate
```

## Uruchomienie

### Kolektor procesów

Zbiera dane z `/proc` co 10 sekund i wysyła je do kolejki:

```bash
php bin/console app:collector
```

### Worker kolejki

Przetwarza wiadomości z kolejki i zapisuje do bazy:

```bash
php bin/console messenger:consume async
```

## Raporty

Raporty generowane są do formatu PDF przy użyciu **groff**. Generator (`GroffPdfGenerator`) produkuje dokument na podstawie zebranych metryk procesów.

## Struktura projektu

```
src/
├── Collector/   # Parsowanie /proc, agregacja snapshotów
├── Processor/   # Obsługa wiadomości z kolejki
├── Report/      # Generowanie raportów (groff → PDF)
├── Entity/      # Encje Doctrine (Process, Metric)
└── Repository/  # Repozytoria
```

## Konfiguracja środowisk

| Plik | Przeznaczenie |
|------|---------------|
| `.env` | Domyślna konfiguracja (nie commitować z prawdziwymi sekretami) |
| `.env.local` | Lokalne nadpisania (ignorowane przez git) |
| `.env.example` | Wzorzec do skopiowania |
