/**
 * Modern Fashion E-commerce Theme
 * JavaScript Functions
 */

(function($) {
    'use strict';

    // Document Ready
    $(document).ready(function() {
        // Mobile Menu Toggle
        $('.navbar-toggler').on('click', function() {
            // Create mobile menu overlay if it doesn't exist
            if ($('.mobile-menu-overlay').length === 0) {
                $('body').append('<div class="mobile-menu-overlay"></div>');
                
                // Clone navigation
                const mobileNav = $('.site-navigation').clone();
                mobileNav.removeClass('d-none d-lg-flex').addClass('mobile-navigation');
                
                // Append to body
                $('body').append(mobileNav);
                
                // Add close button
                mobileNav.prepend('<button class="close-mobile-menu"><i class="fas fa-times"></i></button>');
                
                // Handle close button click
                $('.close-mobile-menu').on('click', function() {
                    $('.mobile-menu-overlay').removeClass('active');
                    $('.mobile-navigation').removeClass('active');
                    $('body').removeClass('menu-open');
                });
                
                // Handle overlay click
                $('.mobile-menu-overlay').on('click', function() {
                    $('.mobile-menu-overlay').removeClass('active');
                    $('.mobile-navigation').removeClass('active');
                    $('body').removeClass('menu-open');
                });
                
                // Handle dropdown toggles
                $('.mobile-navigation .dropdown-toggle').each(function() {
                    $(this).after('<span class="dropdown-toggle-btn"><i class="fas fa-chevron-down"></i></span>');
                });
                
                // Toggle dropdown menus
                $('.mobile-navigation .dropdown-toggle-btn').on('click', function(e) {
                    e.preventDefault();
                    $(this).toggleClass('active');
                    $(this).closest('.dropdown').find('.dropdown-menu').slideToggle(300);
                });
            }
            
            // Toggle mobile menu
            $('.mobile-menu-overlay').addClass('active');
            $('.mobile-navigation').addClass('active');
            $('body').addClass('menu-open');
        });
        
        // Search Toggle
        $('.search-btn').on('click', function() {
            $('.search-overlay').fadeIn(200).css('display', 'flex');
            $('.search-input').focus();
            $('body').addClass('search-open');
        });
        
        // Close Search
        $('.close-search').on('click', function() {
            $('.search-overlay').fadeOut(200);
            $('body').removeClass('search-open');
        });
        
        // Close search on escape key
        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                $('.search-overlay').fadeOut(200);
                $('body').removeClass('search-open');
            }
        });
        
        // Back to Top Button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.back-to-top').addClass('show');
            } else {
                $('.back-to-top').removeClass('show');
            }
        });
        
        $('.back-to-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 800);
        });
        
        // Product Quick View
        $('.quick-view').on('click', function() {
            // In a real implementation, this would open a modal with product details
            // For now, we'll just show an alert
            alert('Quick view functionality would be implemented here');
        });
        
        // Add to Cart
        $('.add-to-cart').on('click', function() {
            // In a real implementation, this would add the product to cart
            // For now, we'll just show an alert
            alert('Product added to cart!');
        });
        
        // Add to Wishlist
        $('.add-to-wishlist').on('click', function() {
            $(this).find('i').toggleClass('far fas');
            // In a real implementation, this would add the product to wishlist
            // For now, we'll just show an alert
            alert('Product added to wishlist!');
        });
        
        // Newsletter Form Submission
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            const email = $(this).find('input[type="email"]').val();
            
            if (email) {
                // In a real implementation, this would submit the form to a server
                // For now, we'll just show an alert
                alert('Thank you for subscribing!');
                $(this).find('input[type="email"]').val('');
            } else {
                alert('Please enter a valid email address');
            }
        });
        
        // Initialize product image hover effect
        initProductHoverEffect();
        
        // Sticky Header on Scroll
        let lastScrollTop = 0;
        const header = $('.site-header');
        const headerHeight = header.outerHeight();
        
        $(window).scroll(function() {
            const scrollTop = $(this).scrollTop();
            
            // Add sticky class when scrolling down
            if (scrollTop > headerHeight) {
                header.addClass('sticky');
                $('body').css('padding-top', headerHeight);
            } else {
                header.removeClass('sticky');
                $('body').css('padding-top', 0);
            }
            
            // Hide/show header when scrolling down/up
            if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
                // Scrolling down
                header.addClass('hide');
            } else {
                // Scrolling up
                header.removeClass('hide');
            }
            
            lastScrollTop = scrollTop;
        });
    });
    
    // Window Load
    $(window).on('load', function() {
        // Hide page loader
        setTimeout(function() {
            $('.page-loader').fadeOut(500);
        }, 500);
    });
    
    // Window Resize
    $(window).on('resize', function() {
        // Reinitialize product hover effect on window resize
        initProductHoverEffect();
    });
    
    // Functions
    function initProductHoverEffect() {
        // Add hover effect for touch devices
        if (isTouchDevice()) {
            $('.product-card').on('touchstart', function() {
                $('.product-card').removeClass('hover');
                $(this).addClass('hover');
            });
        }
    }
    
    // Check if device is touch-enabled
    function isTouchDevice() {
        return (('ontouchstart' in window) ||
                (navigator.maxTouchPoints > 0) ||
                (navigator.msMaxTouchPoints > 0));
    }

})(jQuery);

// Add CSS classes for sticky header
document.addEventListener('DOMContentLoaded', function() {
    // Add CSS for sticky header
    const style = document.createElement('style');
    style.textContent = `
        .site-header.sticky {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.3s ease-out;
            z-index: 999;
        }
        
        .site-header.hide {
            transform: translateY(-100%);
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
        
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .mobile-navigation {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background-color: #fff;
            z-index: 999;
            overflow-y: auto;
            transition: all 0.3s ease;
            padding: 20px;
        }
        
        .mobile-navigation.active {
            left: 0;
        }
        
        .close-mobile-menu {
            position: absolute;
            top: 15px;
            right: 15px;
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        
        .mobile-navigation .nav-menu {
            flex-direction: column;
            margin-top: 50px;
        }
        
        .mobile-navigation .nav-item {
            margin: 0;
            border-bottom: 1px solid #eee;
        }
        
        .mobile-navigation .nav-item a {
            padding: 12px 0;
        }
        
        .mobile-navigation .dropdown-toggle-btn {
            position: absolute;
            right: 0;
            top: 12px;
            cursor: pointer;
        }
        
        .mobile-navigation .dropdown-menu {
            position: static;
            opacity: 1;
            visibility: visible;
            transform: none;
            box-shadow: none;
            padding: 0 0 0 15px;
            display: none;
            min-width: auto;
        }
        
        .mobile-navigation .dropdown-menu h6 {
            padding: 10px 0;
        }
        
        body.menu-open,
        body.search-open {
            overflow: hidden;
        }
        
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-card.hover .product-actions {
            bottom: 0;
        }
    `;
    document.head.appendChild(style);
    
    // Create page loader
    const loader = document.createElement('div');
    loader.className = 'page-loader';
    loader.innerHTML = '<div class="spinner-border text-dark" role="status"><span class="visually-hidden">Loading...</span></div>';
    document.body.appendChild(loader);
}); 