#!/usr/bin/env bash
set -euo pipefail

INPUT="${1:?Informe a imagem de entrada}"
OUTPUT="${2:?Informe a imagem de saída}"

# ImageMagick 7
magick "$INPUT" \
  -auto-orient \
  -strip \
  -resize "1920x>" \
  -quality 78 \
  -define webp:target-size=358400 \
  "$OUTPUT"

# Alternativa com libvips:
# vips thumbnail "$INPUT" "${OUTPUT}[Q=78,strip]" 1920 --size down
