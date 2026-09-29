<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name', 150);
            $table->string('route_path', 255)->unique();
            $table->boolean('is_active')->default(true);

            // Basic SEO
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('canonical_url', 500)->nullable();

            // Open Graph
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('og_type', 40)->default('website');

            // Twitter Card
            $table->string('twitter_card', 40)->default('summary_large_image');
            $table->string('twitter_title', 255)->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 500)->nullable();
            $table->string('twitter_site', 100)->nullable();

            // Schema (JSON-LD)
            $table->longText('schema_json')->nullable();

            // Sitemap / Crawl
            $table->boolean('robots_index')->default(true);
            $table->boolean('robots_follow')->default(true);
            $table->boolean('sitemap_include')->default(true);
            $table->decimal('sitemap_priority', 2, 1)->default(0.5);
            $table->string('sitemap_changefreq', 20)->default('weekly');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
