<?php $__env->startSection('title', 'Blog'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .blog-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8e 100%);
    }

    .blog-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
    }

    .blog-card .card-img-wrap {
        overflow: hidden;
        height: 200px;
    }

    .blog-card .card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .blog-card:hover .card-img-wrap img {
        transform: scale(1.07);
    }

    .blog-card .card-body {
        padding: 16px 18px 20px;
    }

    .blog-card .category-badge {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #e8f0fe;
        color: #1a56db;
        margin-bottom: 6px;
    }

    .blog-card .post-date {
        font-size: 12px;
        color: #9ca3af;
        margin-left: 8px;
    }

    .blog-card .post-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a202c;
        margin: 8px 0 14px;
        line-height: 1.4;
    }

    .read-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #1a56db;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .read-more-btn:hover {
        background: #1648c0;
        transform: translateX(2px);
        color: #fff;
        text-decoration: none;
    }

    .read-more-btn i {
        font-size: 11px;
        transition: transform 0.2s ease;
    }

    .read-more-btn:hover i {
        transform: translateX(3px);
    }

    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    @media (max-width: 1024px) {
        .blog-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 640px) {
        .blog-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="blog-hero py-14 text-center">
    <h1 class="nunito font-extrabold text-4xl text-white tracking-wide">Blog</h1>
</div>


<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="blog-grid">

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1603048297172-c92544798d5a?w=600&q=80" alt="Fresh Chicken">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Non-Veg</span>
                    <span class="post-date">Apr 3, 2026</span>
                </div>
                <p class="post-title">How to Choose Fresh Chicken for Your Family</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&q=80" alt="Fresh Vegetables">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Vegetables</span>
                    <span class="post-date">Apr 3, 2026</span>
                </div>
                <p class="post-title">Top Benefits of Eating Fresh Vegetables Daily</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&q=80" alt="Seafood">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Seafood</span>
                    <span class="post-date">Apr 4, 2026</span>
                </div>
                <p class="post-title">Why Fresh Seafood is Better Than Frozen</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?w=600&q=80" alt="Fruits">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Fruits</span>
                    <span class="post-date">Apr 5, 2026</span>
                </div>
                <p class="post-title">Farm Fresh Fruits for Better Immunity</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&q=80" alt="FarmSea Warehouse">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">FarmSea</span>
                    <span class="post-date">Apr 6, 2026</span>
                </div>
                <p class="post-title">How FarmSea Maintains Freshness</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?w=600&q=80" alt="Meat Storage">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Tips</span>
                    <span class="post-date">Apr 7, 2026</span>
                </div>
                <p class="post-title">Tips to Store Meat and Seafood Properly</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?w=600&q=80" alt="Organic Vegetables">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Comparison</span>
                    <span class="post-date">Apr 9, 2026</span>
                </div>
                <p class="post-title">Organic vs Regular Vegetables</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=600&q=80" alt="Seasonal Fruits">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Fruits</span>
                    <span class="post-date">Apr 9, 2026</span>
                </div>
                <p class="post-title">Best Seasonal Fruits to Eat</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        
        <div class="blog-card">
            <div class="card-img-wrap">
                <img src="https://images.unsplash.com/photo-1534482421-64566f976cfa?w=600&q=80" alt="Fish Diet">
            </div>
            <div class="card-body">
                <div>
                    <span class="category-badge">Seafood</span>
                    <span class="post-date">Apr 10, 2026</span>
                </div>
                <p class="post-title">Why Fish is Important for Your Diet</p>
                <a href="#" class="read-more-btn">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\pages\blog.blade.php ENDPATH**/ ?>