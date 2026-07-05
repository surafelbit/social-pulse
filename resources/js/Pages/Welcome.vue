<template>
  <div class="sp-welcome font-body-md text-body-md antialiased sp-root" style="background-color: var(--sp-bg); color: var(--sp-text); min-height: 100vh;">

    <!-- Ambient: dot grid + film grain -->
    <div class="sp-ambient" aria-hidden="true">
      <svg class="sp-dotgrid" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="sp-dots" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
            <circle cx="1.4" cy="1.4" r="1.4" fill="var(--sp-primary-soft)" />
          </pattern>
          <radialGradient id="sp-fade" cx="50%" cy="30%" r="70%">
            <stop offset="0%" stop-color="hsl(120, 10%, 6%)" stop-opacity="0" />
            <stop offset="100%" stop-color="hsl(120, 10%, 6%)" stop-opacity="1" />
          </radialGradient>
          <mask id="sp-mask">
            <rect width="100%" height="100%" fill="url(#sp-fade)" />
          </mask>
        </defs>
        <rect width="100%" height="100%" fill="url(#sp-dots)" mask="url(#sp-mask)" />
      </svg>
      <div class="sp-grain"></div>
    </div>

    <!-- Header -->
    <header class="fixed top-0 w-full z-50 sp-header" :class="{ 'sp-header--scrolled': scrolled }">
      <div class="flex justify-between items-center px-margin-mobile md:px-margin-desktop py-4 max-w-container-max mx-auto">
        <div class="flex items-center gap-3">
          <span class="sp-logo-orb" aria-hidden="true">
            <span class="sp-logo-orb-core"></span>
          </span>
          <a class="font-headline font-bold text-lg tracking-tight sp-brand" href="#">Social<span class="sp-gradient-text">Pulse</span></a>
        </div>
        <nav class="hidden md:flex items-center gap-8" aria-label="Main navigation">
          <a class="sp-nav-link sp-nav-link--active" href="#">Features</a>
          <a class="sp-nav-link" href="#">Community</a>
          <a class="sp-nav-link flex items-center gap-2" href="#">
            <span class="sp-live-dot" aria-hidden="true"></span>
            Live
          </a>
          <a class="sp-nav-link" href="#">About</a>
        </nav>
        <div class="flex items-center gap-3">
          <button
            @click="toggleTheme"
            id="welcome-theme-toggle"
            class="w-9 h-9 rounded-full flex items-center justify-center sp-icon-btn"
            style="color: var(--sp-text-2); border: 1px solid var(--sp-border);"
            :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
          >
            <svg v-if="isDark" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
          </button>
          <a class="sp-nav-login text-sm font-semibold px-3 py-2 rounded-lg" href="login">Login</a>
          <a href="/register" ref="navRegisterBtn" @mousemove="magnetic" @mouseleave="resetMagnetic" class="sp-btn-primary text-sm px-5 py-2 sp-magnetic">
            Register
          </a>
        </div>
      </div>
    </header>

    <main class="pt-24 overflow-hidden relative z-10" ref="mainContainer">

      <!-- Hero: asymmetric editorial split -->
      <section class="sp-hero px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
        <div class="floating-orb -top-20 -left-20 orb-element" aria-hidden="true"></div>
        <div class="floating-orb top-1/3 -right-32 orb-element" aria-hidden="true"></div>

        <div class="sp-hero-grid">
          <div class="sp-hero-copy">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card mb-8 reveal">
              <span class="flex h-2 w-2 relative" aria-hidden="true">
                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full sp-ping-dot opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 sp-ping-dot"></span>
              </span>
              <span class="text-label-sm font-label-sm sp-accent-text tracking-widest uppercase">Global Pulse v2.0</span>
            </div>

            <h1 class="font-headline-xl text-headline-xl md:text-[3.75rem] mb-6 leading-[1.08] tracking-tight text-on-surface reveal reveal-delay-1">
              Feel the <em class="sp-display-accent not-italic">Beat</em> of the<br class="hidden sm:block" />
              global community
            </h1>

            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-lg mb-10 reveal reveal-delay-2">
              Real moments, real-time. A social space built on trust and human connection — not algorithmic noise.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mb-12 reveal reveal-delay-3">
              <button
                ref="heroCta"
                @mousemove="magnetic"
                @mouseleave="resetMagnetic"
                class="sp-btn-hero sp-magnetic sp-cta-ring"
              >
                Get Started
              </button>
              <button class="sp-btn-ghost glass-card flex items-center justify-center gap-3 group">
                <svg class="w-5 h-5 sp-accent-icon transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.6"/>
                  <path d="M10 8.5L16 12L10 15.5V8.5Z" fill="currentColor"/>
                </svg>
                Watch Demo
              </button>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-6 reveal reveal-delay-4">
              <div class="flex -space-x-3" aria-hidden="true">
                <svg v-for="(a, i) in avatars" :key="i" class="w-11 h-11 rounded-full border-2 sp-avatar-ring" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <linearGradient :id="`sp-av-grad-${i}`" x1="0" y1="0" x2="1" y2="1">
                      <stop offset="0%" :stop-color="a.c1" />
                      <stop offset="100%" :stop-color="a.c2" />
                    </linearGradient>
                  </defs>
                  <circle cx="24" cy="24" r="24" :fill="`url(#sp-av-grad-${i})`" />
                  <text x="24" y="30" text-anchor="middle" font-size="15" font-weight="700" fill="hsl(120, 8%, 12%)" font-family="'Hanken Grotesk', sans-serif">{{ a.initials }}</text>
                </svg>
                <div class="w-11 h-11 rounded-full border-2 sp-avatar-count flex items-center justify-center text-label-sm font-bold" style="border-color: var(--sp-bg);">
                  +{{ statDisplay }}
                </div>
              </div>
              <p class="text-label-sm font-label-sm text-on-surface-variant max-w-[200px]">Trusted by over 2 million people worldwide</p>
            </div>
          </div>

          <div class="sp-hero-visual reveal reveal-delay-2">
            <svg class="sp-pulseline" viewBox="0 0 1200 200" preserveAspectRatio="none" aria-hidden="true">
              <defs>
                <linearGradient id="sp-line-fade" x1="0" y1="0" x2="1" y2="0">
                  <stop offset="0%" stop-color="var(--sp-primary)" stop-opacity="0" />
                  <stop offset="20%" stop-color="var(--sp-primary)" stop-opacity="0.85" />
                  <stop offset="50%" stop-color="var(--sp-primary)" stop-opacity="1" />
                  <stop offset="80%" stop-color="var(--sp-primary)" stop-opacity="0.85" />
                  <stop offset="100%" stop-color="var(--sp-primary)" stop-opacity="0" />
                </linearGradient>
              </defs>
              <path
                class="sp-pulseline-path"
                d="M0,100 L260,100 L300,100 L330,40 L360,160 L390,20 L420,180 L450,100 L500,100 L900,100 L930,60 L960,140 L990,100 L1200,100"
                fill="none" stroke="url(#sp-line-fade)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
              />
            </svg>

            <div class="sp-hero-collage">
              <div class="sp-collage-card sp-collage-card--main">
                <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=900&q=80" alt="Friends sharing a moment together outdoors" loading="lazy" />
              </div>
              <div class="sp-collage-card sp-collage-card--sub">
                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=700&q=80" alt="A group of friends laughing together" loading="lazy" />
              </div>
              <div class="sp-collage-badge">
                <span class="sp-live-dot" aria-hidden="true"></span>
                <span>1.2K pulses today</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Live activity marquee -->
      <section class="py-6 border-y sp-marquee-section" style="border-color: var(--sp-border);" aria-label="Live community activity">
        <div class="sp-marquee">
          <div class="sp-marquee-track">
            <span v-for="(item, i) in [...liveFeed, ...liveFeed]" :key="i" class="sp-marquee-item">
              <span class="sp-live-dot sp-live-dot--sm" aria-hidden="true"></span>
              {{ item }}
            </span>
          </div>
        </div>
      </section>

      <!-- Stats strip -->
      <section class="px-margin-mobile md:px-margin-desktop py-stack-lg max-w-container-max mx-auto">
        <div class="sp-stats-grid reveal">
          <div v-for="(s, i) in stats" :key="i" class="sp-stat glass-card">
            <span class="sp-stat-value font-headline">{{ s.value }}</span>
            <span class="sp-stat-label text-on-surface-variant">{{ s.label }}</span>
          </div>
        </div>
      </section>

      <!-- Features: asymmetric bento -->
      <section class="px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto" id="features">
        <div class="sp-section-header reveal">
          <p class="sp-section-eyebrow text-label-sm uppercase tracking-widest mb-4">Capabilities</p>
          <h2 class="font-headline-lg text-headline-lg mb-4 text-on-surface">Redefining social interaction</h2>
          <p class="text-body-md text-on-surface-variant max-w-xl">Three pillars that make Pulse feel alive — not another feed.</p>
          <div class="sp-underline-grow mt-6"></div>
        </div>

        <div class="sp-bento-grid">
          <article
            v-for="(f, i) in features"
            :key="i"
            class="glass-card animated-card sp-bento-card group sp-tilt"
            :class="{ 'sp-bento-card--featured': i === 0 }"
            @mousemove="tilt"
            @mouseleave="resetTilt"
          >
            <div v-if="f.image" class="sp-feature-photo">
              <img :src="f.image" :alt="f.title" loading="lazy" />
              <span class="sp-feature-photo-icon">
                <span class="material-symbols-outlined sp-accent-icon text-2xl">{{ f.icon }}</span>
              </span>
            </div>
            <div v-else class="w-16 h-16 rounded-2xl sp-icon-well flex items-center justify-center sp-icon-pop">
              <span class="material-symbols-outlined sp-accent-icon text-4xl">{{ f.icon }}</span>
            </div>
            <div class="sp-bento-body">
              <h3 class="font-headline-md text-headline-md mb-3 text-on-surface">{{ f.title }}</h3>
              <p class="font-body-md text-body-md text-on-surface-variant">{{ f.desc }}</p>
            </div>
            <div class="sp-bento-footer">
              <span>{{ f.cta }}</span>
              <span class="material-symbols-outlined text-sm sp-accent-icon" aria-hidden="true">arrow_forward</span>
            </div>
          </article>
        </div>
      </section>

      <!-- CTA -->
      <section class="relative px-margin-mobile md:px-margin-desktop py-stack-xl max-w-container-max mx-auto">
        <div class="relative glass-card sp-cta-shell rounded-[2rem] overflow-hidden p-stack-lg md:p-24 text-center reveal">
          <div class="absolute inset-0 z-0 sp-cta-photo" aria-hidden="true">
            <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&w=1600&q=80" alt="" loading="lazy" />
          </div>
          <svg class="absolute inset-0 z-0 w-full h-full sp-cta-pattern" preserveAspectRatio="xMidYMid slice" viewBox="0 0 800 400" aria-hidden="true">
            <defs>
              <radialGradient id="sp-cta-glow" cx="50%" cy="0%" r="80%">
                <stop offset="0%" stop-color="var(--sp-primary)" stop-opacity="0.2" />
                <stop offset="100%" stop-color="var(--sp-primary)" stop-opacity="0" />
              </radialGradient>
            </defs>
            <rect width="800" height="400" fill="url(#sp-cta-glow)" />
            <g class="sp-cta-rings" stroke="var(--sp-primary-soft)" stroke-width="1">
              <circle cx="400" cy="180" r="90" />
              <circle cx="400" cy="180" r="150" />
              <circle cx="400" cy="180" r="210" class="sp-ring-pulse" />
            </g>
          </svg>
          <div class="relative z-10 flex flex-col items-center">
            <h2 class="font-headline-xl text-headline-xl mb-4 text-on-surface">Join the pulse</h2>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-10 max-w-xl">
              Don't just watch the conversation — be the rhythm that drives it forward.
            </p>
            <form class="w-full max-w-md flex flex-col sm:flex-row gap-4" @submit.prevent>
              <label class="sr-only" for="welcome-email">Email address</label>
              <input
                id="welcome-email"
                class="sp-input flex-grow"
                placeholder="Enter your email"
                type="email"
                autocomplete="email"
              />
              <button
                ref="joinBtn"
                type="submit"
                @mousemove="magnetic"
                @mouseleave="resetMagnetic"
                class="sp-btn-primary px-8 py-4 text-sm whitespace-nowrap sp-magnetic"
              >
                Register Now
              </button>
            </form>
            <p class="mt-6 text-label-sm font-label-sm text-on-surface-variant opacity-80">
              Join today and get a verified founder badge on your profile.
            </p>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="w-full py-stack-xl border-t sp-footer relative z-10">
      <div class="flex flex-col md:flex-row justify-between items-center px-margin-mobile md:px-margin-desktop gap-8 max-w-container-max mx-auto">
        <div class="flex flex-col gap-2 items-center md:items-start">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined sp-accent-icon text-2xl" style="font-variation-settings: 'FILL' 1;" aria-hidden="true">leak_add</span>
            <span class="font-headline-md text-headline-md font-bold text-on-surface tracking-tighter">Social Pulse</span>
          </div>
          <p class="font-body-sm text-body-sm text-on-surface-variant">© 2026 Social Pulse. All rights reserved.</p>
        </div>
        <nav class="flex flex-wrap justify-center gap-x-8 gap-y-4" aria-label="Footer navigation">
          <a class="sp-footer-link" href="#">Privacy Policy</a>
          <a class="sp-footer-link" href="#">Terms of Service</a>
          <a class="sp-footer-link" href="#">Help Center</a>
          <a class="sp-footer-link" href="#">Careers</a>
          <a class="sp-footer-link" href="#">Contact</a>
        </nav>
        <div class="flex gap-3">
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center sp-icon-btn" href="#" aria-label="Website">
            <span class="material-symbols-outlined text-[20px]">public</span>
          </a>
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center sp-icon-btn" href="#" aria-label="Email">
            <span class="material-symbols-outlined text-[20px]">alternate_email</span>
          </a>
          <a class="w-10 h-10 rounded-full glass-card flex items-center justify-center sp-icon-btn" href="#" aria-label="Share">
            <span class="material-symbols-outlined text-[20px]">share</span>
          </a>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const mainContainer = ref(null);
const scrolled = ref(false);
let observer = null;

const isDark = ref(
  typeof window !== 'undefined'
    ? document.documentElement.classList.contains('dark')
    : false
);

const toggleTheme = () => {
  isDark.value = !isDark.value;
  if (isDark.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('sp-theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('sp-theme', 'light');
  }
};

const avatars = [
  { initials: 'A', c1: 'hsl(120, 35%, 42%)', c2: 'hsl(120, 40%, 28%)' },
  { initials: 'M', c1: 'hsl(130, 30%, 48%)', c2: 'hsl(120, 38%, 32%)' },
  { initials: 'J', c1: 'hsl(140, 28%, 50%)', c2: 'hsl(125, 35%, 36%)' },
  { initials: 'S', c1: 'hsl(115, 32%, 44%)', c2: 'hsl(120, 42%, 30%)' },
];

const stats = [
  { value: '2M+', label: 'Active members' },
  { value: '140+', label: 'Countries' },
  { value: '<50ms', label: 'Pulse latency' },
  { value: '99.9%', label: 'Uptime' },
];

const features = [
  {
    icon: 'bolt',
    title: 'Real-time Pulse',
    desc: 'Zero-latency updates for a truly immediate social experience. Content arrives as it happens — not when an algorithm decides.',
    cta: 'Learn more',
    image: 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?auto=format&fit=crop&w=700&q=80',
  },
  {
    icon: 'groups',
    title: 'Deep Connections',
    desc: 'Find your tribe in specialized community groups. Quality interactions over quantity, fostering bonds that last.',
    cta: 'Browse groups',
    image: 'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=700&q=80',
  },
  {
    icon: 'verified_user',
    title: 'Secure Sharing',
    desc: "Privacy isn't an option — it's our foundation. Your data stays yours, with full control over your digital footprint.",
    cta: 'Our manifesto',
  },
];

const liveFeed = [
  'Amara just started a live pulse in Lagos',
  'Kenji joined the Design Systems group',
  'Priya shared a moment from Tokyo',
  'A new community "Indie Makers" just launched',
  'Liam hit 10K pulses this week',
  'Fatima went live from Cairo',
];

const statDisplay = ref('0');
const animateStat = () => {
  const target = 2;
  let current = 0;
  const steps = 40;
  const inc = target / steps;
  const timer = setInterval(() => {
    current += inc;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    statDisplay.value = `${current.toFixed(1)}M`;
  }, 30);
};

const magnetic = (e) => {
  const el = e.currentTarget;
  const rect = el.getBoundingClientRect();
  const x = e.clientX - rect.left - rect.width / 2;
  const y = e.clientY - rect.top - rect.height / 2;
  el.style.transform = `translate(${x * 0.15}px, ${y * 0.28}px)`;
};
const resetMagnetic = (e) => {
  e.currentTarget.style.transform = 'translate(0px, 0px)';
};

const tilt = (e) => {
  const el = e.currentTarget;
  const rect = el.getBoundingClientRect();
  const x = (e.clientX - rect.left) / rect.width - 0.5;
  const y = (e.clientY - rect.top) / rect.height - 0.5;
  el.style.transform = `perspective(800px) rotateX(${-y * 5}deg) rotateY(${x * 5}deg) translateY(-4px)`;
};
const resetTilt = (e) => {
  e.currentTarget.style.transform = 'perspective(800px) rotateX(0deg) rotateY(0deg) translateY(0px)';
};

const handleMouseMove = (e) => {
  const orbs = document.querySelectorAll('.orb-element');
  const x = (e.clientX / window.innerWidth) - 0.5;
  const y = (e.clientY / window.innerHeight) - 0.5;
  orbs.forEach((orb, index) => {
    const speed = (index + 1) * 32;
    orb.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
  });
};

const handleScroll = () => {
  scrolled.value = window.scrollY > 12;
};

onMounted(() => {
  window.addEventListener('mousemove', handleMouseMove);
  window.addEventListener('scroll', handleScroll, { passive: true });

  observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('sp-revealed');
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.animated-card').forEach(card => {
    card.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-8');
    observer.observe(card);
  });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  animateStat();
});

onUnmounted(() => {
  window.removeEventListener('mousemove', handleMouseMove);
  window.removeEventListener('scroll', handleScroll);
  if (observer) observer.disconnect();
});
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&display=swap");

.sp-welcome {
  font-family: "Hanken Grotesk", sans-serif;
}

.sp-root {
  position: relative;
}

/* ── Ambient & grain ── */
.sp-ambient {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
}
.sp-dotgrid {
  width: 100%;
  height: 100%;
  opacity: 0.55;
}
.sp-grain {
  position: absolute;
  inset: 0;
  opacity: 0.035;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  mix-blend-mode: overlay;
}
.dark .sp-grain {
  opacity: 0.06;
}

/* ── Tokens (page-scoped) ── */
.sp-accent-text { color: var(--sp-primary); }
.sp-accent-icon { color: var(--sp-primary); }
.sp-ping-dot { background-color: var(--sp-primary); }

.sp-gradient-text {
  background: linear-gradient(135deg, var(--sp-primary-dark) 0%, var(--sp-primary) 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.sp-display-accent {
  font-family: "Fraunces", serif;
  font-weight: 700;
  font-style: italic;
  color: var(--sp-primary);
  letter-spacing: -0.02em;
}

/* ── Cards ── */
.glass-card {
  position: relative;
  z-index: 1;
  background-color: var(--sp-card);
  border: 1px solid var(--sp-border);
  backdrop-filter: blur(20px);
  transition:
    border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
    box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1),
    background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1),
    transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.glass-card:hover {
  border-color: color-mix(in srgb, var(--sp-primary) 35%, var(--sp-border));
}
:root .glass-card:hover {
  box-shadow: 0 16px 40px var(--sp-primary-glow);
}
.dark .glass-card:hover {
  box-shadow: none;
  background-color: var(--sp-bg-3);
  border-color: color-mix(in srgb, var(--sp-primary) 45%, var(--sp-border));
}

/* ── Buttons ── */
.sp-btn-hero {
  background: linear-gradient(135deg, var(--sp-primary) 0%, var(--sp-primary-dark) 100%);
  color: var(--sp-on-primary);
  font-weight: 700;
  font-size: 0.875rem;
  letter-spacing: 0.02em;
  padding: 1rem 2.5rem;
  border-radius: 0.75rem;
  border: none;
  cursor: pointer;
  transition:
    transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
    box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1),
    filter 0.2s ease;
}
:root .sp-btn-hero:hover {
  box-shadow: 0 8px 28px var(--sp-primary-glow);
}
.dark .sp-btn-hero:hover {
  box-shadow: none;
  filter: brightness(1.08);
}
.sp-btn-hero:active { transform: scale(0.98); }

.sp-btn-ghost {
  padding: 1rem 2rem;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.875rem;
  color: var(--sp-text);
  cursor: pointer;
  transition: background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.sp-btn-ghost:hover { background-color: var(--sp-bg-2); }
.sp-btn-ghost:active { transform: scale(0.98); }

.sp-cta-ring { position: relative; }
.sp-cta-ring::after {
  content: '';
  position: absolute;
  inset: -6px;
  border-radius: inherit;
  border: 1.5px solid color-mix(in srgb, var(--sp-primary) 50%, transparent);
  opacity: 0;
  transform: scale(0.92);
  transition: opacity 0.25s cubic-bezier(0.4, 0, 0.2, 1), transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: none;
}
.sp-cta-ring:hover::after {
  opacity: 1;
  transform: scale(1);
}

.sp-magnetic {
  transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
}

/* ── Header ── */
.sp-header {
  transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border-bottom: 1px solid transparent;
}
.sp-header--scrolled {
  background-color: color-mix(in srgb, var(--sp-bg) 82%, transparent);
  backdrop-filter: blur(16px);
  border-bottom-color: var(--sp-border);
}
:root .sp-header--scrolled {
  box-shadow: 0 8px 24px hsla(120, 10%, 6%, 0.06);
}
.dark .sp-header--scrolled {
  box-shadow: none;
}

.sp-nav-link {
  font-size: 0.875rem;
  color: var(--sp-text-2);
  position: relative;
  transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.sp-nav-link::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -4px;
  width: 0;
  height: 2px;
  background: var(--sp-primary);
  transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.sp-nav-link:hover { color: var(--sp-primary); }
.sp-nav-link:hover::after { width: 100%; }
.sp-nav-link--active { color: var(--sp-primary); font-weight: 700; }
.sp-nav-link--active::after { width: 100%; }

.sp-nav-login {
  color: var(--sp-text-2);
  transition: color 0.2s ease, background-color 0.2s ease;
}
.sp-nav-login:hover {
  color: var(--sp-primary);
  background-color: var(--sp-primary-soft);
}

.sp-icon-btn {
  transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}
.sp-icon-btn:hover {
  transform: translateY(-2px);
  color: var(--sp-primary);
  border-color: color-mix(in srgb, var(--sp-primary) 40%, var(--sp-border)) !important;
  background-color: var(--sp-primary-soft);
}
.sp-icon-btn:active { transform: scale(0.96); }

.sp-logo-orb {
  position: relative;
  width: 10px;
  height: 10px;
  display: inline-flex;
}
.sp-logo-orb-core {
  width: 10px;
  height: 10px;
  border-radius: 9999px;
  background: var(--sp-primary);
  box-shadow: 0 0 12px var(--sp-primary-glow);
  display: block;
  animation: sp-pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.sp-live-dot {
  width: 8px;
  height: 8px;
  border-radius: 9999px;
  background: var(--sp-primary);
  display: inline-block;
  animation: sp-pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
.sp-live-dot--sm {
  width: 6px;
  height: 6px;
  margin-right: 8px;
}

@keyframes sp-pulse-ring {
  0%, 100% { opacity: 0.55; transform: scale(1); }
  50% { opacity: 1; transform: scale(1.12); }
}

.floating-orb {
  position: absolute;
  width: 360px;
  height: 360px;
  background: radial-gradient(circle, var(--sp-primary-soft) 0%, transparent 70%);
  border-radius: 50%;
  filter: blur(64px);
  z-index: -1;
  pointer-events: none;
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ── Hero layout ── */
.sp-hero {
  position: relative;
  min-height: min(88vh, 820px);
  display: flex;
  align-items: center;
}

.sp-hero-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 3rem;
  align-items: center;
  width: 100%;
}

@media (min-width: 1024px) {
  .sp-hero-grid {
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
  }
  .sp-hero-copy { padding-right: 1rem; }
}

.sp-hero-visual {
  position: relative;
  min-height: 280px;
}

.sp-pulseline {
  position: absolute;
  top: 8%;
  left: -8%;
  width: 116%;
  height: 180px;
  opacity: 0.5;
  pointer-events: none;
  z-index: 0;
}
.sp-pulseline-path {
  stroke-dasharray: 1400;
  stroke-dashoffset: 1400;
  animation: sp-draw-line 6s cubic-bezier(0.4, 0, 0.2, 1) infinite;
}
@keyframes sp-draw-line {
  0% { stroke-dashoffset: 1400; }
  45% { stroke-dashoffset: 0; }
  100% { stroke-dashoffset: -1400; }
}

.sp-hero-collage {
  position: relative;
  width: 100%;
  max-width: 440px;
  height: 300px;
  margin: 2rem auto 0;
}
.sp-collage-card {
  position: absolute;
  overflow: hidden;
  border: 3px solid var(--sp-bg);
  transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.sp-collage-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  filter: saturate(0.95) contrast(1.02);
}
.sp-collage-card--main {
  width: 280px;
  height: 200px;
  left: 0;
  top: 0;
  border-radius: 20px;
  transform: rotate(-4deg);
  z-index: 2;
  box-shadow: 0 20px 48px hsla(120, 10%, 6%, 0.16);
}
.dark .sp-collage-card--main {
  box-shadow: none;
  border-color: var(--sp-border);
}
.sp-collage-card--sub {
  width: 200px;
  height: 140px;
  right: 0;
  bottom: 16px;
  border-radius: 16px;
  transform: rotate(5deg);
  z-index: 1;
  box-shadow: 0 12px 32px hsla(120, 10%, 6%, 0.12);
}
.dark .sp-collage-card--sub {
  box-shadow: none;
  border-color: var(--sp-border-2);
}
.sp-hero-collage:hover .sp-collage-card--main {
  transform: rotate(-6deg) translateY(-4px);
}
.sp-hero-collage:hover .sp-collage-card--sub {
  transform: rotate(7deg) translateY(-4px);
}
.sp-collage-badge {
  position: absolute;
  left: 50%;
  bottom: -12px;
  transform: translateX(-50%);
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--sp-text);
  background-color: var(--sp-card);
  border: 1px solid var(--sp-border);
  backdrop-filter: blur(16px);
  z-index: 3;
  white-space: nowrap;
}

.sp-avatar-ring { border-color: var(--sp-bg); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
.sp-avatar-ring:hover { transform: translateY(-4px) scale(1.06); z-index: 2; }
.sp-avatar-count {
  background: var(--sp-primary);
  color: var(--sp-on-primary);
}

/* ── Scroll reveal ── */
.reveal {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
.reveal.sp-revealed {
  opacity: 1;
  transform: translateY(0);
}
.reveal-delay-1 { transition-delay: 0.08s; }
.reveal-delay-2 { transition-delay: 0.16s; }
.reveal-delay-3 { transition-delay: 0.24s; }
.reveal-delay-4 { transition-delay: 0.32s; }

/* ── Marquee ── */
.sp-marquee-section {
  overflow: hidden;
  background: linear-gradient(90deg, var(--sp-bg) 0%, transparent 8%, transparent 92%, var(--sp-bg) 100%);
}
.sp-marquee { overflow: hidden; width: 100%; }
.sp-marquee-track {
  display: flex;
  gap: 3rem;
  width: max-content;
  animation: sp-marquee-scroll 36s linear infinite;
}
.sp-marquee-item {
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
  font-size: 0.875rem;
  color: var(--sp-text-2);
}
@keyframes sp-marquee-scroll {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

/* ── Stats ── */
.sp-stats-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
}
@media (min-width: 768px) {
  .sp-stats-grid { grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
}
.sp-stat {
  padding: 1.5rem;
  border-radius: 1rem;
  display: flex;
  flex-direction: column;
  gap: 4px;
  text-align: center;
}
.sp-stat-value {
  font-size: 1.75rem;
  font-weight: 800;
  letter-spacing: -0.02em;
  color: var(--sp-text);
  line-height: 1.2;
}
.sp-stat-label {
  font-size: 0.8125rem;
  line-height: 1.4;
}

/* ── Features bento ── */
.sp-section-header { margin-bottom: 3rem; max-width: 36rem; }
.sp-section-eyebrow { color: var(--sp-primary); font-weight: 600; }
.sp-underline-grow {
  height: 3px;
  width: 4rem;
  background: var(--sp-primary);
  border-radius: 9999px;
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.15s;
}
.reveal.sp-revealed .sp-underline-grow { transform: scaleX(1); }

.sp-bento-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}
@media (min-width: 768px) {
  .sp-bento-grid {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: auto auto;
  }
  .sp-bento-card--featured {
    grid-row: span 2;
  }
}
.sp-bento-card {
  padding: 1.5rem;
  border-radius: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.sp-bento-card--featured .sp-feature-photo {
  height: 220px;
}
.sp-bento-body { flex: 1; }
.sp-bento-footer {
  display: flex;
  align-items: center;
  gap: 8px;
  padding-top: 1rem;
  border-top: 1px solid var(--sp-border);
  color: var(--sp-primary);
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: gap 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.group:hover .sp-bento-footer { gap: 12px; }

.sp-icon-well {
  background-color: var(--sp-primary-soft);
  transition: background-color 0.3s ease;
}
.group:hover .sp-icon-well {
  background-color: color-mix(in srgb, var(--sp-primary) 16%, transparent);
}

.sp-tilt {
  transform-style: preserve-3d;
  transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease, box-shadow 0.3s ease;
}
.sp-icon-pop {
  transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.group:hover .sp-icon-pop {
  transform: scale(1.08) rotate(-3deg);
}

.sp-feature-photo {
  position: relative;
  width: 100%;
  height: 160px;
  border-radius: 12px;
  overflow: hidden;
}
.sp-feature-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: saturate(0.9);
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), filter 0.4s ease;
}
.sp-feature-photo::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(160deg, color-mix(in srgb, var(--sp-primary) 24%, transparent) 0%, hsla(120, 8%, 8%, 0.32) 65%);
  mix-blend-mode: multiply;
  pointer-events: none;
}
.group:hover .sp-feature-photo img {
  transform: scale(1.06);
  filter: saturate(1);
}
.sp-feature-photo-icon {
  position: absolute;
  bottom: 8px;
  left: 8px;
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--sp-card);
  border: 1px solid var(--sp-border);
  backdrop-filter: blur(10px);
  z-index: 2;
}

/* ── CTA ── */
.sp-cta-shell { border-radius: 2rem; }
.sp-cta-photo { overflow: hidden; }
.sp-cta-photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: grayscale(0.5) brightness(0.52) saturate(1.1) sepia(0.12) hue-rotate(40deg);
  transform: scale(1.04);
}
.sp-cta-photo::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, color-mix(in srgb, var(--sp-bg) 45%, transparent) 0%, color-mix(in srgb, var(--sp-bg) 88%, transparent) 100%);
}
.sp-cta-rings { opacity: 0.6; }
.sp-ring-pulse {
  animation: sp-ring-expand 3.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
  transform-origin: 400px 180px;
}
@keyframes sp-ring-expand {
  0% { opacity: 0.45; transform: scale(0.88); }
  100% { opacity: 0; transform: scale(1.2); }
}

/* ── Footer ── */
.sp-footer {
  background-color: var(--sp-bg);
  border-color: var(--sp-border);
}
.sp-footer-link {
  color: var(--sp-text-2);
  font-size: 0.875rem;
  transition: color 0.2s ease;
}
.sp-footer-link:hover { color: var(--sp-primary); }

.text-on-surface { color: var(--sp-text); }
.text-on-surface-variant { color: var(--sp-text-2); }

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

@media (max-width: 480px) {
  .sp-hero-collage {
    max-width: 300px;
    height: 220px;
  }
  .sp-collage-card--main { width: 200px; height: 150px; }
  .sp-collage-card--sub { width: 140px; height: 100px; bottom: 8px; }
}

@media (prefers-reduced-motion: reduce) {
  .sp-pulseline-path,
  .sp-marquee-track,
  .sp-logo-orb-core,
  .sp-live-dot,
  .sp-ring-pulse {
    animation: none !important;
  }
  .reveal {
    transition: none !important;
    opacity: 1 !important;
    transform: none !important;
  }
}
</style>
