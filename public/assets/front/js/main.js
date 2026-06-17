/* ============================================================
   IK Saudi Manufacturing — Main JavaScript
   ============================================================ */

'use strict';

/* ============================================================
   Loading Screen
   ============================================================ */
function initLoadingScreen() {
  const screen = document.getElementById('loading-screen');
  if (!screen) return;

  setTimeout(() => {
    screen.classList.add('hidden');
    document.body.classList.add('loaded');
  }, 2000);
}

/* ============================================================
   Navbar
   ============================================================ */
function initNavbar() {
  const nav = document.querySelector('.main-nav');
  if (!nav) return;

  const SCROLL_THRESHOLD = 80;

  function updateNav() {
    if (window.scrollY > SCROLL_THRESHOLD) {
      nav.classList.add('scrolled');
      nav.classList.remove('transparent');
    } else {
      nav.classList.remove('scrolled');
      nav.classList.add('transparent');
    }
  }

  updateNav();
  window.addEventListener('scroll', updateNav, { passive: true });

  // Active link detection
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a, .mobile-nav-links a').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });
}

/* ============================================================
   Mobile Menu
   ============================================================ */
function initMobileMenu() {
  const toggle  = document.querySelector('.nav-toggle');
  const menu    = document.querySelector('.mobile-menu');
  const overlay = document.querySelector('.mobile-overlay');
  if (!toggle || !menu) return;

  function openMenu() {
    toggle.classList.add('open');
    menu.classList.add('open');
    overlay?.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    toggle.classList.remove('open');
    menu.classList.remove('open');
    overlay?.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  closeMenu();

  toggle.addEventListener('click', () => {
    menu.classList.contains('open') ? closeMenu() : openMenu();
  });

  if (overlay) overlay.addEventListener('click', closeMenu);

  // Close on nav link click
  menu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', closeMenu);
  });
}

/* ============================================================
   Hero Swiper
   ============================================================ */
function initHeroSwiper() {
  const el = document.querySelector('.hero-swiper');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper('.hero-swiper', {
    effect: 'fade',
    fadeEffect: { crossFade: true },
    autoplay: { delay: 6000, disableOnInteraction: false },
    loop: true,
    speed: 1200,
    pagination: {
      el: '.hero-swiper .swiper-pagination',
      clickable: true
    },
    navigation: {
      nextEl: '.hero-swiper .swiper-button-next',
      prevEl: '.hero-swiper .swiper-button-prev'
    },
    keyboard: { enabled: true }
  });
}

/* ============================================================
   Services Swiper (mobile slider)
   ============================================================ */
function initServicesSwiper() {
  const el = document.querySelector('.services-swiper');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper('.services-swiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    autoplay: { delay: 4000, disableOnInteraction: false },
    breakpoints: {
      576: { slidesPerView: 2 },
      992: { slidesPerView: 3 }
    },
    pagination: {
      el: '.services-swiper .swiper-pagination',
      clickable: true
    }
  });
}

/* ============================================================
   Industries Swiper
   ============================================================ */
function initIndustriesSwiper() {
  const el = document.querySelector('.industries-swiper');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper('.industries-swiper', {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    speed: 800,
    navigation: {
      nextEl: '.ind-nav-next',
      prevEl: '.ind-nav-prev'
    },
    breakpoints: {
      576: { slidesPerView: 2, spaceBetween: 20 },
      992: { slidesPerView: 3, spaceBetween: 24 },
      1200: { slidesPerView: 3, spaceBetween: 28 }
    }
  });
}

/* ============================================================
   Partners Swiper
   ============================================================ */
function initPartnersSwiper() {
  const el = document.querySelector('.partners-swiper');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper('.partners-swiper', {
    slidesPerView: 2,
    spaceBetween: 20,
    loop: true,
    autoplay: { delay: 2500, disableOnInteraction: false },
    speed: 600,
    breakpoints: {
      576: { slidesPerView: 3 },
      768: { slidesPerView: 4 },
      992: { slidesPerView: 5 },
      1200: { slidesPerView: 6 }
    }
  });
}

/* ============================================================
   Testimonials Swiper
   ============================================================ */
function initTestimonialsSwiper() {
  const el = document.querySelector('.testimonials-swiper');
  if (!el || typeof Swiper === 'undefined') return;

  new Swiper('.testimonials-swiper', {
    slidesPerView: 1,
    spaceBetween: 28,
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    speed: 800,
    pagination: {
      el: '.testimonials-swiper .swiper-pagination',
      clickable: true
    },
    breakpoints: {
      768: { slidesPerView: 2 },
      1200: { slidesPerView: 3 }
    }
  });
}

/* ============================================================
   Animated Counters
   ============================================================ */
function initCounters() {
  const counters = document.querySelectorAll('[data-count]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      observer.unobserve(entry.target);

      const el     = entry.target;
      const target = parseInt(el.dataset.count, 10);
      const suffix = el.dataset.suffix || '';
      const duration = 2000;
      const step   = target / (duration / 16);
      let current  = 0;

      const timer = setInterval(() => {
        current += step;
        if (current >= target) {
          current = target;
          clearInterval(timer);
        }
        el.textContent = Math.floor(current).toLocaleString() + suffix;
      }, 16);
    });
  }, { threshold: 0.5 });

  counters.forEach(el => observer.observe(el));
}

/* ============================================================
   FAQ Accordion
   ============================================================ */
function initFaq() {
  document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const isOpen = item.classList.contains('open');

      // Close all
      document.querySelectorAll('.faq-item.open').forEach(openItem => {
        openItem.classList.remove('open');
      });

      // Open clicked if was closed
      if (!isOpen) item.classList.add('open');
    });
  });
}

/* ============================================================
   Scroll To Top
   ============================================================ */
function initScrollTop() {
  const btn = document.getElementById('scroll-top');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    btn.classList.toggle('visible', window.scrollY > 400);
  }, { passive: true });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/* ============================================================
   Language Switcher
   ============================================================ */
function initLanguageSwitcher() {
  const langBtns = document.querySelectorAll('.lang-btn');
  const html     = document.documentElement;
  const body     = document.body;

  // Server-side rendered locale flow: do NOT swap text client-side, just keep
  // direction/active state in sync with the documentElement lang attribute.
  if (document.body.hasAttribute('data-server-locale')) {
    const serverLang = html.getAttribute('lang') || 'en';
    document.querySelectorAll('.lang-btn').forEach(b => {
      b.classList.toggle('active', b.dataset.lang === serverLang);
    });
    return;
  }

  // Restore saved language (legacy static-mode fallback)
  const savedLang = localStorage.getItem('ik-lang') || 'en';
  applyLanguage(savedLang);

  langBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const lang = btn.dataset.lang;
      applyLanguage(lang);
      localStorage.setItem('ik-lang', lang);
    });
  });

  function applyLanguage(lang) {
    const isAr = lang === 'ar';
    html.setAttribute('lang', lang);
    html.setAttribute('dir', isAr ? 'rtl' : 'ltr');
    body.setAttribute('dir', isAr ? 'rtl' : 'ltr');

    // Update active state on all lang buttons
    document.querySelectorAll('.lang-btn').forEach(b => {
      b.classList.toggle('active', b.dataset.lang === lang);
    });

    // Swap visible text
    document.querySelectorAll('[data-en], [data-ar]').forEach(el => {
      const text = isAr ? el.dataset.ar : el.dataset.en;
      if (text !== undefined) el.textContent = text;
    });

    // Reinitialize AOS to refresh positioning
    if (typeof AOS !== 'undefined') {
      setTimeout(() => AOS.refresh(), 200);
    }
  }
}

/* ============================================================
   Search Modal
   ============================================================ */
function initSearch() {
  const modal     = document.querySelector('.search-modal');
  const openBtns  = document.querySelectorAll('.search-open-btn');
  const closeBtn  = document.querySelector('.search-close');
  const input     = document.querySelector('.search-input');
  if (!modal) return;

  openBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      modal.classList.add('open');
      setTimeout(() => input && input.focus(), 200);
    });
  });

  function closeSearch() {
    modal.classList.remove('open');
  }

  if (closeBtn) closeBtn.addEventListener('click', closeSearch);

  modal.addEventListener('click', e => {
    if (e.target === modal) closeSearch();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSearch();
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
      e.preventDefault();
      modal.classList.add('open');
      setTimeout(() => input && input.focus(), 200);
    }
  });
}

/* ============================================================
   AOS (Animate On Scroll)
   ============================================================ */
function initAOS() {
  if (typeof AOS === 'undefined') return;
  AOS.init({
    duration: 800,
    easing: 'ease-out-cubic',
    once: true,
    offset: 60,
    delay: 0
  });
}

/* ============================================================
   GLightbox
   ============================================================ */
function initLightbox() {
  if (typeof GLightbox === 'undefined') return;
  GLightbox({
    selector: '.glightbox',
    touchNavigation: true,
    loop: true,
    zoomable: true,
    openEffect: 'zoom',
    closeEffect: 'fade'
  });
}

/* ============================================================
   Smooth Hover Ripple on Buttons
   ============================================================ */
function initRipple() {
  document.querySelectorAll('.btn-gold, .btn-blue').forEach(btn => {
    btn.addEventListener('click', function(e) {
      const rect   = this.getBoundingClientRect();
      const ripple = document.createElement('span');
      const size   = Math.max(rect.width, rect.height);
      const x = e.clientX - rect.left - size / 2;
      const y = e.clientY - rect.top  - size / 2;

      ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background: rgba(255,255,255,0.25);
        border-radius: 50%;
        transform: scale(0);
        animation: rippleEffect 0.6s linear;
        pointer-events: none;
      `;

      this.style.position = 'relative';
      this.style.overflow = 'hidden';
      this.appendChild(ripple);

      ripple.addEventListener('animationend', () => ripple.remove());
    });
  });
}

// Inject ripple keyframe
(function injectRippleStyle() {
  const style = document.createElement('style');
  style.textContent = `
    @keyframes rippleEffect {
      to { transform: scale(4); opacity: 0; }
    }
  `;
  document.head.appendChild(style);
})();

/* ============================================================
   Navbar Dropdown Keyboard Navigation
   ============================================================ */
function initDropdownA11y() {
  document.querySelectorAll('.nav-dropdown').forEach(dropdown => {
    const trigger = dropdown.querySelector('a');
    const menu    = dropdown.querySelector('.nav-dropdown-menu');
    if (!trigger || !menu) return;

    trigger.setAttribute('aria-haspopup', 'true');
    trigger.setAttribute('aria-expanded', 'false');

    dropdown.addEventListener('mouseenter', () => {
      trigger.setAttribute('aria-expanded', 'true');
    });

    dropdown.addEventListener('mouseleave', () => {
      trigger.setAttribute('aria-expanded', 'false');
    });
  });
}

/* ============================================================
   Floating Particles Animation (canvas-free)
   ============================================================ */
function initParticles() {
  // Particles are handled via CSS animations — no JS needed
  // This function exists for future enhancement
}

/* ============================================================
   Notification / Form Submit Feedback
   ============================================================ */
function showNotification(message, type = 'success') {
  const notification = document.createElement('div');
  notification.style.cssText = `
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    background: ${type === 'success' ? 'var(--grad-gold)' : '#e84444'};
    color: ${type === 'success' ? 'var(--c-dark)' : '#fff'};
    padding: 14px 30px;
    border-radius: var(--radius-pill);
    font-family: var(--font-head);
    font-size: 0.88rem;
    font-weight: 700;
    z-index: 99999;
    box-shadow: var(--shadow-lg);
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
  `;
  notification.textContent = message;
  document.body.appendChild(notification);

  requestAnimationFrame(() => {
    notification.style.opacity = '1';
    notification.style.transform = 'translateX(-50%) translateY(0)';
  });

  setTimeout(() => {
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(-50%) translateY(20px)';
    setTimeout(() => notification.remove(), 400);
  }, 3500);
}

/* ============================================================
   Contact Form
   ============================================================ */
function initContactForm() {
  const form = document.querySelector('.contact-form');
  if (!form) return;

  // Real Laravel-driven forms should perform a normal POST.
  if (form.hasAttribute('data-real-submit')) {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('[type="submit"]');
      if (!btn) return;
      btn.dataset.originalHtml = btn.innerHTML;
      const label = document.documentElement.lang === 'ar' ? 'جارٍ الإرسال...' : 'Sending...';
      btn.innerHTML = `<i class="bi bi-arrow-repeat"></i> <span>${label}</span>`;
      btn.disabled = true;
    });
    return;
  }

  form.addEventListener('submit', e => {
    e.preventDefault();

    const btn = form.querySelector('[type="submit"]');
    const original = btn.textContent;
    btn.textContent = document.documentElement.lang === 'ar' ? 'جارٍ الإرسال...' : 'Sending...';
    btn.disabled = true;

    setTimeout(() => {
      btn.textContent = original;
      btn.disabled = false;
      form.reset();
      const msg = document.documentElement.lang === 'ar'
        ? 'تم إرسال رسالتك بنجاح!'
        : 'Your message has been sent successfully!';
      showNotification(msg);
    }, 1800);
  });
}

/* ============================================================
   Newsletter Form
   ============================================================ */
function initNewsletterForm() {
  document.querySelectorAll('.newsletter-form').forEach(form => {
    // Allow real server submissions when explicitly opted-in.
    if (form.hasAttribute('data-real-submit')) return;

    form.addEventListener('submit', e => {
      e.preventDefault();
      const msg = document.documentElement.lang === 'ar'
        ? 'شكراً! تم اشتراكك بنجاح.'
        : 'Thank you! You have been subscribed.';
      showNotification(msg);
      form.reset();
    });
  });
}

/* ============================================================
   Sticky Section Highlights (scroll spy)
   ============================================================ */
function initScrollSpy() {
  const sections = document.querySelectorAll('section[id]');
  const navLinks = document.querySelectorAll('.nav-links a');
  if (!sections.length || !navLinks.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const id = entry.target.id;
      navLinks.forEach(link => {
        const href = link.getAttribute('href');
        link.classList.toggle('active', href === `#${id}` || href === `index.html#${id}`);
      });
    });
  }, { threshold: 0.35, rootMargin: '-80px 0px -50% 0px' });

  sections.forEach(s => observer.observe(s));
}

/* ============================================================
   Page Transition Fade-in
   ============================================================ */
function initPageTransition() {
  document.body.style.opacity = '0';
  document.body.style.transition = 'opacity 0.4s ease';
  requestAnimationFrame(() => {
    document.body.style.opacity = '1';
  });
}

/* ============================================================
   Helper: Throttle
   ============================================================ */
function throttle(fn, delay) {
  let last = 0;
  return function(...args) {
    const now = Date.now();
    if (now - last >= delay) {
      last = now;
      fn.apply(this, args);
    }
  };
}

/* ============================================================
   DOMContentLoaded — Bootstrap All Modules
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  initLoadingScreen();
  initNavbar();
  initMobileMenu();
  initHeroSwiper();
  initServicesSwiper();
  initIndustriesSwiper();
  initPartnersSwiper();
  initTestimonialsSwiper();
  initCounters();
  initFaq();
  initScrollTop();
  initLanguageSwitcher();
  initSearch();
  initAOS();
  initLightbox();
  initRipple();
  initDropdownA11y();
  initContactForm();
  initNewsletterForm();
  initScrollSpy();
  initPageTransition();
});

/* ============================================================
   Window Load — Final Cleanup
   ============================================================ */
window.addEventListener('load', () => {
  document.body.classList.add('fully-loaded');
  if (typeof AOS !== 'undefined') AOS.refresh();
});
