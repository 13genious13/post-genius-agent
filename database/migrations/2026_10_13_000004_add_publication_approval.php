<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {Schema::table('social_publications',function(Blueprint $t){$t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();$t->timestamp('approved_at')->nullable();});}
 public function down():void {Schema::table('social_publications',function(Blueprint $t){$t->dropConstrainedForeignId('approved_by');$t->dropColumn('approved_at');});}
};
