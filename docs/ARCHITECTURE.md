# Architecture

## Pipeline

1. Validar o upload.
2. Detetar duplicados quando o sistema de aplicação possuir armazenamento por hash.
3. Corrigir orientação EXIF.
4. Reduzir a largura sem ampliar.
5. Remover metadados.
6. Converter para WebP.
7. Tentar qualidades descendentes até atingir o alvo.
8. Gerar variantes de acordo com o layout.
9. Guardar metadados da imagem.
10. Servir a variante adequada com `srcset`.

## Browser

A compressão no browser é complementar. A validação e a compressão no servidor continuam obrigatórias.

## Produção

Para aplicações com grande volume de imagens, libvips/Sharp pode reduzir consumo de memória em comparação com abordagens que carregam a imagem inteira em memória.
