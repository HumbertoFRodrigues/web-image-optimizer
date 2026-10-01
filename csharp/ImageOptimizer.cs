using System;
using System.IO;
using SixLabors.ImageSharp;
using SixLabors.ImageSharp.Formats.Webp;
using SixLabors.ImageSharp.Processing;

public static class ImageOptimizer
{
    public static void CompressToWebp(
        string src,
        string dst,
        int maxWidth = 1920,
        int targetKb = 350)
    {
        using var image = Image.Load(src);

        image.Mutate(x => x.AutoOrient());

        if (image.Width > maxWidth)
            image.Mutate(x => x.Resize(maxWidth, 0));

        image.Metadata.ExifProfile = null;

        byte[] bytes = Array.Empty<byte>();

        foreach (var quality in new[] { 78, 70, 62, 55, 48 })
        {
            using var ms = new MemoryStream();
            image.Save(ms, new WebpEncoder { Quality = quality });
            bytes = ms.ToArray();

            if (bytes.Length <= targetKb * 1024)
                break;
        }

        File.WriteAllBytes(dst, bytes);
    }

    public static void MakeVariant(
        string src,
        string dst,
        int width,
        int height)
    {
        using var image = Image.Load(src);

        image.Mutate(x => x.Resize(new ResizeOptions
        {
            Size = new Size(width, height),
            Mode = ResizeMode.Crop
        }));

        image.Save(dst, new WebpEncoder { Quality = 78 });
    }
}
