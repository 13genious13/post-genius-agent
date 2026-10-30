<?php
namespace Tests\Feature;

use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    public function test_public_pages_are_accessible(): void
    {
        foreach (['/','/features','/how-it-works','/pricing','/about','/faq','/contact','/privacy','/terms'] as $url) {
            $this->get($url)->assertOk()->assertSee('PostGenius');
        }
    }

    public function test_contact_form_sends_message(): void
    {
        Mail::fake();
        $this->post('/contact', [
            'name'=>'Sample User','email'=>'sample@example.com','company'=>'Example Co',
            'message'=>'I would like to discuss the product.','website'=>'',
        ])->assertRedirect(route('public.contact'));
        Mail::assertSentCount(1);
    }

    public function test_contact_form_rejects_honeypot(): void
    {
        Mail::fake();
        $this->post('/contact', [
            'name'=>'Sample User','email'=>'sample@example.com',
            'message'=>'I would like to discuss the product.','website'=>'spam',
        ])->assertSessionHasErrors('website');
        Mail::assertNothingSent();
    }

    public function test_sitemap_is_disabled_by_default(): void
    {
        config()->set('postgenius_public.indexable', false);
        $this->get('/sitemap.xml')->assertNotFound();
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
    }
}
