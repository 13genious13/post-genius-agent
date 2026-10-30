@extends('public.layout')
@section('content')
<section class="page-hero"><div class="container narrow center"><span class="eyebrow">Get in touch</span><h1>Let's build something <span class="gradient-text">great together.</span></h1><p class="lead">Tell us what you want to achieve and our team will get back to you.</p></div></section>
<section class="section"><div class="container contact-grid"><div class="contact-info"><h2>Talk to our team</h2><p class="lead-sm">Questions about plans, onboarding, features or integrations? We'd love to hear from you.</p><a class="text-link" href="mailto:{{ config('postgenius_public.contact_email') }}">{{ config('postgenius_public.contact_email') }}</a><div class="contact-note"><b>Helpful details to share</b><p>Your business type, marketing channels and the workflow you'd like to improve.</p></div></div><form class="contact-form" method="POST" action="{{ route('public.contact.submit') }}">@csrf
@if(session('success'))<div class="notice success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="notice error" role="alert">Please correct the highlighted fields and try again.</div>@endif
<div class="form-row"><label for="name">Your name *</label><input id="name" name="name" autocomplete="name" required maxlength="100" value="{{ old('name') }}">@error('name')<small class="field-error">{{ $message }}</small>@enderror</div>
<div class="form-row"><label for="email">Work email *</label><input id="email" name="email" type="email" autocomplete="email" required maxlength="254" value="{{ old('email') }}">@error('email')<small class="field-error">{{ $message }}</small>@enderror</div>
<div class="form-row"><label for="company">Company (optional)</label><input id="company" name="company" autocomplete="organization" maxlength="120" value="{{ old('company') }}"></div>
<div class="hp" aria-hidden="true"><label for="website">Leave this field blank</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
<div class="form-row"><label for="message">How can we help? *</label><textarea id="message" name="message" rows="5" required minlength="10" maxlength="3000">{{ old('message') }}</textarea>@error('message')<small class="field-error">{{ $message }}</small>@enderror</div>
<button type="submit" class="btn btn-primary full">Send Message →</button><p class="muted tiny">By submitting, you agree that we may use your details to respond to your enquiry.</p></form></div></section>
@endsection
