const sharp = require('sharp');
const fs = require('fs/promises');

async function compressToWebp(src, dst, maxWidth = 1920, targetKb = 350) {
  const base = sharp(src)
    .rotate()
    .resize({ width: maxWidth, withoutEnlargement: true });

  let buf;

  for (const quality of [78, 70, 62, 55, 48]) {
    buf = await base.clone().webp({ quality }).toBuffer();

    if (buf.length <= targetKb * 1024) {
      break;
    }
  }

  await fs.writeFile(dst, buf);
}

async function makeVariant(src, dst, width, height) {
  await sharp(src)
    .resize(width, height, { fit: 'cover', position: 'centre' })
    .webp({ quality: 78 })
    .toFile(dst);
}

module.exports = { compressToWebp, makeVariant };
