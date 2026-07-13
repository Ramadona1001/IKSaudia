# IK Products seeding source

Place product datasheets and images here. The seeder reads this folder and rebuilds the `products` table.

## Layout

```text
resources/ik_products/
├── *.pdf                                 # Product datasheets (required)
├── image-map.json                        # Optional explicit image mapping
├── images/                               # Optional per-product folders
│   ├── foam-scrapers/
│   │   └── 01.jpg
│   └── bi-directional-brush-scrapers/
├── Products with Background/             # Studio photos (matched by filename keywords or image-map)
├── Products in Factory/
└── People in Action/
```

## Recognized PDF products

| PDF (example) | Slug |
|---|---|
| `IK-Saudi_ Foam_Scrapers_DATASHEET.pdf` | `foam-scrapers` |
| `IK-Saudi_ Bi_directional_Brush_Scrapers.pdf` | `bi-directional-brush-scrapers` |
| `IK-Saudi_ Bi_directional_disc_Scrapers.pdf` | `bi-directional-disc-scrapers` |
| `IK-Saudi_ Bi_directional_gauging_Scrapers.pdf` | `bi-directional-gauging-scrapers` |

Duplicate PDFs for the same product (e.g. `Bi_directional_disc_Scrapers.pdf`) are skipped; `IK-Saudi_*` files are preferred.

## Image matching order

1. Entries in `image-map.json` for the product slug  
2. Files in `images/{slug}/`  
3. Filenames containing product keywords (`foam`, `brush`, `disc`/`disk`, `gauging`)  
4. Embedded images extracted from the PDF (preferred datasheet product shot)

The database only stores **one** `featured_image` and one `pdf_path` per product (existing schema). Additional matched images are copied to:

`storage/app/public/products/{slug}/gallery/`

## Run

```bash
php artisan db:seed --class=ProductSeeder
```

Ensure the public disk is linked:

```bash
php artisan storage:link
```

## Notes

- The seeder **truncates** all products (and translations) before insert — it is idempotent.  
- PDF parse / image match failures are logged as warnings; the seeder continues.  
- Requires `smalot/pdfparser` (already in `composer.json`).
