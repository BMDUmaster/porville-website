{{--
    SEO head tags. Values from SEO Management ($seoPage) win; otherwise pages can supply
    @section('title'), @section('meta_description'), @section('og_image'), @section('og_type').
--}}
@php
    // @section('x', 'value') stores escaped HTML; decode so {{ }} below doesn't double-escape.
    $seoYield = fn (string $section, string $default = '') => trim(html_entity_decode($__env->yieldContent($section, $default), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $seoTitle = $seoPage?->meta_title ?: $seoYield('title', 'Porville — Fresh Cut Pure Standards');
    $seoDescription = $seoPage?->meta_description ?: $seoYield('meta_description');
    $seoCanonical = $seoPage?->resolved_canonical ?: url()->current();
    $seoOgTitle = $seoPage?->og_title ?: $seoTitle;
    $seoOgDescription = $seoPage?->og_description ?: $seoDescription;
    $seoOgImage = $seoPage?->og_image_url ?: ($seoYield('og_image') ?: $brandLogoUrl);
    $seoOgType = $seoPage?->og_type ?: ($seoYield('og_type') ?: 'website');
    $seoTwitterTitle = $seoPage?->twitter_title ?: $seoOgTitle;
    $seoTwitterDescription = $seoPage?->twitter_description ?: $seoOgDescription;
    $seoTwitterImage = $seoPage?->twitter_image_url ?: $seoOgImage;
@endphp
<title>{{ $seoTitle }}</title>
@if($seoDescription)
<meta name="description" content="{{ $seoDescription }}">
@endif
@if($seoPage?->meta_keywords)
<meta name="keywords" content="{{ $seoPage->meta_keywords }}">
@endif
<meta name="robots" content="{{ $seoPage?->robots_content ?? 'index, follow' }}">
<link rel="canonical" href="{{ $seoCanonical }}">

<meta property="og:site_name" content="Porville">
<meta property="og:type" content="{{ $seoOgType }}">
<meta property="og:title" content="{{ $seoOgTitle }}">
@if($seoOgDescription)
<meta property="og:description" content="{{ $seoOgDescription }}">
@endif
<meta property="og:url" content="{{ $seoCanonical }}">
<meta property="og:image" content="{{ $seoOgImage }}">
<meta property="og:locale" content="en_IN">

<meta name="twitter:card" content="{{ $seoPage?->twitter_card ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $seoTwitterTitle }}">
@if($seoTwitterDescription)
<meta name="twitter:description" content="{{ $seoTwitterDescription }}">
@endif
<meta name="twitter:image" content="{{ $seoTwitterImage }}">
@if($seoPage?->twitter_site)
<meta name="twitter:site" content="{{ $seoPage->twitter_site }}">
@endif
@foreach($seoPage?->schemaBlocks() ?? [] as $schemaBlock)
<script type="application/ld+json">{!! json_encode($schemaBlock, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endforeach
