<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
@php($brand = config('postgenius_public.brand'))
<title>{{ $meta['title'] }} | {{ $brand }}</title>
<meta name="description" content="{{ $meta['description'] }}">
<meta name="robots" content="{{ config('postgenius_public.indexable') ? 'index,follow' : 'noindex,nofollow' }}">
<link rel="canonical" href="{{ $canonical }}">
<meta property="og:type" content="website"><meta property="og:site_name" content="{{ $brand }}">
<meta property="og:title" content="{{ $meta['title'] }} | {{ $brand }}"><meta property="og:description" content="{{ $meta['description'] }}">
<meta property="og:url" content="{{ $canonical }}"><meta property="og:image" content="{{ url(config('postgenius_public.social_image')) }}">
<meta name="twitter:card" content="summary_large_image"><meta name="theme-color" content="#f8f9ff">
<link rel="icon" href="{{ asset('postgenius/favicon.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ asset('postgenius/site.css') }}">
<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'SoftwareApplication','name'=>$brand,'applicationCategory'=>'BusinessApplication','operatingSystem'=>'Web','url'=>route('public.home'),'description'=>$meta['description']], JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
</head>
<body>
<a class="skip" href="#main">Skip to content</a>
<header class="header"><div class="container nav">
<a class="brand" href="{{ route('public.home') }}" aria-label="PostGenius Agent home"><span class="brand-mark">✦</span><span>PostGenius <b>Agent</b></span></a>
<button class="menu-toggle" id="menuToggle" aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">☰</button>
<nav class="navlinks" id="siteNav" aria-label="Main navigation">
<a href="{{ route('public.features') }}">Features</a><a href="{{ route('public.how-it-works') }}">How It Works</a><a href="{{ route('public.pricing') }}">Pricing</a><a href="{{ route('public.faq') }}">FAQ</a>
<a class="nav-login" href="{{ Route::has('login') ? route('login') : url('/login') }}">Log in</a>
<a class="btn btn-primary btn-small" href="{{ Route::has('register') ? route('register') : route('public.contact') }}">Get Started <span aria-hidden="true">↗</span></a>
</nav></div></header>
<main id="main">@yield('content')</main>
<footer class="footer"><div class="container"><div class="footer-top"><div><a class="brand" href="{{ route('public.home') }}"><span class="brand-mark">✦</span><span>PostGenius <b>Agent</b></span></a><p class="muted footer-desc">A smarter workspace for research, creative campaigns, publishing and learning.</p></div><div><b>Product</b><a href="{{ route('public.features') }}">Features</a><a href="{{ route('public.how-it-works') }}">How It Works</a><a href="{{ route('public.pricing') }}">Pricing</a></div><div><b>Company</b><a href="{{ route('public.about') }}">About</a><a href="{{ route('public.contact') }}">Contact</a><a href="{{ route('public.faq') }}">FAQ</a></div><div><b>Legal</b><a href="{{ route('public.privacy') }}">Privacy</a><a href="{{ route('public.terms') }}">Terms</a></div></div><div class="footer-bottom">© {{ date('Y') }} {{ config('postgenius_public.brand') }}. All rights reserved. <span>Built for human-guided AI marketing.</span></div></div></footer>
<script src="{{ asset('postgenius/site.js') }}" defer></script>
</body></html>
