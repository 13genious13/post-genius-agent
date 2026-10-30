<?php
namespace Tests\Feature;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
class Phase9SmokeTest extends TestCase {
 #[Test] public function guests_cannot_view_social_accounts():void {$this->get('/businesses/1/social-accounts')->assertRedirect('/login');}
 #[Test] public function guests_cannot_view_publications():void {$this->get('/publishing/1')->assertRedirect('/login');}
}
