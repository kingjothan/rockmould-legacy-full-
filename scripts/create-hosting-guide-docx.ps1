$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$markdownPath = Join-Path $root 'docs\rockmould-hosting-guide.md'
$docxPath = Join-Path $root 'docs\rockmould-hosting-guide.docx'
$zipPath = Join-Path $root 'docs\rockmould-hosting-guide.zip'
$tempPath = Join-Path $env:TEMP ('rockmould-docx-' + [guid]::NewGuid())

function Escape-XmlText {
    param([string] $Text)

    return [System.Security.SecurityElement]::Escape($Text)
}

function Add-Paragraph {
    param(
        [System.Collections.Generic.List[string]] $Parts,
        [string] $Text,
        [string] $Style = 'Normal'
    )

    $escaped = Escape-XmlText $Text
    $styleXml = ''

    if ($Style -ne 'Normal') {
        $styleXml = "<w:pPr><w:pStyle w:val=`"$Style`"/></w:pPr>"
    }

    $Parts.Add("<w:p>$styleXml<w:r><w:t xml:space=`"preserve`">$escaped</w:t></w:r></w:p>")
}

function Add-CodeParagraph {
    param(
        [System.Collections.Generic.List[string]] $Parts,
        [string] $Text
    )

    $escaped = Escape-XmlText $Text
    $Parts.Add("<w:p><w:pPr><w:pStyle w:val=`"Code`"/></w:pPr><w:r><w:t xml:space=`"preserve`">$escaped</w:t></w:r></w:p>")
}

if (-not (Test-Path $markdownPath)) {
    throw "Markdown source not found: $markdownPath"
}

if (Test-Path $tempPath) {
    Remove-Item -LiteralPath $tempPath -Recurse -Force
}

New-Item -ItemType Directory -Path $tempPath | Out-Null
New-Item -ItemType Directory -Path (Join-Path $tempPath '_rels') | Out-Null
New-Item -ItemType Directory -Path (Join-Path $tempPath 'word') | Out-Null
New-Item -ItemType Directory -Path (Join-Path $tempPath 'word\_rels') | Out-Null

$contentTypes = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>
'@

$rels = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>
'@

$documentRels = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"/>
'@

$styles = @'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:default="1" w:styleId="Normal">
    <w:name w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:after="180" w:line="276" w:lineRule="auto"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Aptos" w:hAnsi="Aptos"/><w:sz w:val="22"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:after="240"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Trajan Pro" w:hAnsi="Trajan Pro"/><w:b/><w:sz w:val="44"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:before="420" w:after="180"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Cormorant Garamond" w:hAnsi="Cormorant Garamond"/><w:b/><w:sz w:val="34"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:basedOn w:val="Normal"/>
    <w:next w:val="Normal"/>
    <w:qFormat/>
    <w:pPr><w:spacing w:before="260" w:after="120"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Cormorant Garamond" w:hAnsi="Cormorant Garamond"/><w:b/><w:sz w:val="28"/></w:rPr>
  </w:style>
  <w:style w:type="paragraph" w:styleId="Code">
    <w:name w:val="Code"/>
    <w:basedOn w:val="Normal"/>
    <w:pPr><w:spacing w:after="80"/></w:pPr>
    <w:rPr><w:rFonts w:ascii="Consolas" w:hAnsi="Consolas"/><w:sz w:val="19"/></w:rPr>
  </w:style>
</w:styles>
'@

$parts = [System.Collections.Generic.List[string]]::new()
$inCode = $false
$firstHeading = $true

foreach ($line in Get-Content $markdownPath) {
    if ($line.StartsWith('```')) {
        $inCode = -not $inCode
        continue
    }

    if ($inCode) {
        Add-CodeParagraph -Parts $parts -Text $line
        continue
    }

    if ([string]::IsNullOrWhiteSpace($line)) {
        Add-Paragraph -Parts $parts -Text ''
        continue
    }

    if ($line.StartsWith('# ')) {
        $style = if ($firstHeading) { 'Title' } else { 'Heading1' }
        Add-Paragraph -Parts $parts -Text $line.Substring(2) -Style $style
        $firstHeading = $false
        continue
    }

    if ($line.StartsWith('## ')) {
        Add-Paragraph -Parts $parts -Text $line.Substring(3) -Style 'Heading1'
        continue
    }

    if ($line.StartsWith('### ')) {
        Add-Paragraph -Parts $parts -Text $line.Substring(4) -Style 'Heading2'
        continue
    }

    Add-Paragraph -Parts $parts -Text $line
}

$body = [string]::Join("`n", $parts)
$document = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:body>
    $body
    <w:sectPr>
      <w:pgSz w:w="11906" w:h="16838"/>
      <w:pgMar w:top="1440" w:right="1080" w:bottom="1440" w:left="1080" w:header="708" w:footer="708" w:gutter="0"/>
    </w:sectPr>
  </w:body>
</w:document>
"@

Set-Content -LiteralPath (Join-Path $tempPath '[Content_Types].xml') -Value $contentTypes -Encoding UTF8
Set-Content -LiteralPath (Join-Path $tempPath '_rels\.rels') -Value $rels -Encoding UTF8
Set-Content -LiteralPath (Join-Path $tempPath 'word\_rels\document.xml.rels') -Value $documentRels -Encoding UTF8
Set-Content -LiteralPath (Join-Path $tempPath 'word\styles.xml') -Value $styles -Encoding UTF8
Set-Content -LiteralPath (Join-Path $tempPath 'word\document.xml') -Value $document -Encoding UTF8

if (Test-Path $docxPath) {
    Remove-Item -LiteralPath $docxPath -Force
}

if (Test-Path $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

Compress-Archive -Path (Join-Path $tempPath '*') -DestinationPath $zipPath -Force
Move-Item -LiteralPath $zipPath -Destination $docxPath -Force
Remove-Item -LiteralPath $tempPath -Recurse -Force

Write-Host "Created $docxPath"
