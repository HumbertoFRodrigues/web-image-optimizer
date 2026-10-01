# Installation

## PHP GD

Confirme o suporte WebP e EXIF:

```bash
php -r 'var_dump(gd_info()["WebP Support"]);'
php -m | grep exif
```

## Node.js

```bash
cd node
npm install
```

## Python

```bash
cd python
pip install -r requirements.txt
```

## C#

```bash
dotnet add package SixLabors.ImageSharp
```

## Go

Instale libvips no sistema e depois as dependências Go.

## Ruby

Instale `image_processing` e libvips.
