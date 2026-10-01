const path = require('path');
const {
  compressToWebp,
  makeVariant
} = require('./src/imageOptimizer');

(async () => {
  const input = path.resolve('../examples/input/photo.jpg');
  const output = path.resolve('../examples/output/photo.webp');

  await compressToWebp(input, output, 1920, 350);

  for (const [w, h] of [[150,150], [370,222], [740,444], [1024,615]]) {
    await makeVariant(
      output,
      path.resolve(`../examples/output/photo-${w}x${h}.webp`),
      w,
      h
    );
  }
})();
