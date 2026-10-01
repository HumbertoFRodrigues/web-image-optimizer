from image_optimizer import compress_to_webp, make_variant

compress_to_webp(
    "../examples/input/photo.jpg",
    "../examples/output/photo.webp"
)

for width, height in [(150,150), (370,222), (740,444), (1024,615)]:
    make_variant(
        "../examples/output/photo.webp",
        f"../examples/output/photo-{width}x{height}.webp",
        width,
        height
    )
