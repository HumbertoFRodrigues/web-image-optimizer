from io import BytesIO
from PIL import Image, ImageOps


def compress_to_webp(src, dst, max_width=1920, target_kb=350):
    img = ImageOps.exif_transpose(Image.open(src))

    if img.width > max_width:
        new_height = round(img.height * max_width / img.width)
        img = img.resize((max_width, new_height), Image.LANCZOS)

    if img.mode not in ("RGB", "RGBA"):
        img = img.convert("RGBA")

    for quality in (78, 70, 62, 55, 48):
        buf = BytesIO()
        img.save(buf, "WEBP", quality=quality, method=6)

        if buf.tell() <= target_kb * 1024:
            break

    with open(dst, "wb") as f:
        f.write(buf.getvalue())


def make_variant(src, dst, width, height):
    img = Image.open(src)
    img = ImageOps.fit(img, (width, height), Image.LANCZOS)
    img.save(dst, "WEBP", quality=78)
