# Image in KB

Pipeline reutilizável para otimização automática de imagens para a Web.

A ideia central é receber uma imagem pesada, corrigir a orientação, reduzir a dimensão,
remover metadados, converter para WebP e reduzir progressivamente a qualidade até atingir
um orçamento máximo de tamanho. Também podem ser geradas variantes responsivas.

## Objetivos

- Converter fotos para WebP
- Reduzir imagens sem ampliar imagens pequenas
- Corrigir orientação EXIF
- Remover metadados/EXIF
- Trabalhar com um alvo máximo em KB
- Gerar variantes para diferentes componentes da interface
- Disponibilizar exemplos em várias linguagens
- Permitir compressão no browser antes do upload
- Servir WebP mantendo compatibilidade com URLs antigas

## Estrutura

- `php/` — PHP GD, Imagick e Laravel
- `node/` — Node.js + Sharp
- `python/` — Python + Pillow
- `csharp/` — C# + ImageSharp
- `go/` — Go + libvips/govips
- `ruby/` — Ruby/Rails + libvips
- `cli/` — exemplos de linha de comando
- `browser/` — compressão antes do upload
- `server/` — configurações Nginx e Apache
- `docs/` — documentação técnica original
- `tests/` — espaço para fixtures e testes

## Configuração padrão

- largura máxima: `1920px`
- alvo: `350 KB`
- qualidades tentadas: `78, 70, 62, 55, 48`
- variantes: `150x150`, `370x222`, `740x444`, `1024x615`

## Fluxo

```text
Upload
  ↓
Validação
  ↓
Correção EXIF
  ↓
Resize sem ampliar
  ↓
Remoção de metadados
  ↓
Conversão WebP
  ↓
Qualidade adaptativa até o alvo
  ↓
Variantes responsivas
  ↓
Armazenamento
```

## Exemplo

```php
compress_to_webp(
    $src,
    $dst,
    maxWidth: 1920,
    targetKb: 350
);
```

## Nota

O código deste repositório é uma organização reutilizável da técnica descrita na documentação
incluída em `docs/imagens-em-kb.pdf`. Antes de usar em produção, teste com imagens reais,
especialmente fotos de telemóvel, PNG com transparência, GIF e formatos HEIC/ICC.

## Licença

Ver `LICENSE`.
