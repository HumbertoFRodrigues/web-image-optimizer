# Web Image Optimizer

<p align="center">
  <strong>Web Image Optimizer</strong><br>
  Automatic image compression, WebP conversion, resizing and responsive variants for modern web applications.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/WebP-supported-0A7A3B" alt="WebP">
  <img src="https://img.shields.io/badge/PHP-supported-777BB4" alt="PHP">
  <img src="https://img.shields.io/badge/Node.js-supported-339933" alt="Node.js">
  <img src="https://img.shields.io/badge/Python-supported-3776AB" alt="Python">
  <img src="https://img.shields.io/badge/license-MIT-blue" alt="MIT License">
</p>

## Overview

**Web Image Optimizer** is a reusable image-processing toolkit for websites and web applications.

It implements a practical optimization pipeline designed to reduce image weight while preserving useful visual quality:

```text
Upload
  │
  ▼
Validate
  │
  ▼
Read orientation / metadata
  │
  ▼
Correct orientation
  │
  ▼
Resize without enlarging
  │
  ▼
Remove unnecessary metadata
  │
  ▼
Convert to WebP
  │
  ▼
Adaptive quality
  │
  ▼
Target file size
  │
  ▼
Generate responsive variants
  │
  ▼
Store and serve
```

The project is intended for portals, CMS platforms, e-commerce systems, dashboards, institutional websites, APIs and any application where users upload images.

## Why this project?

Large images are one of the most common causes of unnecessary page weight.

A photograph uploaded directly from a modern phone can contain:

- excessive pixel dimensions;
- embedded EXIF metadata;
- camera orientation information;
- more quality than the interface needs;
- a format that is inefficient for web delivery.

Instead of relying on users to manually optimize files, the application can process every upload automatically.

### Main goals

- Convert supported images to **WebP**.
- Limit maximum image width.
- Avoid enlarging already-small images.
- Correct EXIF orientation.
- Remove unnecessary metadata.
- Target a maximum file size in KB.
- Reduce quality progressively when necessary.
- Generate responsive image variants.
- Support both server-side and browser-side optimization.
- Provide examples for multiple programming environments.

## Features

### Automatic WebP conversion

```text
photo.jpg
    ↓
photo.webp
```

WebP is used as the primary output format in the examples.

### Maximum width

The optimizer can resize large images while keeping their aspect ratio.

Example:

```text
4000 × 3000
      ↓
1920 × 1440
```

An image smaller than the configured limit is not enlarged.

### Target file size

The optimizer can progressively reduce quality until the generated image reaches the configured size target.

Example:

```text
Quality 78
    ↓
Quality 70
    ↓
Quality 62
    ↓
Quality 55
    ↓
Quality 48
```

The process stops when the target is reached or when the configured minimum quality is reached.

### EXIF orientation

Images captured by phones and cameras can contain orientation metadata. The examples include an orientation-correction step before final encoding.

### Metadata removal

The pipeline can strip metadata from the generated image.

This can reduce unnecessary bytes and, depending on the application, avoid exposing metadata that should not be published.

### Responsive variants

The repository includes examples for generating variants such as:

```text
photo-150x150.webp
photo-370x222.webp
photo-740x444.webp
photo-1024x615.webp
photo.webp
```

The exact dimensions should be adapted to the application's real interface.

## Supported implementations

| Environment | Implementation | Status |
|---|---|---|
| PHP | GD | Example |
| PHP | Imagick | Example |
| Laravel | PHP | Example |
| Node.js | Sharp | Example |
| Python | Pillow | Example |
| C# | ImageSharp | Example |
| Go | libvips / govips | Example |
| Ruby | libvips / image_processing | Example |
| CLI | ImageMagick / libvips examples | Example |
| Browser | Canvas / WebP | Example |
| Nginx | WebP delivery example | Example |
| Apache | WebP delivery example | Example |

The implementations are intentionally separated so the same optimization strategy can be adapted to different application stacks.

## Repository structure

```text
web-image-optimizer/
│
├── README.md
├── LICENSE
├── CHANGELOG.md
├── CONTRIBUTING.md
│
├── docs/
│   ├── imagens-em-kb.pdf
│   ├── ARCHITECTURE.md
│   ├── CONFIG.md
│   ├── INSTALLATION.md
│   └── SECURITY.md
│
├── php/
│   ├── gd/
│   ├── imagick/
│   └── laravel/
│
├── node/
│   ├── package.json
│   ├── src/
│   └── example.js
│
├── python/
│   ├── requirements.txt
│   ├── image_optimizer.py
│   └── example.py
│
├── csharp/
├── go/
├── ruby/
├── cli/
├── browser/
├── server/
├── examples/
└── tests/
```

## Quick start — PHP GD

### Requirements

PHP with:

- GD;
- WebP support;
- EXIF support when orientation correction is required.

Check WebP support:

```bash
php -r 'var_dump(gd_info()["WebP Support"]);'
```

Check EXIF:

```bash
php -m | grep exif
```

### Usage

```php
require 'php/gd/ImageOptimizer.php';

compressToWebp(
    'photo.jpg',
    'photo.webp',
    1920,
    350
);
```

Arguments:

```text
source      → input image
destination → output WebP
maxWidth    → maximum width in pixels
targetKb    → target maximum size
```

## Quick start — Node.js

The Node.js example uses [Sharp](https://sharp.pixelplumbing.com/).

```bash
cd node
npm install
```

Then:

```bash
node example.js
```

The implementation is available in:

```text
node/src/imageOptimizer.js
```

Example:

```javascript
await compressToWebp(
  "photo.jpg",
  "photo.webp",
  1920,
  350
);
```

## Quick start — Python

Install the dependency:

```bash
cd python
pip install -r requirements.txt
```

Then:

```bash
python example.py
```

The main implementation is:

```text
python/image_optimizer.py
```

## Browser-side optimization

The repository also contains a browser example for reducing the image before it reaches the server:

```text
browser/compress-before-upload.js
```

This can reduce upload bandwidth and improve the upload experience.

However, **browser-side processing should not replace server-side validation and processing**.

The server should still validate the uploaded file and enforce its own limits.

## Responsive images

After creating the main optimized image, applications can generate variants for different interface components.

Example:

```html
<img
  src="/uploads/photo-740x444.webp"
  srcset="
    /uploads/photo-370x222.webp 370w,
    /uploads/photo-740x444.webp 740w,
    /uploads/photo-1024x615.webp 1024w
  "
  sizes="(max-width: 740px) 100vw, 740px"
  alt="Example image"
/>
```

The dimensions and `sizes` rules should be adjusted to the actual layout.

## Recommended pipeline

For a production application, the conceptual flow is:

```text
Client
  │
  ├── optional browser compression
  │
  ▼
Upload endpoint
  │
  ├── validate type
  ├── validate dimensions
  ├── validate upload size
  │
  ▼
Image processor
  │
  ├── orientation
  ├── resize
  ├── metadata
  ├── WebP encoding
  └── target-size loop
  │
  ▼
Storage
  │
  ├── original (optional, private)
  ├── optimized image
  └── responsive variants
  │
  ▼
Web server / CDN
  │
  ▼
Browser
```

## Security considerations

Image uploads should be treated as untrusted input.

At minimum:

- validate the actual file content, not only the extension;
- enforce upload-size limits;
- restrict allowed image formats;
- avoid executable files in upload directories;
- generate controlled filenames;
- keep private originals outside the public webroot when necessary;
- strip metadata when appropriate;
- protect image-processing endpoints from abuse;
- consider processing limits for very large or malformed images.

See [`docs/SECURITY.md`](docs/SECURITY.md).

## Configuration

Default values used by the examples:

| Setting | Default |
|---|---:|
| Maximum width | 1920 px |
| Target size | 350 KB |
| Initial quality | 78 |
| Minimum quality | 48 |
| Quality steps | 78 → 70 → 62 → 55 → 48 |
| Variant | 150 × 150 |
| Variant | 370 × 222 |
| Variant | 740 × 444 |
| Variant | 1024 × 615 |

These are starting points, not universal optimal values.

Different applications may need different targets depending on the image type, visual importance and available network conditions.

## Testing

The `tests/` directory is prepared for image fixtures and automated tests.

Test at least:

- large JPEG photographs;
- small JPEG images;
- PNG with transparency;
- portrait images;
- landscape images;
- images with EXIF orientation;
- images containing metadata;
- already-optimized WebP;
- unusually large dimensions;
- invalid or corrupted uploads.

The important properties to verify are:

```text
✓ output format
✓ dimensions
✓ orientation
✓ transparency when applicable
✓ output size
✓ visual quality
✓ metadata handling
✓ behavior with invalid input
```

## Performance

There is no single compression setting that is optimal for every image.

The target-size loop is therefore configurable. For production deployments, benchmark representative images from the application's own content rather than assuming that one quality value will produce the same result for every image.

For high-volume processing, libraries based on **libvips** can be considered because image processing characteristics differ from approaches that load and manipulate images entirely in memory.

## Documentation

Additional documentation:

- [`Architecture`](docs/ARCHITECTURE.md)
- [`Configuration`](docs/CONFIG.md)
- [`Installation`](docs/INSTALLATION.md)
- [`Security`](docs/SECURITY.md)
- [`Original technical document`](docs/imagens-em-kb.pdf)

## Roadmap

Possible future improvements:

- [ ] unified command-line interface;
- [ ] automated test suite;
- [ ] benchmark suite;
- [ ] automatic format negotiation for AVIF/WebP;
- [ ] JPEG XL evaluation;
- [ ] content hashing and duplicate detection;
- [ ] queue-based asynchronous processing;
- [ ] storage adapters;
- [ ] S3-compatible object storage support;
- [ ] Docker image;
- [ ] GitHub Actions CI;
- [ ] published npm package;
- [ ] published Composer package;
- [ ] Laravel package with service provider;
- [ ] REST API example.

## Contributing

Contributions are welcome.

Before opening a Pull Request:

1. Keep the implementation focused.
2. Add or update documentation.
3. Test the affected implementation.
4. Avoid unnecessary dependencies.
5. Explain changes that affect image quality or output size.

See [`CONTRIBUTING.md`](CONTRIBUTING.md).

## License

MIT — see [`LICENSE`](LICENSE).

## Project note

This repository organizes and turns the image-optimization strategy documented in
`docs/imagens-em-kb.pdf` into a reusable multi-stack project.

The examples are intended as a foundation. Production systems should adapt validation,
storage, security, quality targets and processing limits to their own requirements.
