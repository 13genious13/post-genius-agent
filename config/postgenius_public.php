<?php
return [
 'brand' => env('POSTGENIUS_BRAND', 'PostGenius Agent'),
 'tagline' => 'Your autonomous AI marketing team',
 'contact_email' => env('POSTGENIUS_CONTACT_EMAIL', 'hello@example.com'),
 'contact_to' => env('POSTGENIUS_CONTACT_TO', env('MAIL_FROM_ADDRESS', 'hello@example.com')),
 'social_image' => env('POSTGENIUS_SOCIAL_IMAGE', '/postgenius/og-cover.svg'),
 'indexable' => filter_var(env('POSTGENIUS_PUBLIC_INDEXABLE', false), FILTER_VALIDATE_BOOLEAN),
 'pricing' => [
  ['name'=>'Starter','price'=>'Contact us','period'=>'','description'=>'A focused starting point for growing brands.','features'=>['Business and brand intelligence','Campaign planning','AI content drafts','Approval workflow'],'featured'=>false],
  ['name'=>'Growth','price'=>'Contact us','period'=>'','description'=>'A connected marketing workflow for teams.','features'=>['Everything in Starter','Creative workflows','Social scheduling','Analytics and optimization'],'featured'=>true],
  ['name'=>'Scale','price'=>'Custom','period'=>'','description'=>'More control for established teams.','features'=>['Everything in Growth','Advanced governance','Multi-campaign workflows','Custom onboarding'],'featured'=>false],
 ],
];
