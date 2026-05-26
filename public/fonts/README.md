Rockmould webfont files
=======================

Place the licensed production webfont files in this folder so every visitor sees the same typography on Mac, Windows, iPhone, Android, and desktop browsers.

The user's device does not need Trajan Pro or Gotham installed. The website loads these files directly from `/fonts/...` through `@font-face`.

Required filenames:

- trajan-pro.woff2
- trajan-pro-bold.woff2
- gotham-book.woff2
- gotham-medium.woff2
- gotham-bold.woff2

Optional fallback filenames also supported by `resources/css/app.css`:

- trajan-pro.woff
- trajan-pro.otf
- trajan-pro.ttf
- trajan-pro-bold.woff
- trajan-pro-bold.otf
- trajan-pro-bold.ttf
- gotham.woff2
- gotham.otf
- gotham.ttf
- gotham-book.woff
- gotham-book.otf
- gotham-book.ttf
- gotham-medium.woff
- gotham-medium.otf
- gotham-medium.ttf
- gotham-bold.woff
- gotham-bold.otf
- gotham-bold.ttf

Important: Trajan Pro and Gotham are commercial fonts. Use licensed webfont files only.

The CSS intentionally does not use `local()` font sources or local system font names for Trajan/Gotham. This keeps typography consistent across devices by forcing browsers to load the same hosted files from `/public/fonts`.

After upload, verify these URLs in a browser:

- https://your-domain.com/fonts/trajan-pro-bold.woff2
- https://your-domain.com/fonts/gotham-book.woff2
- https://your-domain.com/fonts/gotham-medium.woff2
- https://your-domain.com/fonts/gotham-bold.woff2

If any URL shows 404, the font file is not uploaded in the correct folder or filename.
