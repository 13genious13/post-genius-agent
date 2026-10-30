<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PublicSiteController extends Controller
{
    private const PAGES = [
        'home' => ['title'=>'Autonomous AI Marketing Team','description'=>'Plan, create, review, publish and improve campaigns with PostGenius Agent, your AI-powered marketing workspace.'],
        'features' => ['title'=>'Features','description'=>'Explore AI research, campaign strategy, content, creative workflows, approvals, social publishing and analytics.'],
        'how-it-works' => ['title'=>'How It Works','description'=>'See how PostGenius Agent connects your business goals with a guided AI marketing workflow.'],
        'pricing' => ['title'=>'Pricing','description'=>'Explore PostGenius Agent plans and request a tailored quote for your marketing needs.'],
        'about' => ['title'=>'About','description'=>'Learn why PostGenius Agent brings planning, creation and campaign measurement into one workspace.'],
        'faq' => ['title'=>'Frequently Asked Questions','description'=>'Answers about PostGenius Agent, AI content generation, approvals, integrations and pricing.'],
        'contact' => ['title'=>'Contact','description'=>'Talk to the PostGenius Agent team about your marketing workflow.'],
        'privacy' => ['title'=>'Privacy Policy','description'=>'Read the PostGenius Agent privacy policy template.'],
        'terms' => ['title'=>'Terms of Service','description'=>'Read the PostGenius Agent terms of service template.'],
    ];

    public function page(Request $request, string $page = 'home')
    {
        abort_unless(isset(self::PAGES[$page]), 404);
        $meta = self::PAGES[$page];
        $canonical = route('public.'.($page === 'home' ? 'home' : $page));
        return response()->view('public.pages.'.$page, compact('meta','canonical'));
    }

    public function contactSubmit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','min:2','max:100'],
            'email' => ['required','email:rfc','max:254'],
            'company' => ['nullable','string','max:120'],
            'message' => ['required','string','min:10','max:3000'],
            'website' => ['nullable','string','max:0'], // honeypot
        ]);
        unset($data['website']);
        try {
            Mail::raw("New PostGenius Agent enquiry\n\nName: {$data['name']}\nEmail: {$data['email']}\nCompany: ".($data['company'] ?? '-')."\n\nMessage:\n{$data['message']}", function ($mail) use ($data) {
                $mail->to(config('postgenius_public.contact_to'))
                     ->replyTo($data['email'], $data['name'])
                     ->subject('PostGenius Agent website enquiry');
            });
        } catch (\Throwable $e) {
            Log::error('Public contact form delivery failed', ['exception'=>get_class($e)]);
            return back()->withInput()->withErrors(['message'=>'Your message could not be delivered. Please try again later.']);
        }
        return redirect()->route('public.contact')->with('success','Thanks! Your message has been sent.');
    }

    public function sitemap()
    {
        abort_unless(config('postgenius_public.indexable'), 404);
        $pages = array_keys(self::PAGES);
        $urls = array_map(fn ($page) => route('public.'.($page === 'home' ? 'home' : $page)), $pages);
        return response()->view('public.sitemap', compact('urls'))->header('Content-Type','application/xml; charset=UTF-8');
    }

    public function robots()
    {
        $enabled = config('postgenius_public.indexable');
        $body = $enabled ? "User-agent: *\nAllow: /\nDisallow: /dashboard\nDisallow: /login\nDisallow: /register\nSitemap: ".route('public.sitemap')."\n" : "User-agent: *\nDisallow: /\n";
        return response($body)->header('Content-Type','text/plain; charset=UTF-8');
    }
}
