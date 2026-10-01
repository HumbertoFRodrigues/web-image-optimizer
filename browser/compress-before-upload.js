export async function compressBeforeUpload(
  file,
  maxWidth = 1920,
  quality = 0.78
) {
  const bmp = await createImageBitmap(file, {
    imageOrientation: 'from-image'
  });

  const scale = Math.min(1, maxWidth / bmp.width);

  const canvas = document.createElement('canvas');
  canvas.width = Math.round(bmp.width * scale);
  canvas.height = Math.round(bmp.height * scale);

  canvas
    .getContext('2d')
    .drawImage(bmp, 0, 0, canvas.width, canvas.height);

  let blob = await new Promise(resolve =>
    canvas.toBlob(resolve, 'image/webp', quality)
  );

  if (!blob || blob.type !== 'image/webp') {
    blob = await new Promise(resolve =>
      canvas.toBlob(resolve, 'image/jpeg', quality)
    );
  }

  return new File(
    [blob],
    file.name.replace(
      /\.\w+$/,
      blob.type === 'image/webp' ? '.webp' : '.jpg'
    ),
    { type: blob.type }
  );
}
