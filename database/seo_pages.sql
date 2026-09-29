-- Creates the seo_pages table (migration 2026_09_29_000001_create_seo_pages_table)
-- for databases imported from aksharac_porville.sql, which predates that migration.
-- Import once via phpMyAdmin → select the database → Import.

CREATE TABLE IF NOT EXISTS `seo_pages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_name` varchar(150) NOT NULL,
  `route_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `og_title` varchar(255) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `og_type` varchar(40) NOT NULL DEFAULT 'website',
  `twitter_card` varchar(40) NOT NULL DEFAULT 'summary_large_image',
  `twitter_title` varchar(255) DEFAULT NULL,
  `twitter_description` text DEFAULT NULL,
  `twitter_image` varchar(500) DEFAULT NULL,
  `twitter_site` varchar(100) DEFAULT NULL,
  `schema_json` longtext DEFAULT NULL,
  `robots_index` tinyint(1) NOT NULL DEFAULT 1,
  `robots_follow` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_include` tinyint(1) NOT NULL DEFAULT 1,
  `sitemap_priority` decimal(2,1) NOT NULL DEFAULT 0.5,
  `sitemap_changefreq` varchar(20) NOT NULL DEFAULT 'weekly',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seo_pages_route_path_unique` (`route_path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mark the migration as run so a later `php artisan migrate` does not try to create it again.
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_09_29_000001_create_seo_pages_table', 3
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_29_000001_create_seo_pages_table'
);
