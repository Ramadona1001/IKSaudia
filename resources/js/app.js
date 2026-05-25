import './bootstrap';
import Alpine from 'alpinejs';

import '@fontsource/inter/400.css';
import '@fontsource/inter/500.css';
import '@fontsource/inter/600.css';
import '@fontsource/inter/700.css';

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

const animateValue = (el, target, duration = 1800) => {
    const start = performance.now();
    const from = 0;
    const suffix = el.dataset.suffix ?? '';
    const prefix = el.dataset.prefix ?? '';

    const tick = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const value = Math.round(from + (target - from) * easeOutExpo(progress));
        el.textContent = `${prefix}${value.toLocaleString()}${suffix}`;
        if (progress < 1) {
            requestAnimationFrame(tick);
        }
    };

    requestAnimationFrame(tick);
};

document.addEventListener('alpine:init', () => {
    Alpine.data('pageLoader', () => ({
        done: false,
        init() {
            const finish = () => {
                this.done = true;
                document.body.classList.remove('is-loading');
                setTimeout(() => this.$el.remove(), 600);
            };

            if (document.readyState === 'complete') {
                setTimeout(finish, 400);
            } else {
                window.addEventListener('load', () => setTimeout(finish, 300), { once: true });
            }
        },
    }));

    Alpine.data('siteHeader', () => ({
        open: false,
        mobileServices: false,
        megaOpen: null,
        scrolled: false,
        init() {
            const onScroll = () => {
                this.scrolled = window.scrollY > 12;
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });

            this.$watch('open', (value) => {
                document.body.style.overflow = value ? 'hidden' : '';
            });
        },
        toggleMega(key) {
            this.megaOpen = this.megaOpen === key ? null : key;
        },
        closeAll() {
            this.open = false;
            this.megaOpen = null;
            this.mobileServices = false;
        },
    }));

    Alpine.data('scrollReveal', () => ({
        init() {
            const elements = this.$el.querySelectorAll('.reveal, .reveal-fade, .reveal-scale');

            if (prefersReducedMotion()) {
                elements.forEach((el) => el.classList.add('is-visible'));

                return;
            }

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                },
                { threshold: 0.08, rootMargin: '0px 0px -48px 0px' },
            );

            elements.forEach((el) => observer.observe(el));
        },
    }));

    Alpine.data('statCounter', () => ({
        init() {
            if (prefersReducedMotion()) {
                this.$el.querySelectorAll('[data-count]').forEach((el) => {
                    el.textContent = `${el.dataset.prefix ?? ''}${Number(el.dataset.count).toLocaleString()}${el.dataset.suffix ?? ''}`;
                });

                return;
            }

            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }
                        const el = entry.target;
                        animateValue(el, Number(el.dataset.count), Number(el.dataset.duration) || 2000);
                        observer.unobserve(el);
                    });
                },
                { threshold: 0.4 },
            );

            this.$el.querySelectorAll('[data-count]').forEach((el) => observer.observe(el));
        },
    }));

    Alpine.data('servicesTabs', () => ({
        active: 0,
        setActive(index) {
            this.active = index;
        },
    }));

    Alpine.data('projectsCarousel', () => ({
        index: 0,
        total: 0,
        init() {
            this.total = this.$refs.track?.children?.length ?? 0;
            const track = this.$refs.track;
            if (!track) {
                return;
            }

            track.addEventListener(
                'scroll',
                () => {
                    const children = [...track.children];
                    const scrollLeft = Math.abs(track.scrollLeft);
                    let closest = 0;
                    let minDist = Infinity;
                    children.forEach((child, i) => {
                        const dist = Math.abs(child.offsetLeft - scrollLeft);
                        if (dist < minDist) {
                            minDist = dist;
                            closest = i;
                        }
                    });
                    this.index = closest;
                },
                { passive: true },
            );
        },
        next() {
            if (this.total <= 1) {
                return;
            }
            this.index = (this.index + 1) % this.total;
            this.scrollToIndex();
        },
        prev() {
            if (this.total <= 1) {
                return;
            }
            this.index = (this.index - 1 + this.total) % this.total;
            this.scrollToIndex();
        },
        goTo(i) {
            this.index = i;
            this.scrollToIndex();
        },
        scrollToIndex() {
            const child = this.$refs.track?.children?.[this.index];
            child?.scrollIntoView({ behavior: prefersReducedMotion() ? 'auto' : 'smooth', inline: 'start', block: 'nearest' });
        },
    }));

    Alpine.data('processTimeline', () => ({
        active: 0,
        setActive(i) {
            this.active = i;
        },
    }));

    Alpine.data('heroSlider', () => ({
        index: 0,
        total: 0,
        autoplay: false,
        interval: 6000,
        timer: null,
        init() {
            this.total = Number(this.$el.dataset.total) || 0;
            this.autoplay = this.$el.dataset.autoplay === '1';
            this.interval = Number(this.$el.dataset.interval) || 6000;

            if (this.autoplay && this.total > 1) {
                this.startAutoplay();
            }
        },
        startAutoplay() {
            this.stopAutoplay();
            this.timer = window.setInterval(() => this.next(), this.interval);
        },
        stopAutoplay() {
            if (this.timer) {
                window.clearInterval(this.timer);
                this.timer = null;
            }
        },
        pause() {
            this.stopAutoplay();
        },
        resume() {
            if (this.autoplay && this.total > 1) {
                this.startAutoplay();
            }
        },
        next() {
            if (this.total <= 1) {
                return;
            }
            this.index = (this.index + 1) % this.total;
        },
        prev() {
            if (this.total <= 1) {
                return;
            }
            this.index = (this.index - 1 + this.total) % this.total;
        },
        goTo(i) {
            this.index = i;
        },
    }));
});

document.body.classList.add('is-loading');

window.Alpine = Alpine;
Alpine.start();
