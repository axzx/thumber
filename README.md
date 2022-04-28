## Informacje
Przykładowe zdjęcia są w katalogu `photos`.

W środowisku `dev`, miniaturki będą tworzone w katalogu `public/media/cache`.
W środowisku `prod`, miniaturki będą zapisywane w usłudze AWS S3.

## Komendy

* `app:generate-thumb <filepath> [<filter>]`

Wygenerowanie miniaturki dla konkretnego pliku i konkretnego filtru (rozmiaru).

Przykład użycia:

`./bin/console app:generate-thumb photos/image1.jpg min`

`./bin/console app:generate-thumb photos/image2.jpg min_premium`

* `app:user:generate-thumb`

Wygenerowanie miniaturek dla wszystkich userów, którzy mają ustawione zdjęcie.

Dla userów oznaczonych jako premium zostanie wykorzystany filtr `min_premium`,
a dla pozostałych `min`.

## Filtry do zmiany obrazków
- `min` - miniaturka o rozmiarze max 150-150px
- `min_premium` - miniaturka o rozmiarze max 300-300px

## API
* POST `/api/v1/generate/thumb`
 
Przesyłana zawartość powinna zawierać `filepath` i `filter`.
Przykładowa zawartość:
```json
{
    "filepath": "photos/image1.jpg", 
    "filter": "min"
}
```
Kod 200 odpowiedzi oznacza, że zadanie zostało dodane do kolejki.
