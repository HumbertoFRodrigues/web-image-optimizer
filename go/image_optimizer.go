package main

import (
	"os"

	"github.com/davidbyttow/govips/v2/vips"
)

func CompressToWebp(src, dst string, maxWidth, targetKb int) error {
	img, err := vips.NewImageFromFile(src)
	if err != nil {
		return err
	}
	defer img.Close()

	img.AutoRotate()

	if img.Width() > maxWidth {
		if err := img.Resize(
			float64(maxWidth)/float64(img.Width()),
			vips.KernelLanczos3,
		); err != nil {
			return err
		}
	}

	var buf []byte

	for _, q := range []int{78, 70, 62, 55, 48} {
		p := vips.NewWebpExportParams()
		p.Quality = q
		p.StripMetadata = true

		buf, _, err = img.ExportWebp(p)
		if err != nil {
			return err
		}

		if len(buf) <= targetKb*1024 {
			break
		}
	}

	return os.WriteFile(dst, buf, 0644)
}

func main() {
	vips.Startup(nil)
	defer vips.Shutdown()
}
