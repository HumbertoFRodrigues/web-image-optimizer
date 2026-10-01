require "image_processing/vips"

def compress_to_webp(src, dst, max_width = 1920)
  ImageProcessing::Vips
    .source(src)
    .resize_to_limit(max_width, nil)
    .convert("webp")
    .saver(quality: 78, strip: true)
    .call(destination: dst)
end

def make_variant(src, dst, width, height)
  ImageProcessing::Vips
    .source(src)
    .resize_to_fill(width, height)
    .convert("webp")
    .saver(quality: 78, strip: true)
    .call(destination: dst)
end
