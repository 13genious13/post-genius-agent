@props(['title' => 'Account', 'eyebrow' => null, 'heading' => null, 'subtitle' => null])
@include('layouts.pg-auth', compact('title', 'eyebrow', 'heading', 'subtitle', 'slot'))
