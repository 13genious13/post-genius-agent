@extends('public.layout')
@section('content')
<section class="page-hero"><div class="container narrow center"><span class="eyebrow">Plans for growing teams</span><h1>Start with what you need. <span class="gradient-text">Grow with confidence.</span></h1><p class="lead">Plan availability and pricing are confirmed with our team. No unverified prices or checkout promises.</p></div></section>
<section class="section"><div class="container">@include('public.partials.price-cards')<p class="center muted pricing-note">Features, quotas, platform access and onboarding terms are confirmed before purchase. Contact us for a written quote.</p></div></section>
@include('public.partials.cta')
@endsection
